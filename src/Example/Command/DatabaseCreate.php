<?php

namespace Example\Command;

use Doctrine\DBAL\DriverManager;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCreate extends Command
{
    protected function configure()
    {
        $this
            ->setName('example:database:create')
            ->setDescription('Create the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $params = $app['db']->getParams();
        $databaseName = $params['dbname'];
        unset($params['dbname']);

        $connection = DriverManager::getConnection($params);
        $schemaManager = $connection->getSchemaManager();
        $schemaManager->createDatabase($databaseName);

        $output->writeln(sprintf('<info>Created database <comment>%s</comment>.</info>', $databaseName));
    }
}
