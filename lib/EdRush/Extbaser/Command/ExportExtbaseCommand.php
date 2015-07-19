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

class ExportExtbaseCommand extends Command
{
    protected function configure()
    {
    	$this
	    	->setName('extbaser:export')
	    	->setDescription('Export an existing database schema to a TYPO3 Extbase Extension.')
	    	
	    	->addArgument('dbname', InputArgument::REQUIRED, 'The database name.')
	    	->addArgument('extension-key', InputArgument::REQUIRED, 'The target TYPO3 Extension key.')
	    	
	    	->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to export the extension to.')
	    	->addOption('filter', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match entities that should be mapped.')
	    	->addOption('force', 'f', InputOption::VALUE_NONE, 'Roundtrip existing project.')
	    	
	    	//db connection parameters
	    	->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'The database user.')
	    	->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'The database password.')
	    	->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The database host.')
	    	->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The database port.')
	    	->addOption('driver', null, InputOption::VALUE_OPTIONAL, 'The database driver.')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbName = $input->getArgument('dbname');
        $extensionKey = $input->getArgument('extension-key');

        //db defaults
        $dbUser = $input->getOption('user') ? $input->getOption('user') : 'root';
        $dbPassword = $input->getOption('password') ? $input->getOption('password') : null;
        $dbHost = $input->getOption('host') ? $input->getOption('host') : '127.0.0.1';
        $dbPort = $input->getOption('port') ? $input->getOption('port') : null;
        $dbDriver = $input->getOption('driver') ? $input->getOption('driver') : 'pdo_mysql';
        
        $exportPath = $input->getOption('path') ? $input->getOption('path') : '.';

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
        
        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);

        $exporter = new ExtbaseExporter($cmf);
        $exporter->setExtensionKey($extensionKey);
        $exporter->setPath($exportPath);
        $exporter->setOverwriteExistingFiles($input->getOption('force'));
        $exporter->setFilter($input->getOption('filter'));
        
        $output->writeln(sprintf('Exporting database schema "<info>%s</info>".', $dbName));
        
        $result = $exporter->exportJson();
        
        foreach ($exporter->getLogs() as $log) {
        	$output->writeln($log);
        }
        
        return $result? 0 : 1;
    }
}
