<?php

namespace Sys\Orm;

use ReflectionClass;

/**
 * Converts database rows into entities and entities back into arrays.
 *
 * The mapper keeps entities clean: they do not need to know about SQL, PDO, or
 * table metadata.
 */
class EntityMapper
{
    public function hydrate(array $row, EntityMetadata $metadata): object
    {
        $entity = new $metadata->entityClass();

        foreach ($metadata->fields as $field) {
            if (!array_key_exists($field, $row)) {
                continue;
            }

            $entity->{$field} = $this->castValue($row[$field]);
        }

        return $entity;
    }

    public function extract(object $entity, EntityMetadata $metadata, bool $onlyFillable = true): array
    {
        $reflection = new ReflectionClass($entity);
        $data = [];

        foreach ($metadata->fields as $field) {
            if ($field === $metadata->primaryKey && $onlyFillable) {
                continue;
            }

            if ($onlyFillable && !$metadata->isFillable($field)) {
                continue;
            }

            if (!$reflection->hasProperty($field)) {
                continue;
            }

            $property = $reflection->getProperty($field);

            if (!$property->isInitialized($entity)) {
                continue;
            }

            $data[$field] = $property->getValue($entity);
        }

        return $data;
    }

    private function castValue(mixed $value): mixed
    {
        if (is_numeric($value) && (string) (int) $value === (string) $value) {
            return (int) $value;
        }

        return $value;
    }
}
