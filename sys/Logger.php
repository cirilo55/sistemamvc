<?php

namespace Sys;

use Sys\Logging\DailyFileLogWriter;
use Sys\Logging\LogEntry;
use Sys\Logging\LogFormatter;
use Sys\Logging\LogLevel;
use Sys\Logging\LogWriter;
use Sys\Logging\ReadableLogFormatter;

class Logger
{
    public function __construct(
        private string $logDirectory,
        private ?LogFormatter $formatter = null,
        private ?LogWriter $writer = null
    ) {
        $this->formatter = $this->formatter ?? new ReadableLogFormatter();
        $this->writer = $this->writer ?? new DailyFileLogWriter($this->logDirectory);
    }

    public function info(string $message, array $context = []): void
    {
        $this->write(LogLevel::INFO, $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->write(LogLevel::WARNING, $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->write(LogLevel::ERROR, $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->write(LogLevel::CRITICAL, $message, $context);
    }

    private function write(string $level, string $message, array $context): void
    {
        $entry = new LogEntry(
            level: $level,
            message: $message,
            context: $context,
            requestId: $this->requestId(),
            path: $_SERVER['REQUEST_URI'] ?? null,
            method: $_SERVER['REQUEST_METHOD'] ?? null
        );

        $written = $this->writer->write($this->formatter->format($entry));

        if ($written === false) {
            error_log("[{$level}] {$message}");
        }
    }

    private function requestId(): string
    {
        if (!isset($_SERVER['APP_REQUEST_ID'])) {
            $_SERVER['APP_REQUEST_ID'] = bin2hex(random_bytes(8));
        }

        return $_SERVER['APP_REQUEST_ID'];
    }
}
