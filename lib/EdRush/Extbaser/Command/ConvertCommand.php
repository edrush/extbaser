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

class ConvertCommand extends \Doctrine\DBAL\Tools\Console\Command\ImportCommand
{
    protected function configure()
    {
        $this
            ->setName('extbaser:convert')
            ->setDescription('Convert a database scheme to a TYPO3 Extbase Extension.')
            ->addArgument('dbName', InputArgument::REQUIRED, 'The database name.')
			->addArgument('extensionKey', InputArgument::REQUIRED, 'The target extension key.')
			
			->addOption('dbUser', null, InputOption::VALUE_OPTIONAL, 'The database user.')
			->addOption('dbPassword', null, InputOption::VALUE_OPTIONAL, 'The database password.')
            ->addOption('dbHost', null, InputOption::VALUE_OPTIONAL, 'The database host.')
			->addOption('dbPort', null, InputOption::VALUE_OPTIONAL, 'The database port.')
			->addOption('dbDriver', null, InputOption::VALUE_OPTIONAL, 'The database driver.')
			->addOption('exportPath', null, InputOption::VALUE_OPTIONAL, 'The path to export the target extension to.')
			
			->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
			->addOption('filter', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match entities that should be mapped.')
			->addOption('force', null, InputOption::VALUE_NONE, 'Force to overwrite existing ExtensionBuilder.json.')
		;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$dbName = $input->getArgument('dbName');
		$extensionKey = $input->getArgument('extensionKey');
		
        //defaults
        $dbUser = $input->getOption('dbUser') ? $input->getOption('dbUser'): 'root'; 
		$dbPassword = $input->getOption('dbPassword') ? $input->getOption('dbPassword') : null;
		$dbHost = $input->getOption('dbHost') ? $input->getOption('dbHost') : '127.0.0.1';
		$dbPort = $input->getOption('dbPort') ? $input->getOption('dbPort') : null;
		$dbDriver = $input->getOption('dbDriver') ? $input->getOption('dbDriver') : 'pdo_mysql';
		$exportPath = $input->getOption('exportPath') ? $input->getOption('exportPath') : '.';
		
		$path =  $exportPath.'/'.$extensionKey;
        
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => $dbName,
            'user' => $dbUser,
            'password' => $dbPassword,
            'host' => $dbHost,
            'driver' => $dbDriver,
            'port' => $dbPort
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
        
        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);
		
		$metadata = $cmf->getAllMetadata();
        $metadata = MetadataFilter::filter($metadata, $input->getOption('filter'));

        if ($metadata) {
            $output->writeln(sprintf('Importing mapping information from "<info>%s</info>" entity manager', $emName));
			
			$filename = $path.'/'.ExtbaseExporter::PROJECT_FILE_NAME;
			
			if (!is_dir($dir = dirname($filename))) {
                mkdir($dir, 0777, true);
            }
			
			if (is_readable($filename) && !$input->getOption('force'))
			{
				$output->writeln(sprintf('File "<info>%s</info>" already existing, use --force to replace it.', $filename), 'ERROR');
            	$output->writeln('', 'ERROR');
            	return 1;
			}
			
			$exporter->setExtensionKey($extensionKey);
			$exporter->setMetadata($metadata);
			
			$json = $exporter->exportJson();
			foreach ($exporter->getLogs() as $log) {
				$output->writeln('--'.$log);
			} 
			
			file_put_contents($filename, $json);

            return 0;
        } else {
            $output->writeln('Database does not have any mapping information.', 'ERROR');
            $output->writeln('', 'ERROR');
            return 1;
        }

    }
}