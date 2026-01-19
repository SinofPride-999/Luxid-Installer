<?php

declare(strict_types=1);

namespace Luxid\Installer\Commands;

use Symfony\Component\Console\Command\Command;

/**
 * Custom help command placeholder
 */
class HelpCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('help')
            ->setDescription('Display help for Luxid commands');
    }
}
