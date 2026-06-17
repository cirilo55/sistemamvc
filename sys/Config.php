<?php

namespace Sys;

class Config
{
    private array $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public static function fromEnvironment(string $envFile): self
    {
        $fileValues = is_file($envFile) ? parse_ini_file($envFile) : [];
        $fileValues = is_array($fileValues) ? $fileValues : [];

        $serverEnv = getenv();
        $serverEnv = is_array($serverEnv) ? $serverEnv : [];

        return new self(array_merge($fileValues, $_ENV, $serverEnv));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->values[$key] ?? null;

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }

    public function isDebug(): bool
    {
        return filter_var($this->get('APP_DEBUG', false), FILTER_VALIDATE_BOOL);
    }
}
