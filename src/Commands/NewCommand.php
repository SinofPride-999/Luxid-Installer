<?php

declare(strict_types=1);

namespace Luxid\Installer\Commands;

use Luxid\Installer\Concerns\InteractsWithIO;
use Luxid\Installer\Support\Str;
use Luxid\Installer\Services\EnvironmentChecker;
use Luxid\Installer\Exceptions\InstallerException;
use Luxid\Installer\Support\ProjectName;

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

        $rawName = $input->getArgument('name');
        $normalized = ProjectName::normalize($rawName);

        if (! ProjectName::isValid($normalized)) {
            $this->errorInvalidName($rawName, $normalized);
            return Command::FAILURE;
        }

        $name = $normalized;

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

    private function errorInvalidName(string $raw, string $normalized): void
    {
        $this->io->error("Invalid project name \"{$raw}\".");

        if ($raw !== $normalized && ProjectName::isValid($normalized)) {
            $this->io->writeln('');
            $this->io->writeln('Did you mean:');
            $this->io->writeln("  <info>{$normalized}</info>");
        }
    }

}

