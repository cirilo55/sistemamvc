<?php

namespace Sys\Orm;

/**
 * Generic repository for one mapped entity.
 *
 * This class is the educational equivalent of a small DbSet: it exposes common
 * ORM operations while hiding SQL from controllers and services.
 */
class EntityRepository
{
    private QueryBuilder $query;

    public function __construct(
        private ConnectionInterface $connection,
        private EntityMapper $mapper,
        private EntityMetadata $metadata
    ) {
        $this->query = $this->newQuery();
    }

    public function find(int|string $id): ?object
    {
        $row = $this->newQuery()
            ->where($this->metadata->primaryKey, '=', $id)
            ->limit(1)
            ->first();

        return $row ? $this->mapper->hydrate($row, $this->metadata) : null;
    }

    public function findOrFail(int|string $id): object
    {
        return $this->find($id) ?? throw EntityNotFoundException::forId($this->metadata->entityClass, $id);
    }

    public function all(): array
    {
        return $this->mapRows($this->newQuery()->get());
    }

    public function create(array $data): object
    {
        if ($this->metadata->usesUuidPrimaryKey && empty($data[$this->metadata->primaryKey])) {
            $data[$this->metadata->primaryKey] = Uuid::v4();
        }

        $data = $this->filterFillable($data);

        if (!$data) {
            throw new \InvalidArgumentException('No fillable data provided for create.');
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn(string $column) => ':' . $column, $columns);
        $quotedColumns = array_map(fn(string $column) => $this->quoteIdentifier($column), $columns);
        $params = [];

        foreach ($data as $column => $value) {
            $params[':' . $column] = $value;
        }

        $sql = 'INSERT INTO ' . $this->quoteIdentifier($this->metadata->table) . ' (' . implode(', ', $quotedColumns) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $this->connection->prepareAndExecute($sql, $params);

        return $this->findOrFail($data[$this->metadata->primaryKey] ?? $this->connection->lastInsertId());
    }

    public function update(int|string $id, array $data): bool
    {
        $data = $this->filterFillable($data);

        if (!$data) {
            return false;
        }

        $sets = [];
        $params = [':id' => $id];

        foreach ($data as $column => $value) {
            $placeholder = ':' . $column;
            $sets[] = $this->quoteIdentifier($column) . " = {$placeholder}";
            $params[$placeholder] = $value;
        }

        $sql = 'UPDATE ' . $this->quoteIdentifier($this->metadata->table) . ' SET ' . implode(', ', $sets) . ' WHERE ' . $this->quoteIdentifier($this->metadata->primaryKey) . ' = :id';

        return $this->connection->prepareAndExecute($sql, $params)->rowCount() > 0;
    }

    public function delete(int|string $id): bool
    {
        $sql = 'DELETE FROM ' . $this->quoteIdentifier($this->metadata->table) . ' WHERE ' . $this->quoteIdentifier($this->metadata->primaryKey) . ' = :id';

        return $this->connection->prepareAndExecute($sql, [':id' => $id])->rowCount() > 0;
    }

    public function where(string $field, string $operator, mixed $value): self
    {
        $clone = clone $this;
        $clone->query->where($field, $operator, $value);

        return $clone;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $clone = clone $this;
        $clone->query->orderBy($field, $direction);

        return $clone;
    }

    public function limit(int $limit): self
    {
        $clone = clone $this;
        $clone->query->limit($limit);

        return $clone;
    }

    public function first(): ?object
    {
        $row = $this->query->first();

        return $row ? $this->mapper->hydrate($row, $this->metadata) : null;
    }

    public function firstOrFail(): object
    {
        return $this->first() ?? throw EntityNotFoundException::forQuery($this->metadata->entityClass);
    }

    public function get(): array
    {
        return $this->mapRows($this->query->get());
    }

    public function count(): int
    {
        return $this->query->count();
    }

    public function paginate(int $page = 1, int $perPage = 15): QueryResult
    {
        $result = $this->query->paginate($page, $perPage);

        return new QueryResult(
            $this->mapRows($result->items),
            $result->total,
            $result->page,
            $result->perPage
        );
    }

    public function belongsTo(object $entity, self $relatedRepository, string $foreignKey, string $ownerKey = 'id'): ?object
    {
        $foreignId = $entity->{$foreignKey} ?? null;

        if ($foreignId === null) {
            return null;
        }

        return $relatedRepository->where($ownerKey, '=', $foreignId)->first();
    }

    public function hasOne(object $entity, self $relatedRepository, string $foreignKey, string $localKey = 'id'): ?object
    {
        $localId = $entity->{$localKey} ?? null;

        if ($localId === null) {
            return null;
        }

        return $relatedRepository->where($foreignKey, '=', $localId)->first();
    }

    public function hasMany(object $entity, self $relatedRepository, string $foreignKey, string $localKey = 'id'): array
    {
        $localId = $entity->{$localKey} ?? null;

        if ($localId === null) {
            return [];
        }

        return $relatedRepository->where($foreignKey, '=', $localId)->get();
    }

    public function __clone()
    {
        $this->query = clone $this->query;
    }

    private function newQuery(): QueryBuilder
    {
        return new QueryBuilder($this->connection, $this->metadata);
    }

    private function mapRows(array $rows): array
    {
        return array_map(fn(array $row) => $this->mapper->hydrate($row, $this->metadata), $rows);
    }

    private function filterFillable(array $data): array
    {
        $filtered = [];

        foreach ($data as $field => $value) {
            $this->metadata->assertFieldAllowed($field);

            if ($field === $this->metadata->primaryKey && $this->metadata->usesUuidPrimaryKey) {
                $filtered[$field] = $value;
                continue;
            }

            if ($this->metadata->isFillable($field)) {
                $filtered[$field] = $value;
            }
        }

        return $filtered;
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
