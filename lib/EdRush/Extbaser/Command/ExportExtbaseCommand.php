<?php

namespace EdRush\Extbaser\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use EdRush\Extbaser\ExtbaseExporter;

/**
 * @author weberius
 */
class ExportExtbaseCommand extends Command
{
    /**
     * Get command options for all environments.
     *
     * return array
     */
    public static function getDefaultInputOptions()
    {
        $inputOptions = array(
            new InputOption('filter', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match entities that should be mapped'),
            new InputOption('force', 'f', null, 'Override existing extension'),
            new InputOption('round-trip', 'r', null, 'Roundtrip existing extension'),
        );

        return $inputOptions;
    }

    public static function mapDefaultInputOptions(ExtbaseExporter $exporter, InputInterface $input)
    {
        $exporter->setOverwriteExistingFiles($input->getOption('force'));
        $exporter->setRoundTripExistingFiles($input->getOption('round-trip'));
        $exporter->setFilter($input->getOption('filter'));
    }

    protected function configure()
    {
        $this
            ->setName('extbaser:export')
            ->setDescription('Export an existing database schema to a TYPO3 Extbase Extension')

            ->addArgument('dbname', InputArgument::REQUIRED, 'The database you want to export')
            ->addArgument('extension-key', InputArgument::REQUIRED, 'The target TYPO3 Extension key')

            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to export the extension to', '.')

            //db connection parameters
            ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'The database user', 'root')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'The database password')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The database host', '127.0.0.1')
            ->addOption('driver', null, InputOption::VALUE_OPTIONAL, 'The database driver', 'pdo_mysql')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The database port')
        ;

        foreach (self::getDefaultInputOptions() as $inputOption) {
            $this->getDefinition()->addOption($inputOption);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbName = $input->getArgument('dbname');

        $connectionParams = array(
            'dbname' => $dbName,
            'user' => $input->getOption('user'),
            'password' => $input->getOption('password'),
            'host' => $input->getOption('host'),
            'driver' => $input->getOption('driver'),
            'port' => $input->getOption('port'),
        );

        $config = Setup::createAnnotationMetadataConfiguration(array('.'), false);
        $em = EntityManager::create($connectionParams, $config);

        $em->getConfiguration()->setMetadataDriverImpl(
            new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
                $em->getConnection()->getSchemaManager()
            )
        );

        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);

        $exporter = new ExtbaseExporter($cmf);
        $exporter->setExtensionKey($input->getArgument('extension-key'));
        $exporter->setPath($input->getOption('path'));
        self::mapDefaultInputOptions($exporter, $input);

        $output->writeln(sprintf('Exporting database schema "<info>%s</info>".', $dbName));

        $result = $exporter->exportJson();

        foreach ($exporter->getLogs() as $log) {
            $output->writeln($log);
        }

        return $result ? 0 : 1;
    }
}
