<?php

namespace Sys\Orm;

use InvalidArgumentException;
use PDO;

/**
 * Minimal fluent SQL builder for SELECT queries.
 *
 * It intentionally supports only the operations needed by the first ORM slice.
 * More SQL features should be added only when the application needs them.
 */
class QueryBuilder
{
    private array $wheres = [];
    private array $params = [];
    private array $orders = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private int $paramIndex = 0;

    public function __construct(
        private ConnectionInterface $connection,
        private EntityMetadata $metadata
    ) {
    }

    public function where(string $field, string $operator, mixed $value): self
    {
        $this->metadata->assertFieldAllowed($field);
        $operator = strtoupper($operator);
        $this->assertOperatorAllowed($operator);

        $parameter = ':p' . $this->paramIndex++;
        $this->wheres[] = "{$field} {$operator} {$parameter}";
        $this->params[$parameter] = $value;

        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->metadata->assertFieldAllowed($field);
        $direction = strtoupper($direction);

        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException('Order direction must be ASC or DESC.');
        }

        $this->orders[] = "{$field} {$direction}";

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = max(1, $limit);

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = max(0, $offset);

        return $this;
    }

    public function get(): array
    {
        $stmt = $this->connection->prepareAndExecute($this->toSql(), $this->params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): ?array
    {
        $clone = clone $this;
        $clone->limit(1);
        $rows = $clone->get();

        return $rows[0] ?? null;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) AS aggregate FROM {$this->metadata->table}" . $this->whereSql();
        $stmt = $this->connection->prepareAndExecute($sql, $this->params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['aggregate'] ?? 0);
    }

    public function paginate(int $page, int $perPage): QueryResult
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $total = $this->count();

        $clone = clone $this;
        $items = $clone
            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();

        return new QueryResult($items, $total, $page, $perPage);
    }

    public function toSql(): string
    {
        $sql = "SELECT * FROM {$this->metadata->table}" . $this->whereSql();

        if ($this->orders) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return $sql;
    }

    public function params(): array
    {
        return $this->params;
    }

    private function whereSql(): string
    {
        if (!$this->wheres) {
            return '';
        }

        return ' WHERE ' . implode(' AND ', $this->wheres);
    }

    private function assertOperatorAllowed(string $operator): void
    {
        if (!in_array($operator, ['=', '!=', '<>', '>', '>=', '<', '<=', 'LIKE'], true)) {
            throw new InvalidArgumentException("Operator {$operator} is not supported.");
        }
    }
}
