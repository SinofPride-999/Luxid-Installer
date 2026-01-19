<?php

declare(strict_types=1);

namespace Luxid\Installer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create a new Luxid application
 */
class NewCommand extends Command
{
    protected static $defaultName = 'new';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Luxid project creation coming soon...</comment>');

        return Command::SUCCESS;
    }
}

