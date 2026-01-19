<?php

declare(strict_types=1);

namespace Luxid\Installer\Support;

final class Str
{
    /**
     * Validate a Luxid project name
     */
    public static function isValidProjectName(string $name): bool
    {
        return preg_match('/^[a-z0-9]+([_-][a-z0-9]+)*$/', $name) === 1;
    }
}
