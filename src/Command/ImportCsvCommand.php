<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Service\Importer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Exception;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCsvCommand extends Command {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var int
     */
    private $lineNumber = 1;

    private Importer $importer;

    protected static $defaultName = 'app:import:csv';

    protected function configure() : void {
        $this->setName('app:import:csv');
        $this->setDescription('Import a CSV file');
        $this->addArgument('file', InputArgument::REQUIRED, 'File to import.');
        $this->addOption('commit', null, InputOption::VALUE_NONE, 'Commit the import to the database.');
        $this->addOption('skip', null, InputOption::VALUE_REQUIRED, 'Skip this many rows at the start of the CSV', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void {
        $file = $input->getArgument('file');
        $skip = $input->getOption('skip');
        $errors = 0;
        $this->importer->setCommit($input->hasOption('commit'));

        $fh = fopen($file, 'r');
        for ($i = 1; $i < $skip; $i++) {
            $this->lineNumber++;
            $ignored = fgetcsv($fh);
        }

        while (($row = fgetcsv($fh))) {
            $this->lineNumber++;

            try {
                $data = $this->trim($row, 66);
                $this->importer->import($data);
            } catch (ORMException $e) {
                $output->writeln("{$file}:{$this->lineNumber}: {$row[0]}");
                $output->writeln($e->getMessage());
                $output->writeln('Cannot continue. STOPPED.');

                return;
            } catch (Exception $e) {
                $output->writeln("{$file}:{$this->lineNumber}: {$row[0]}");
                $output->writeln($e->getMessage());
                $output->writeln('------------------------------------');
                $errors++;
            }
            $this->em->clear();
        }
        $output->writeln("\n\nImport finished with {$errors} rows not imported.");
    }

    public function trim(array $row, int $columns) : array {
        $data = array_pad($row, $columns, '');
        return array_map(fn ($d) => preg_replace('/^\s+|\s+$/u', '', $d), $data);
    }

    /**
     * @required
     */
    public function setImporter(Importer $importer) : void {
        $this->importer = $importer;
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger) : void {
        $this->logger = $logger;
    }
}
