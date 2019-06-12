<?php

namespace AppBundle\Command;

use AppBundle\Entity\SubjectSource;
use AppBundle\Entity\Work;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use OCLC\Auth\WSKey;
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

    const URL_PFX = 'https://worldcat.org/bib/data/';

    private $key;

    private $secret;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct($key, $secret, EntityManagerInterface $em) {
        parent::__construct();
        $this->key = $key;
        $this->secret = $secret;
        $this->em = $em;
    }

    /**
     * Configure the command.
     */
    protected function configure() {
        $this->setName('newn:update:subjects')->setDescription('Fetch subject data from OCLC');
    }

    protected function fetch($oclcNumber) {

        $url = self::URL_PFX . $oclcNumber;
        $wskey = new WSKey($this->key, $this->secret);
        $auth = $wskey->getHMACSignature('GET', $url);

        $client = new Client();
        $response = $client->request('GET', $url, array(
            'http_errors' => false,
            'headers' => array(
                'Authorization' => $auth
            )
        ));
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Error: " . $response->getStatusCode() . ": " .
                $response->getReasonPhrase() . " " . $response->getBody());
        }
        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('marc', 'http://www.loc.gov/MARC21/slim');
        $nodeList = $xpath->query('//marc:datafield[@tag="650"]');
        if ( ! $nodeList->length) {
            return null;
        }

        $subjects = array();
        for ($i = 0; $i < $nodeList->length; $i++) {
            $node = $nodeList->item($i);
            $subject = '';
            foreach ($node->childNodes as $childNode) {
                switch ($childNode->getAttribute('code')) {
                    case 'a':
                        $subject = $childNode->textContent;
                        break;
                    case 'x':
                        $subject .= ' -- ' . $childNode->textContent;
                        break;
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
        $wc = $this->em->getRepository(SubjectSource::class)->findOneBy(array('name' => 'wc',));
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
            $subjects = $this->fetch($matches[1]);
            dump($subjects);
            return;
        }
    }

}
