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
     * If true, commit the import to the database. Set by the commit option
     * at the command line.
     * 
     * @var boolean
     */
    private $commit;
    private $from;
    private $to;

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
        $this->addOption('to', null, InputOption::VALUE_REQUIRED, 'Stop importing at this row.');
    }

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->em = $container->get('doctrine')->getManager();
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
            $column = 'book';
        }
        $repo = $this->em->getRepository(WorkCategory::class);
        $category = $repo->findOneBy(array('name' => $column));
        if (!$category) {
            throw new Exception("Uknown category " . $column);
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

    protected function importContribution($work, $personCol, $rowCol) {
        if (!$personCol || !$rowCol) {
            return;
        }
        $person = $this->getPerson($personCol);
        $role = $this->getRole($rowCol);
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
            throw new Exception('Uknown date type ' . $dateTypeCol);
        }
        $date = new DateYear();
        $date->setValue($dateCol);
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
                throw new Exception('Unknown subject ' . $label);
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
            throw new Exception('Unknown genre ' . $label);
        }
        $work->setGenre($genre);
        $genre->addWork($work);
    }

    protected function setCreatedBy($work, $name) {
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
        $work->setCreatedBy($user);
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
        $this->setWorkCategory($work);
        if ($row[2] && preg_match('/(\d+)/', $row[2], $matches)) {
            $work->setVolume($matches[1]);
        }
        $this->importContribution($work, $row[3], $row[4]);
        $this->importContribution($work, $row[5], $row[6]);
        $this->importContribution($work, $row[7], $row[8]);

        $this->importDate($work, $row[9], $row[10]);
        if ($row[11] && preg_match('/(\d+)/', $row[11], $matches)) {
            $work->setEdition($matches[1]);
        }
        if ($row[12]) {
            $work->setPublicationPlace($row[12]);
        }
        $this->importPublisher($work, $row[13]);
        if ($row[14]) {
            $work->setPhysicalDescription($row[14]);
        }
        if ($row[15]) {
            $work->setIllustrations('Yes' === $row[15]);
        }
        if ($row[16]) {
            $work->setFrontispiece('Yes' === $row[16]);
        }
        if ($row[17]) {
            $work->setTranslationDescription($row[17]);
        }
        if ($row[18]) {
            $work->setDedication($row[18]);
        }
        // 20 is author of preface (skipped)
        // 21 is worldcat subject (skipped)

        if ($row[21]) {
            $work->setWorldcatUrl($row[21]);
        }

        $this->addSubjects($work, array($row[22], $row[23], $row[24]));
        $this->setGenre($work, $row[25]);
        if ($row[26]) {
            $work->setTranscription($row[26] === 'Yes');
        }
        $work->setPhysicalLocations($row[27]);
        $work->setDigitalLocations($row[28]);
        if ($row[29]) {
            $work->setDigitalUrl($row[29]);
        }
        if ($row[30]) {
            $work->setNotes($row[30]);
        }
        if ($row[31]) {
            $this->setCreatedBy($work, $row[31]);
        }

        $this->importContribution($work, $row[32], $row[33]);
        $this->importContribution($work, $row[34], $row[35]);
        $this->importContribution($work, $row[36], $row[37]);

        if ($row[38]) {
            $this->addCheckedBy($work, $row[38]);
        }

        $this->persist($work);
        $this->flush();
    }

    protected function import($path, OutputInterface $output) {
        $fh = fopen($path, 'r');
        $n = 0;
        $headers = fgetcsv($fh);
        $n++;
        $index = $this->headersToIndex($headers);
        while (($row = fgetcsv($fh))) {
            $n++;
            if($this->from && $n < $this->from) {
                continue;
            }
            if($this->to && $this->to < $n) {
                continue;
            }
            $output->writeln( $n . ': ' . $row[0]);
            $this->importRow($row, $index);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $file = $input->getArgument('file');
        if ($input->getOption('commit')) {
            $this->commit = true;
        }
        if ($input->getOption('from')) {
            $this->from = $input->getOption('from');
        }
        if ($input->getOption('to')) {
            $this->to = $input->getOption('to');
        }
        $output->writeln($file);
        $this->import($file, $output);
    }

}
