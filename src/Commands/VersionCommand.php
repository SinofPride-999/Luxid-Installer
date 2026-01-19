<?php

declare(strict_types=1);

namespace Luxid\Installer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Display Luxid Installer version
 */
class VersionCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('version')
            ->setDescription('Display the Luxid installer version');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Luxid Installer v0.1.0-dev</info>');

        return Command::SUCCESS;
    }
}
