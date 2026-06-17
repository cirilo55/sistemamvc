<?php

namespace Sys;

use Throwable;

class ErrorHandler
{
    public function __construct(
        private Logger $logger,
        private bool $debug = false
    ) {
    }

    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        $this->logger->warning($message, [
            'severity' => $this->severityName($severity),
            'file' => $file,
            'line' => $line,
            'status' => 'handled by PHP error handler',
        ]);

        return true;
    }

    public function handleException(Throwable $exception): void
    {
        $this->logger->critical($exception->getMessage(), [
            'exception' => $exception::class,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'status' => '500 Internal Server Error',
            'trace' => $exception->getTraceAsString(),
        ]);

        http_response_code(500);

        if ($this->debug) {
            echo '<pre>' . htmlspecialchars((string) $exception, ENT_QUOTES, 'UTF-8') . '</pre>';
            return;
        }

        echo 'Erro interno da aplicação.';
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();

        if (!$error || !in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            return;
        }

        $this->logger->critical($error['message'], [
            'severity' => $this->severityName($error['type']),
            'file' => $error['file'],
            'line' => $error['line'],
            'status' => 'fatal shutdown error',
        ]);
    }

    private function severityName(int $severity): string
    {
        return match ($severity) {
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            default => 'E_UNKNOWN',
        };
    }
}
