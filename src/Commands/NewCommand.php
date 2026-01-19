<?php

declare(strict_types=1);

namespace Luxid\Installer\Commands;

use Luxid\Installer\Concerns\InteractsWithIO;
use Luxid\Installer\Support\Str;
use Luxid\Installer\Services\EnvironmentChecker;
use Luxid\Installer\Exceptions\InstallerException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create a new Luxid application
 */
class NewCommand extends Command
{
    use InteractsWithIO;

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
        $this->initIO($input, $output);

        $name = $input->getArgument('name');

        // Detect accidental spaces (e.g. luxid new blog app)
        if (preg_match('/\s/', $name)) {
            $this->error(
                'Project name must be a single word. Use hyphens instead: blog-app'
            );

            return Command::FAILURE;
        }

        if (! Str::isValidProjectName($name)) {
            $this->error(
                'Invalid project name. Use lowercase letters, numbers, hyphens or underscores only.'
            );

            return Command::FAILURE;
        }

        try {
            (new EnvironmentChecker())->check();
        } catch (InstallerException $e) {
            $this->error('Environment check failed: ' . $e->getMessage());

            return Command::FAILURE;
        }

        $this->info("Environment check passed OK.");
        $this->info("Creating Luxid application: {$name}");

        return Command::SUCCESS;
    }
}

