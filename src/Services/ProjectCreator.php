<?php

declare(strict_types=1);

namespace Luxid\Installer\Services;

use Luxid\Installer\Support\Path;
use Luxid\Installer\Exceptions\InstallerException;

class ProjectCreator
{
    protected string $destination;

    public function __construct(string $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Create all required directories for a Luxid project
     */
    public function createDirectories(): void
    {
        $directories = [
            $this->destination . '/app/Actions',
            $this->destination . '/app/Entities',
            $this->destination . '/app/Middleware', // Sometimes empty
            $this->destination . '/config',
            $this->destination . '/routes',
            $this->destination . '/migrations',
            $this->destination . '/screens',
            $this->destination . '/web',
        ];

        foreach ($directories as $dir) {
            Path::ensureDirectory($dir);
        }
    }

    /**
     * Copy the Luxid framework skeleton to a new project folder
     *
     * @param string $source The path to luxid/framework skeleton
     * @param string $destination The path to the new project
     * @throws InstallerException
     */
    public function copySkeleton(string $source, string $destination): void
    {
        if (!is_dir($source)) {
            throw new InstallerException("Skeleton source folder does not exist: {$source}");
        }

        // Ensure destination exists
        Path::ensureDirectory($destination);

        // Recursively copy files
        $this->recursiveCopy($source, $destination);

        echo "Skeleton copied from {$source} → {$destination}" . PHP_EOL;
    }

    /**
     * Recursively copy files and directories
     *
     * @param string $src
     * @param string $dst
     */
    private function recursiveCopy(string $src, string $dst): void
    {
        $items = scandir($src);

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $sourcePath = $src . DIRECTORY_SEPARATOR . $item;
            $destPath   = $dst . DIRECTORY_SEPARATOR . $item;

            // Skip vendor folder
            if ($item === 'vendor') {
                continue;
            }

            if (is_dir($sourcePath)) {
                // Create directory if not exists
                Path::ensureDirectory($destPath);
                // Recursive copy
                $this->recursiveCopy($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
                // Preserve permissions
                chmod($destPath, fileperms($sourcePath) & 0777);
            }
        }
    }
}
