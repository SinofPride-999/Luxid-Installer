<?php

declare(strict_types=1);

namespace Luxid\Installer;

use Luxid\Installer\Commands\NewCommand;
use Luxid\Installer\Commands\VersionCommand;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\InstalledVersions;

/**
 * Main Luxid Installer Console Application
 */
class Application extends SymfonyApplication
{
    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'Luxid Installer',
            $this->getInstallerVersion()
        );

        // Register commands
        $this->registerCommands();
    }

    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        $this->displayBanner($output);

        return parent::doRun($input, $output);
    }

    /**
     * Show Luxid banner.
     */
    protected function displayBanner(OutputInterface $output)
    {
        $banner = __DIR__ . '/../resources/banners/logo.txt';

        if (file_exists($banner)) {
            $output->writeln(file_get_contents(filename: $banner));
            $output->writeln('');
        }
    }

    /**
     * Registers the available commands for the installer.
     */
    private function registerCommands(): void
    {
        $this->add(new NewCommand());
        $this->add(new VersionCommand());
    }

    /**
     * Resolve installer version.
     *
     * @return string
     */
    private function getInstallerVersion(): string
    {
        return InstalledVersions::getPrettyVersion('luxid/installer')
             ?? 'dev-main';
    }
}
