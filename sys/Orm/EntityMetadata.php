<?php

namespace Sys\Orm;

use InvalidArgumentException;

/**
 * Describes how an entity maps to a database table.
 *
 * This keeps mapping rules out of controllers and repositories. It is a simple
 * educational alternative to Entity Framework's fluent configuration.
 */
class EntityMetadata
{
    /**
     * @param class-string $entityClass
     * @param list<string> $fields
     * @param list<string> $fillable
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly string $table,
        public readonly string $primaryKey,
        public readonly array $fields,
        public readonly array $fillable,
        public readonly bool $usesUuidPrimaryKey = false
    ) {
    }

    public function assertFieldAllowed(string $field): void
    {
        if (!in_array($field, $this->fields, true)) {
            throw new InvalidArgumentException("Field {$field} is not mapped for {$this->entityClass}.");
        }
    }

    public function isFillable(string $field): bool
    {
        return in_array($field, $this->fillable, true);
    }
}
