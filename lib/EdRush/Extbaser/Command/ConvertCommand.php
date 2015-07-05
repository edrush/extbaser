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

class ConvertCommand extends \Doctrine\DBAL\Tools\Console\Command\ImportCommand
{
    protected function configure()
    {
        $this
            ->setName('extbaser:convert')
            ->setDescription('Convert a database scheme to a TYPO3 Extbase Extension.')
            ->addArgument(
                'dbname',
                InputArgument::REQUIRED,
                'Who do you want to greet?'
            )
            ->addArgument(
                'user',
                InputArgument::REQUIRED,
                'Who do you want to greet?'
            )
            ->addArgument(
                'password',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
               'host',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
            ->addOption(
               'driver',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
            ->addOption(
               'port',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //defaults
        $driver = 'pdo_mysql';
        $host = '127.0.0.1';
        $port= null;
        
        if ($input->getOption('driver')) {
            $driver = $input->getOption('driver');
        }
        if ($input->getOption('host')) {
            $host = $input->getOption('host');
        }
        if ($input->getOption('port')) {
            $port = $input->getOption('driver');
        }
        
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => $input->getArgument('dbname'),
            'user' => $input->getArgument('user'),
            'password' => $input->getArgument('password'),
            'host' => $host,
            'driver' => $driver,
            'port' => $port
        );
        
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        $config = Setup::createAnnotationMetadataConfiguration(array('.'), false);
		$entityManager = EntityManager::create($connectionParams, $config);
        
        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($entityManager);
        $metadata = $cmf->getAllMetadata();
        
        if ($metadata) {
            $output->writeln(sprintf('Importing mapping information from "<info>%s</info>" entity manager', $emName));
            foreach ($metadata as $class) {
                /*$className = $class->name;
                $class->name = $bundle->getNamespace().'\\Entity\\'.$className;*/
            }
            return 0;
        } else {
            $output->writeln('Database does not have any mapping information.', 'ERROR');
            $output->writeln('', 'ERROR');
            return 1;
        }

    }
}