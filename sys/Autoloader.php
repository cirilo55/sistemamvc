<?php

namespace Sys;

class Autoloader
{
    private array $prefixes = [];

    public function addNamespace(string $prefix, string $baseDirectory): void
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDirectory = rtrim($baseDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $this->prefixes[$prefix] = $baseDirectory;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    private function loadClass(string $class): void
    {
        foreach ($this->prefixes as $prefix => $baseDirectory) {
            if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
                continue;
            }

            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDirectory . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (is_file($file)) {
                require $file;
                return;
            }

            $legacyFile = $baseDirectory . str_replace('\\', DIRECTORY_SEPARATOR, lcfirst($relativeClass)) . '.php';

            if (is_file($legacyFile)) {
                require $legacyFile;
                return;
            }
        }
    }
}
