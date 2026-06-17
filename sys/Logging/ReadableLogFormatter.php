<?php

namespace Sys\Logging;

class ReadableLogFormatter implements LogFormatter
{
    public function format(LogEntry $entry): string
    {
        $lines = [
            str_repeat('-', 80),
            '[' . $entry->timestamp() . '] ' . strtoupper($entry->level) . ' - ' . $entry->message,
        ];

        if ($entry->requestId) {
            $lines[] = 'Request: ' . $entry->requestId;
        }

        if ($entry->method || $entry->path) {
            $lines[] = 'Route: ' . trim(($entry->method ?? '') . ' ' . ($entry->path ?? ''));
        }

        foreach ($entry->context as $key => $value) {
            $lines[] = ucfirst((string) $key) . ': ' . $this->stringify($value);
        }

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }

    private function stringify(mixed $value): string
    {
        if (is_scalar($value) || $value === null) {
            return (string) $value;
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
