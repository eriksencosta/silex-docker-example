<?php

namespace Example\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureEnvironment extends Command
{
    protected function configure()
    {
        $this
            ->setName('example:configure:environment')
            ->setDescription(
                'Register the whitelisted environment variables listed on the "environment.variables.whitelist" '.
                'parameter.'
            )
            ->setHelp(<<<EOT
Register the whitelisted environment variables listed on the "environment.variables.whitelist"
parameter to the environment file (defined by "environment.variables.file" parameter).

<info>php %command.full_name%</info>

To set additional variables, use the <info>--variable</info> option:

<info>php %command.full_name% --variable="EXAMPLE_DEBUG=true"</info>

You can set as many additional variables as needed:

<info>php %command.full_name% --variable="EXAMPLE_DEBUG=true" --variable="EXAMPLE_FOO=BAR"</info>

EOT
            )
            ->addOption(
                'variable',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'A key value variable to be set in the environment file. Format: KEY=VALUE.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $whitelistVariables = $app['environment.variables.whitelist'];
        $optionVariables = $input->getOption('variable');

        $contents = array();
        $contents[] = '# Created by '.$this->getName().' at '.date('Y-m-d H:i:s').'.';

        if (count($optionVariables)) {
            $contents[] = '## --variable option variables.';
        }

        foreach ($optionVariables as $value) {
            $contents[] = $value;
        }

        if (count($whitelistVariables)) {
            $contents[] = '## environment.variables.whitelist variables.';
        }

        foreach ($whitelistVariables as $variable => $alias) {
            $contents[] = sprintf('%s="%s"', $alias, getenv($variable));
        }

        $contents = implode(PHP_EOL, $contents).PHP_EOL;

        file_put_contents($app['environment.variables.file'], $contents);

        $output->writeln(sprintf(
            '<info>Created the environment file at <comment>%s</comment>',
            realpath($app['environment.variables.file']))
        );
    }
}
