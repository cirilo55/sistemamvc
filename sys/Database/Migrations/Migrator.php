<?php

namespace Sys\Database\Migrations;

use PDO;
use RuntimeException;
use Sys\Orm\ConnectionInterface;

class Migrator
{
    public function __construct(
        private ConnectionInterface $connection,
        private MigrationRepository $repository,
        private string $migrationPath
    ) {
    }

    /**
     * @return list<string>
     */
    public function run(): array
    {
        $this->repository->ensureTableExists();

        $applied = $this->repository->applied();
        $batch = $this->repository->nextBatch();
        $messages = [];

        foreach ($this->migrations() as $migration) {
            if (in_array($migration->name(), $applied, true)) {
                continue;
            }

            $migration->up($this->connection);
            $this->repository->log($migration->name(), $batch);
            $messages[] = 'Migrated: ' . $migration->name();
        }

        if ($messages === []) {
            $messages[] = 'Nothing to migrate.';
        }

        return $messages;
    }

    /**
     * @return list<string>
     */
    public function rollback(): array
    {
        $this->repository->ensureTableExists();

        $migrationNames = $this->repository->lastBatchMigrations();

        if ($migrationNames === []) {
            return ['Nothing to rollback.'];
        }

        $migrations = [];

        foreach ($this->migrations() as $migration) {
            $migrations[$migration->name()] = $migration;
        }

        $messages = [];

        foreach ($migrationNames as $migrationName) {
            if (!array_key_exists($migrationName, $migrations)) {
                throw new RuntimeException("Migration file not found for rollback: {$migrationName}");
            }

            $migrations[$migrationName]->down($this->connection);
            $this->repository->delete($migrationName);
            $messages[] = 'Rolled back: ' . $migrationName;
        }

        return $messages;
    }

    /**
     * Drop all tables and run every migration again.
     *
     * @return list<string>
     */
    public function fresh(): array
    {
        $messages = $this->dropAllTables();

        return array_merge($messages, $this->run());
    }

    /**
     * @return list<MigrationInterface>
     */
    private function migrations(): array
    {
        $files = glob(rtrim($this->migrationPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php') ?: [];
        sort($files);

        $migrations = [];

        foreach ($files as $file) {
            $migration = require $file;

            if (!$migration instanceof MigrationInterface) {
                throw new RuntimeException("Migration file must return a MigrationInterface: {$file}");
            }

            $migrations[] = $migration;
        }

        return $migrations;
    }

    /**
     * @return list<string>
     */
    private function dropAllTables(): array
    {
        $rows = $this->connection
            ->prepareAndExecute(
                "SELECT TABLE_NAME
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_TYPE = 'BASE TABLE'"
            )
            ->fetchAll(PDO::FETCH_ASSOC);

        if ($rows === []) {
            return ['No tables to drop.'];
        }

        $this->connection->prepareAndExecute('SET FOREIGN_KEY_CHECKS=0');

        foreach ($rows as $row) {
            $this->connection->prepareAndExecute('DROP TABLE IF EXISTS ' . $this->quoteIdentifier($row['TABLE_NAME']));
        }

        $this->connection->prepareAndExecute('SET FOREIGN_KEY_CHECKS=1');

        return ['Dropped all tables.'];
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
