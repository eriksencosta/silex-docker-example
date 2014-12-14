<?php

namespace Example\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixtureLoad extends Command
{
    protected function configure()
    {
        $this
            ->setName('example:fixtures:load')
            ->setDescription('Load fixture data into the database.')
            ->addOption('truncate', null, InputOption::VALUE_NONE, 'Truncate the tables before loading data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $issues = array();

        $issues[] = array(
            'title' => 'The API returns 200 instead of 201 for POST /issues',
            'description' => 'It would be better to return 201 Created. See: '.
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
            'author' => '@foobar',
        );

        $issues[] = array(
            'title' => 'The author field is not being validated',
            'description' => 'I sent a POST request to /issues without a valid author name and it was accepted.',
            'author' => '@foobar',
        );

        $issues[] = array(
            'title' => 'Oasis must reunite',
            'description' => 'Yeah, for sure. For half a billion pounds or not. See: '.
                'http://www.nme.com/news/noel-gallagher/76984',
            'author' => '@eriksencosta',
        );

        $app = $this->getSilexApplication();
        $connection = $app['db'];

        if ($input->getOption('truncate')) {
            $connection->executeQuery('TRUNCATE issues');
            $output->writeln('<info>Truncated the table <comment>issues</comment>.');
        }

        foreach ($issues as $issue) {
            $connection->insert('issues', $issue);

            $output->writeln(
                sprintf(
                    '<info>Created issue <comment>%s</comment> on the on the <comment>issues</comment> table.</info>',
                    $issue['title']
                )
            );
        }
    }
}
