<?php

namespace Luxid\Installer\Support;

class ProjectName
{
    public static function normalize(string $name): string
    {
        $name = trim($name);

        // Convert CamelCase / PascalCase to kebab-case
        $name = preg_replace('/([a-z])([A-Z])/', '$1-$2', $name);

        // Convert underscores to hyphens
        $name = str_replace('_', '-', $name);

        // Lowercase
        return strtolower($name);
    }

    public static function isValid(string $name): bool
    {
        return (bool) preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $name);
    }
}
