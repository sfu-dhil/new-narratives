<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Importer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import:csv')]
class ImportCsvCommand extends Command {
    private \Doctrine\ORM\EntityManagerInterface $em;

    private int $lineNumber = 1;

    private Importer $importer;

    protected function configure() : void {
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

        while ($row = fgetcsv($fh)) {
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

        return array_map(fn ($d) => preg_replace('/^\s+|\s+$/u', '', (string) $d), $data);
    }

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setImporter(Importer $importer) : void {
        $this->importer = $importer;
    }

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setLogger(LoggerInterface $logger) : void {
        $this->logger = $logger;
    }
}
