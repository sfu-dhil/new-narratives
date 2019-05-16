<?php

namespace AppBundle\Command;

use AppBundle\Entity\Work;
use Doctrine\ORM\EntityManagerInterface;
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
        $this->setName('oclc:update:subjects')
             ->setDescription('Fetch subject data from OCLC');
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
        $qb = $this->em->createQueryBuilder();
        $qb->select('w')->from(Work::class, 'w');
        $iterator = $qb->getQuery()->iterate();
        while($row = $iterator->next()) {
            /** @var Work $work */
            $work = $row[0];
            $output->writeln($work->getTitle());
        }
    }

}
