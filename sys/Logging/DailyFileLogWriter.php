<?php

namespace Sys\Logging;

class DailyFileLogWriter implements LogWriter
{
    public function __construct(
        private string $directory,
        private string $filenamePrefix = 'app'
    ) {
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0775, true);
        }
    }

    public function write(string $formattedEntry): bool
    {
        $file = $this->directory . DIRECTORY_SEPARATOR . $this->filenamePrefix . '-' . date('Y-m-d') . '.log';

        return @file_put_contents($file, $formattedEntry, FILE_APPEND | LOCK_EX) !== false;
    }
}
