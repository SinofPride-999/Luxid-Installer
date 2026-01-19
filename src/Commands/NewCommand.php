<?php

declare(strict_types=1);

namespace Luxid\Installer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create a new Luxid application
 */
class NewCommand extends Command
{
    /**
     * Configure the command name, arguments, and description
     */
    protected function configure(): void
    {
        $this
            ->setName('new')
            ->setDescription('Create a new Luxid application')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the new Luxid application'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $output->writeln("<info>Creating Luxid application:</info> {$name}");

        return Command::SUCCESS;
    }
}

