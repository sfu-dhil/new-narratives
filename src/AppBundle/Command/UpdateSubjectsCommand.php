<?php

namespace AppBundle\Command;

use AppBundle\Entity\Subject;
use AppBundle\Entity\SubjectSource;
use AppBundle\Entity\Work;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use OCLC\Auth\WSKey;
use OCLC\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * UpdateSubjectsCommand command.
 */
class UpdateSubjectsCommand extends ContainerAwareCommand {

    const BATCH_SIZE = 100;

    // append oclcid and ?wskey=
    const URL_PFX = 'http://www.worldcat.org/webservices/catalog/content/';

    private $key;

    private $secret;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct($key, $secret, EntityManagerInterface $em, LoggerInterface $logger) {
        parent::__construct();
        $this->key = $key;
        $this->secret = $secret;
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Configure the command.
     */
    protected function configure() {
        $this->setName('newn:update:subjects')->setDescription('Fetch subject data from OCLC');
    }

    protected function fetch($oclcNumber) {
        $url = self::URL_PFX . $oclcNumber;

        $client = new Client();
        $response = $client->request('GET', $url . "?wskey={$this->key}", array('http_errors' => false,));
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Error: " . $response->getStatusCode() . ": " . $response->getReasonPhrase() . " " . $response->getBody());
        }

        return $response->getBody()->getContents();
    }

    protected function parse($content) {
        $dom = new DOMDocument();
        $dom->loadXML($content);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('marc', 'http://www.loc.gov/MARC21/slim');
        $nodeList = $xpath->query('//marc:datafield[@tag="650"][@ind2="0"]');
        if ( ! $nodeList->length) {
            return array();
        }

        $subjects = array();
        for ($i = 0; $i < $nodeList->length; $i++) {
            // node is a datafield element.
            $node = $nodeList->item($i);
            $children = $node->childNodes;
            if( ! $children->length) {
                continue;
            }
            $subject = '';
            for($j = 0; $j < $children->length; $j++) {
                // child is a subfield element.
                $child = $children->item($j);
                if($child->nodeType === XML_TEXT_NODE) {
                    continue;
                }
                switch($child->getAttribute('code')) {
                    case 'a':
                        $subject = $child->textContent;
                        break;
                    default:
                        $subject .= ' -- ' . $child->textContent;
                }
            }
            $subjects[] = $subject;
        }
        return $subjects;
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     *   Command input, as defined in the configure() method.
     * @param OutputInterface $output
     *   Output destination.
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $subjectRepo = $this->em->getRepository(Subject::class);
        $qb = $this->em->createQueryBuilder();
        $qb->select('w')->from(Work::class, 'w');
        $iterator = $qb->getQuery()->iterate();
        while ($row = $iterator->next()) {
            /** @var Work $work */
            $work = $row[0];
            $matches = array();
            if ( ! preg_match('{/(\d+)$}', $work->getWorldcatUrl(), $matches)) {
                continue;
            }
            $content = $this->fetch($matches[1]);
            $parsedSubjects = $this->parse($content);
            $wc = $this->em->getRepository(SubjectSource::class)->findOneBy(array('name' => 'wc',));

            foreach($parsedSubjects as $subject) {
                $name = preg_replace('[^a-zA-Z0-9-]', '', $subject);
                $label = $subject;
                $subject = $subjectRepo->findOneBy(array(
                    'name' => $name,
                    'subjectSource' => $wc,
                ));
                if( ! $subject) {
                    $subject = new Subject();
                    $subject->setName($name);
                    $subject->setLabel($label);
                    $subject->setSubjectSource($wc);
                    $this->em->persist($subject);
                }
                $subject->addWork($work);
                $work->addSubject($subject);
            }
            $this->em->flush();
            $this->em->clear();
            $output->writeln($work->getId() . '-' . $work->getTitle());
            foreach($work->getSubjects() as $s) {
                $output->writeln("    " . $s);
            }
            sleep(12);
        }
    }

}
