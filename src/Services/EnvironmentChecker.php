<?php

declare(strict_types=1);

namespace Luxid\Installer\Services;

use Luxid\Installer\Exceptions\InstallerException;

final class EnvironmentChecker
{
    /**
     * Run all environment checks.
     */
    public function check(): void
    {
        $this->checkPhpVersion();
        $this->checkComposer();
        $this->checkExtensions();
    }

    /**
     *  Ensure minimum PHP version is met.
     */
    protected function checkPhpVersion(): void
    {
        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            throw new InstallerException(
                'Luxid requires PHP version 8.0.0 or higher. Current version: ' . PHP_VERSION
            );
        }
    }

    /**
     *  Ensure Composer is available.
     */
    protected function checkComposer(): void
    {
        $result = shell_exec('composer --version');

        if ($result === null) {
            throw new InstallerException(
                'Composer is not installed or not available in the system PATH.'
            );
        }
    }

    /**
     * Ensure required PHP extensions exists.
     */
    protected function checkExtensions(): void
    {
        $requiredExtensions = [
            'pdo',
            'pdo_mysql',
        ];

        foreach ($requiredExtensions as $extension) {
            if (!extension_loaded($extension)) {
                throw new InstallerException(
                    "Required PHP extension missing: '{$extension}'"
                );
            }
        }
    }
}
