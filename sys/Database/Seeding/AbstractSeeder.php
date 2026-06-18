<?php

namespace Sys\Database\Seeding;

use InvalidArgumentException;
use Sys\Orm\ConnectionInterface;

abstract class AbstractSeeder implements SeederInterface
{
    public function __construct(protected ConnectionInterface $connection)
    {
    }

    /**
     * Idempotent insert/update helper for seed data.
     *
     * Seeds should be safe to run more than once. MySQL's ON DUPLICATE KEY keeps
     * this simple while the framework is still in the learning stage.
     */
    protected function upsert(string $table, array $row, array $updateColumns): void
    {
        if ($row === []) {
            throw new InvalidArgumentException('Seed row cannot be empty.');
        }

        $columns = array_keys($row);
        $placeholders = array_map(fn(string $column) => ':' . $column, $columns);
        $updates = array_map(fn(string $column) => $this->quoteIdentifier($column) . ' = VALUES(' . $this->quoteIdentifier($column) . ')', $updateColumns);
        $params = [];

        foreach ($row as $column => $value) {
            $params[':' . $column] = $value;
        }

        $quotedColumns = array_map(fn(string $column) => $this->quoteIdentifier($column), $columns);
        $sql = 'INSERT INTO ' . $this->quoteIdentifier($table) . ' (' . implode(', ', $quotedColumns) . ') VALUES (' . implode(', ', $placeholders) . ')';

        if ($updates !== []) {
            $sql .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $updates);
        }

        $this->connection->prepareAndExecute($sql, $params);
    }

    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
