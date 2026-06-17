<?php

namespace Sys\Logging;

class LogEntry
{
    public function __construct(
        public readonly string $level,
        public readonly string $message,
        public readonly array $context = [],
        public readonly ?string $requestId = null,
        public readonly ?string $path = null,
        public readonly ?string $method = null,
        public readonly ?string $createdAt = null
    ) {
    }

    public function timestamp(): string
    {
        return $this->createdAt ?? date('Y-m-d H:i:s');
    }
}
