<?php

namespace Sys;

use RuntimeException;

class Container
{
    private array $factories = [];
    private array $instances = [];

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (!array_key_exists($id, $this->factories)) {
            throw new RuntimeException("Service not registered: {$id}");
        }

        $this->instances[$id] = $this->factories[$id]($this);

        return $this->instances[$id];
    }
}
