<?php

namespace EdRush\Extbaser\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\Console\MetadataFilter;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use EdRush\Extbaser\ExtbaseExporter;

/**
 * @todo Handle multiple relations from one table to another for import (Doctrine error "Property xy already declared, must be declared only once...").
 */
class ConvertCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('extbaser:convert')
            ->setDescription('Convert a database scheme to a TYPO3 Extbase Extension.')
            ->addArgument('dbname', InputArgument::REQUIRED, 'The database name.')
            ->addArgument('extensionkey', InputArgument::REQUIRED, 'The target extension key.')

            ->addOption('user', null, InputOption::VALUE_OPTIONAL, 'The database user.')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'The database password.')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The database host.')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The database port.')
            ->addOption('driver', null, InputOption::VALUE_OPTIONAL, 'The database driver.')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to export the target extension to.')

            ->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match entities that should be mapped.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force to overwrite modules of existing ExtensionBuilder.json (roundtrip).')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbName = $input->getArgument('dbname');
        $extensionKey = $input->getArgument('extensionkey');

        //defaults
        $dbUser = $input->getOption('user') ? $input->getOption('user') : 'root';
        $dbPassword = $input->getOption('password') ? $input->getOption('password') : null;
        $dbHost = $input->getOption('host') ? $input->getOption('host') : '127.0.0.1';
        $dbPort = $input->getOption('port') ? $input->getOption('port') : null;
        $dbDriver = $input->getOption('driver') ? $input->getOption('driver') : 'pdo_mysql';
        $exportPath = $input->getOption('path') ? $input->getOption('path') : '.';

        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => $dbName,
            'user' => $dbUser,
            'password' => $dbPassword,
            'host' => $dbHost,
            'driver' => $dbDriver,
            'port' => $dbPort,
        );

        $config = Setup::createAnnotationMetadataConfiguration(array('.'), false);
        $em = EntityManager::create($connectionParams, $config);

        $em->getConfiguration()->setMetadataDriverImpl(
            new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
                $em->getConnection()->getSchemaManager()
            )
        );

        $emName = $input->getOption('em');
        $emName = $emName ? $emName : 'default';

        $exporter = new ExtbaseExporter();
        $exporter->setExtensionKey($extensionKey);
        $exporter->setPath($exportPath);
        $exporter->setOverwriteExistingFiles($input->getOption('force'));

        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);

        $metadata = $cmf->getAllMetadata();
        $metadata = MetadataFilter::filter($metadata, $input->getOption('filter'));

        if ($metadata) {
            $exporter->setMetadata($metadata);
            $output->writeln(sprintf('Importing mapping information from "<info>%s</info>" entity manager', $emName));

            $result = $exporter->exportJson();

            foreach ($exporter->getLogs() as $log) {
                $output->writeln('--'.$log);
            }

            return $result;
        } else {
            $output->writeln('Database does not have any mapping information.', 'ERROR');
            $output->writeln('', 'ERROR');

            return 1;
        }
    }
}
