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
            $this->destination . '/app/Middleware',
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

        Path::ensureDirectory($destination);
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

        if ($items === false) return;

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $sourcePath = $src . DIRECTORY_SEPARATOR . $item;
            $destPath   = $dst . DIRECTORY_SEPARATOR . $item;

            // Skip vendor folder
            if ($item === 'vendor') continue;

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

    /**
     * Copy .env.example to .env in the new project
     *
     * @param string $skeletonPath Path to luxid/framework
     */
    public function generateEnv(string $skeletonPath): void
    {
        $sourceEnv = $skeletonPath . '/.env.example';
        $destEnv   = $this->destination . '/.env';

        if (!file_exists($sourceEnv)) {
            throw new InstallerException(".env.example not found in skeleton: {$sourceEnv}");
        }

        // Copy the file
        copy($sourceEnv, $destEnv);

        // Optionally: set MySQL defaults (from .env.example)
        $contents = file_get_contents($destEnv);

        // Replace placeholder values (if needed)
        $contents = preg_replace('/DB_DSN=.*$/m', 'DB_DSN=mysql:host=127.0.0.1;port=3306;dbname=' . basename($this->destination), $contents);
        $contents = preg_replace('/DB_USER=.*$/m', 'DB_USER=root', $contents);
        $contents = preg_replace('/DB_PASSWORD=.*$/m', 'DB_PASSWORD=', $contents);

        file_put_contents($destEnv, $contents);

        echo ".env file generated with MySQL defaults." . PHP_EOL;
    }

}
