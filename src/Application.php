<?php

declare(strict_types=1);

namespace Luxid\Installer;

use Symfony\Component\Console\Application as SymfonyApplication;
use Luxid\Installer\Commands\NewCommand;
use Luxid\Installer\Commands\VersionCommand;

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
        $this->displayBanner();
        $this->registerCommands();
    }

    /**
     * Show Luxid banner.
     */
    protected function displayBanner()
    {
        $banner = __DIR__ . '/../resources/banners/logo.txt';

        if (file_exists($banner)) {
            $this->getOutput()->writeln(file_get_contents($banner));
            $this->getOutput()->writeln('');
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
        return '0.1.0-dev';
    }
}
