<?php

namespace Sys\Database\Migrations;

use PDO;
use Sys\Orm\ConnectionInterface;

class MigrationRepository
{
    public function __construct(private ConnectionInterface $connection)
    {
    }

    public function ensureTableExists(): void
    {
        $this->connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                batch INT UNSIGNED NOT NULL,
                createdAt DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    /**
     * @return list<string>
     */
    public function applied(): array
    {
        $this->ensureTableExists();

        $rows = $this->connection
            ->prepareAndExecute('SELECT migration FROM migrations ORDER BY id ASC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $row['migration'], $rows);
    }

    public function nextBatch(): int
    {
        $this->ensureTableExists();

        return $this->lastBatch() + 1;
    }

    public function lastBatch(): int
    {
        $this->ensureTableExists();

        $row = $this->connection
            ->prepareAndExecute('SELECT MAX(batch) AS batch FROM migrations')
            ->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['batch'] ?? 0);
    }

    /**
     * @return list<string>
     */
    public function lastBatchMigrations(): array
    {
        $batch = $this->lastBatch();

        if ($batch === 0) {
            return [];
        }

        $rows = $this->connection
            ->prepareAndExecute(
                'SELECT migration FROM migrations WHERE batch = :batch ORDER BY id DESC',
                [':batch' => $batch]
            )
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $row['migration'], $rows);
    }

    public function log(string $migration, int $batch): void
    {
        $this->connection->prepareAndExecute(
            'INSERT INTO migrations (migration, batch, createdAt) VALUES (:migration, :batch, :createdAt)',
            [
                ':migration' => $migration,
                ':batch' => $batch,
                ':createdAt' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public function delete(string $migration): void
    {
        $this->connection->prepareAndExecute(
            'DELETE FROM migrations WHERE migration = :migration',
            [':migration' => $migration]
        );
    }
}
