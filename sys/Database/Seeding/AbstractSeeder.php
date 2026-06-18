<?php

namespace Sys\Database\Seeding;

use InvalidArgumentException;
use Sys\Orm\ConnectionInterface;
use Sys\Orm\EntityMapper;
use Sys\Orm\EntityMetadata;
use Sys\Orm\EntityRepository;

abstract class AbstractSeeder implements SeederInterface
{
    public function __construct(protected ConnectionInterface $connection)
    {
    }

    /**
     * Idempotent ORM save helper for seed data.
     */
    protected function save(string $table, array $row, array $updateColumns): object
    {
        if ($row === []) {
            throw new InvalidArgumentException('Seed row cannot be empty.');
        }

        if (!array_key_exists('id', $row)) {
            throw new InvalidArgumentException('Seed row must contain an id.');
        }

        $repository = $this->repository($table, array_keys($row));
        $existing = $repository->find($row['id']);

        if ($existing === null) {
            return $repository->create($row);
        }

        $updates = array_intersect_key($row, array_flip($updateColumns));
        $repository->update($row['id'], $updates);

        return $repository->findOrFail($row['id']);
    }

    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @param list<string> $fields
     */
    private function repository(string $table, array $fields): EntityRepository
    {
        return new EntityRepository(
            $this->connection,
            new EntityMapper(),
            new EntityMetadata(
                entityClass: \stdClass::class,
                table: $table,
                primaryKey: 'id',
                fields: $fields,
                fillable: array_values(array_filter($fields, fn(string $field) => $field !== 'id')),
                usesUuidPrimaryKey: true
            )
        );
    }
}
