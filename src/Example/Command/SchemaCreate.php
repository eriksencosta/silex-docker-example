<?php

namespace Example\Command;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Schema\Schema;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SchemaCreate extends Command
{
    protected function configure()
    {
        $this
            ->setName('example:schema:create')
            ->setDescription('Create the database schema.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();
        $tables = $app['db']->getSchemaManager()->listTableNames();

        $schema = new Schema();

        if (!in_array('issues', $tables)) {
            $table = $schema->createTable('issues');
            $table->addColumn('id', Type::INTEGER, array('autoincrement' => true));
            $table->addColumn('title', Type::STRING, array('lenght' => 255));
            $table->addColumn('description', Type::TEXT);
            $table->addColumn('author', Type::STRING, array('lenght' => 255));
            $table->addIndex(array('id'));
        }

        $statements = $schema->toSql($app['db']->getDatabasePlatform());

        foreach ($statements as $statement) {
            $app['db']->executeUpdate($statement);
        }

        if (count($statements)) {
            $output->writeln('<info>Created the database schema.</info>');
        } else {
            $output->writeln('<info>The database schema was already created.</info>');
        }
    }
}
