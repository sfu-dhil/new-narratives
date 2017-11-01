<?php

namespace AppBundle\Command;

use AppBundle\Entity\Contribution;
use AppBundle\Entity\DateCategory;
use AppBundle\Entity\DateYear;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Person;
use AppBundle\Entity\Publisher;
use AppBundle\Entity\Role;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Work;
use AppBundle\Entity\WorkCategory;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Monolog\Logger;
use Nines\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImportCsvCommand extends ContainerAwareCommand {

    /**
     * @var ObjectManager
     */
    private $em;
    
    /**
     *
     * @var Logger
     */
    private $logger;
    
    /**
     * If true, commit the import to the database. Set by the commit option
     * at the command line.
     * 
     * @var boolean
     */
    private $commit;
    
    private $from;
    
    private $lineNumber;
    
    public function __construct($name = null) {
        parent::__construct($name);
        $this->commit = false;
    }

    protected function configure() {
        $this->setName('app:import:csv');
        $this->setDescription('Import a CSV file');
        $this->addArgument('file', InputArgument::REQUIRED, 'File to import.');
        $this->addOption('commit', null, InputOption::VALUE_NONE, 'Commit the import to the database.');
        $this->addOption('from', null, InputOption::VALUE_REQUIRED, 'Start import at this row.');
    }

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->em = $container->get('doctrine')->getManager();
        $this->logger = $container->get('logger');
    }

    protected function headersToIndex($row) {
        $index = array();
        foreach ($row as $idx => $header) {
            $index[$header] = $idx;
        }
        return $index;
    }

    private function persist($entity) {
        if ($this->commit) {
            $this->em->persist($entity);
        }
    }

    private function flush() {
        if ($this->commit) {
            $this->em->flush();
            $this->em->clear();
        }
    }

    protected function setWorkCategory($work, $column = null) {
        if (!$column) {
            $this->logger->error("Missing work category on line {$this->lineNumber}: {$column}");
            $column = 'Book';
        }
        if($column == 'MS') {
            $column = 'Manuscript';
        }
        $repo = $this->em->getRepository(WorkCategory::class);
        $category = $repo->findOneBy(array('label' => $column));
        if (!$category) {
            $this->logger->error("Unknown work category on line {$this->lineNumber}: {$column}");
            $category = $repo->findOneBy(array('label' => 'Book'));
        }
        $work->setWorkCategory($category);
        $category->addWork($work);
    }

    protected function getPerson($column) {
        $repo = $this->em->getRepository(Person::class);
        $person = $repo->findOneBy(array('fullName' => $column));
        if (!$person) {
            $person = new Person();
            $person->setFullName($column);
            $this->persist($person);
        }
        return $person;
    }

    protected function getRole($column) {
        $repo = $this->em->getRepository(Role::class);
        $matches = array();
        if (!preg_match('/\[(\w+)\]/', $column, $matches)) {
            throw new Exception('Cannot find role code in ' . $column);
        }
        $result = $repo->findOneBy(array('name' => $matches[1]));
        if (!$result) {
            throw new Exception('Unknown role code ' . $matches[1]);
        }
        return $result;
    }

    protected function importContribution($work, $personCol, $roleCol) {
        if (!$personCol || !$roleCol) {
            return;
        }
        $person = $this->getPerson($personCol);
        $role = $this->getRole($roleCol);
        $contribution = new Contribution();
        $contribution->setPerson($person);
        $contribution->setRole($role);
        $contribution->setWork($work);
        $work->addContribution($contribution);
        $person->addContribution($contribution);
        $role->addContribution($contribution);
        $this->persist($contribution);
    }

    protected function importDate($work, $dateCol, $dateTypeCol) {
        if (!$dateCol || !$dateTypeCol) {
            return;
        }
        $repo = $this->em->getRepository(DateCategory::class);
        $dateCategory = $repo->findOneBy(array('label' => $dateTypeCol));
        if (!$dateCategory) {
            $this->logger->error("Malformed date type line:{$this->lineNumber}. '{$dateTypeCol}'");
            return;
        }
        $date = new DateYear();
        try {
            $date->setValue($dateCol);
        } catch (\Exception $e) {
            $this->logger->error("Malformed date line:{$this->lineNumber}. '{$dateCol}'");
            return;
        }
        $date->setDateCategory($dateCategory);
        $date->setWork($work);
        $work->addDate($date);
        $dateCategory->addDate($date);
        $this->persist($date);
    }

    public function importPublisher($work, $publisherCol) {
        if (!$publisherCol) {
            return;
        }
        $repo = $this->em->getRepository(Publisher::class);
        $publisher = $repo->findOneBy(array('name' => $publisherCol));
        if (!$publisher) {
            $publisher = new Publisher();
            $publisher->setName($publisherCol);
            $this->persist($publisher);
        }
        $work->setPublisher($publisher);
        $publisher->addWork($work);
    }

    public function addSubjects($work, $subjects = array()) {
        $repo = $this->em->getRepository(Subject::class);
        foreach ($subjects as $label) {
            if (!$label) {
                continue;
            }
            $subject = $repo->findOneBy(array(
                'label' => $label
            ));
            if (!$subject) {
                $this->logger->error("Unknown subject on line {$this->lineNumber}. '{$label}'");
                return;
            }
            $work->addSubject($subject);
            $subject->addWork($work);
        }
    }

    protected function setGenre($work, $label) {
        if (!$label) {
            return;
        }
        $repo = $this->em->getRepository(Genre::class);
        $genre = $repo->findOneBy(array('label' => $label));
        if (!$genre) {
            $this->logger->error("Unknown genre on line {$this->lineNumber}. '{$label}'");
            return;
        }
        $work->setGenre($genre);
        $genre->addWork($work);
    }

    protected function addCheckedBy($work, $name) {
        $repo = $this->em->getRepository(User::class);
        $user = $repo->findOneBy(array(
            'fullname' => $name
        ));
        if (!$user) {
            $user = new User();
            $user->setEmail($name . '@example.com');
            $user->setFullname($name);
            $user->setInstitution('SFU');
            $user->setPlainPassword(md5(uniqid()));
            $this->persist($user);
        }
        $work->addCheckedBy($user);
    }

    protected function importRow($row) {
        $matches = array();
        $work = new Work();
        $work->setTitle($row[0]);
        
        if ($row[2] && preg_match('/(\d+)/', $row[2], $matches)) {
            $work->setVolume($matches[1]);
        } elseif($row[2]) {
            $this->logger->error("Malformed volume line:{$this->lineNumber}, col:2. '{$row[2]}'");
        }
        
        $this->importContribution($work, $row[3], $row[4]);
        $this->importContribution($work, $row[5], $row[6]);
        $this->importContribution($work, $row[7], $row[8]);
        
        $this->importDate($work, $row[9], $row[10]);
        if ($row[11] && preg_match('/(\d+)/', $row[11], $matches)) {
            $work->setEdition($matches[1]);
        } elseif($row[11]) {
            $this->logger->error("Malformed edition line:{$this->lineNumber}, col:11. '{$row[11]}'");
        }
        
        if ($row[12]) {
            $work->setPublicationPlace($row[12]);
        }
        
        $this->importPublisher($work, $row[13]);
        
        if ($row[14]) {
            $work->setPhysicalDescription($row[14]);
        }
        
        switch($row[15]) {
            case '': break;
            case 'Yes': $work->setIllustrations(true); break;
            case 'No': $work->setIllustrations(false); break;
            default: $this->logger->error("Unexpected value line {$this->lineNumber} col 15: '{$row[15]}'");
        }

        switch($row[16]) {
            case '': break;
            case 'Yes': $work->setFrontispiece(true); break;
            case 'No': $work->setFrontispiece(false); break;
            default: $this->logger->error("Unexpected value line {$this->lineNumber} col 16: '{$row[16]}'");
        }

        if ($row[17]) {
            $work->setTranslationDescription($row[17]);
        }
        
        if ($row[18]) {
            $work->setDedication($row[18]);
        }
        // 20 is author of preface (skipped)
        // 21 is worldcat subject (skipped)

        if ($row[21] && filter_var(trim($row[21]), FILTER_VALIDATE_URL)) {
            $work->setWorldcatUrl(trim($row[21]));
        } elseif($row[21]) {
            $this->logger->error("Invalid URL on line {$this->lineNumber} col 21: '{$row[21]}'");
        }

        $this->addSubjects($work, array($row[22], $row[23], $row[24]));
        $this->setGenre($work, $row[25]);
        
        switch($row[26]) {
            case '': break;
            case 'Yes': $work->setTranscription(true); break;
            case 'No': $work->setTranscription(false); break;
            default: $this->logger->error("Unexpected value line {$this->lineNumber} col 26: '{$row[26]}'");
        }

        
        $work->setPhysicalLocations($row[27]);
        $work->setDigitalLocations($row[28]);
        
        if ($row[29] && filter_var(trim($row[29]), FILTER_VALIDATE_URL)) {
            $work->setDigitalUrl(trim($row[29]));
        } elseif($row[29]) {
            $this->logger->error("Invalid URL on line {$this->lineNumber} col 29: '{$row[29]}'");
        }
        
        if ($row[30]) {
            $work->setNotes($row[30]);
        }
        
        if ($row[31]) {
            foreach(preg_split("/\s?[\/,]\s*/", $row[31]) as $checkedBy) {
                $checkedBy = preg_replace("/^\s|\s$/", '', $checkedBy);
                $checkedBy = strtoupper($checkedBy);
                $checkedBy = preg_replace("/[^A-Z]/", '', $checkedBy);
                if( ! preg_match("/^[A-Z]+$/", $checkedBy)) {
                    $this->logger->error("Invalid initials on line {$this->lineNumber} col 31: '{$checkedBy}'");
                    continue;
                }
                $this->addCheckedBy($work, $checkedBy);
            }
        }

        $this->importContribution($work, $row[32], $row[33]);
        $this->importContribution($work, $row[34], $row[35]);
        $this->importContribution($work, $row[36], $row[37]);
        
        $this->setWorkCategory($work, $row[38]);
        
        if($row[39]) {
            $work->setEditorialNotes($row[39]);
        }
        
        switch(strtolower(trim($row[40]))) {
            case '': break;
            case 'yes': $work->setComplete(true); break;
            case 'no': $work->setComplete(false); break;
            default: $this->logger->error("Unexpected value on line {$this->lineNumber} col 40: '$row[40]'");
        }

        $this->persist($work);
        $this->flush();
    }
    
    protected function trim($string) {
        return preg_replace("/^\p{Z}*|\p{Z}*$/u", '', $string);
    }
    
    protected function import($path, OutputInterface $output) {
        $fh = fopen($path, 'r');
        $this->lineNumber = 1;
        $headers = fgetcsv($fh);
        $this->lineNumber++;
        $index = $this->headersToIndex($headers);
        while (($row = fgetcsv($fh))) {
            $row = $this->trim($row);
            $this->lineNumber++;
            if($this->from && $this->lineNumber < $this->from) {
                continue;
            }
            if(count(array_filter($row)) === 0) {
                continue;
            }
            $this->logger->notice($this->lineNumber . ': ' . $row[0]);
            $this->importRow($row, $index);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $file = $input->getArgument('file');
        if ($input->getOption('commit')) {
            $this->commit = true;
        }
        if ($input->getOption('from')) {
            $this->from = $input->getOption('from') + 1;
        }
        try {
            $this->import($file, $output);
        } catch(\Exception $e) {
            $this->logger->critical($e->getMessage() . " in CSV file after line {$this->lineNumber}");
        }
    }

}
