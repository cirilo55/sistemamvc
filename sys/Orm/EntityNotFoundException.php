<?php

namespace Sys\Orm;

use RuntimeException;

class EntityNotFoundException extends RuntimeException
{
    public static function forId(string $entityClass, int|string $id): self
    {
        return new self("Entity {$entityClass} with id {$id} was not found.");
    }

    public static function forQuery(string $entityClass): self
    {
        return new self("No {$entityClass} entity matched the query.");
    }
}
