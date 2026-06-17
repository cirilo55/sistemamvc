<?php

namespace Sys\Event;

class EventDispatcher
{
    private array $listeners = [];

    public function listen(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch(string $eventName, array $payload = []): void
    {
        foreach ($this->listeners[$eventName] ?? [] as $listener) {
            $listener($payload);
        }
    }
}
