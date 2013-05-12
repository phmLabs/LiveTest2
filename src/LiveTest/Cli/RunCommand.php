<?php

namespace LiveTest\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use LiveTest\Event\Dispatcher;
use LiveTest\Cli\Runner;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('testsuite', InputArgument::REQUIRED, 'the testsuite to run'),
                new InputArgument('config', InputArgument::REQUIRED, 'the config to use')
            ))
            ->setDescription('runs the testsuite')
            ->setHelp(<<<EOT
The <info>run</info> command runs the entire test-suite
EOT
            )
            ->setName('run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dispatcher = new Dispatcher($output);

        $runner = new Runner($input->getArguments(), $dispatcher);

        if ($runner->isRunAllowed()) {
            $runner->run();
        }
    }

}