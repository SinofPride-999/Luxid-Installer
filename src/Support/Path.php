<?php

declare(strict_types=1);

namespace Luxid\Installer\Support;

class Path
{
    /**
     * Ensure that a directory exists
     *
     * @param string $path
     * @return void
     */
    public static function ensureDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}
