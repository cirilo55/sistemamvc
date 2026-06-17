<?php

namespace Sys\Http;

class Request
{
    public function __construct(
        private string $method,
        private string $uri,
        private array $query,
        private array $post,
        private array $files,
        private array $server,
        private array &$session
    ) {
    }

    public static function capture(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_SESSION
        );
    }

    public function method(): string
    {
        return strtoupper($this->method);
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    public function session(string $key, mixed $default = null): mixed
    {
        return $this->session[$key] ?? $default;
    }

    public function isAjax(): bool
    {
        return ($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
