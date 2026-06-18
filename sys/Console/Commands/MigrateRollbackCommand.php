<?php

namespace Sys\Console\Commands;

use Sys\Console\CommandInterface;
use Sys\Database\Migrations\Migrator;

class MigrateRollbackCommand implements CommandInterface
{
    public function __construct(private Migrator $migrator)
    {
    }

    public function name(): string
    {
        return 'migrate:rollback';
    }

    public function description(): string
    {
        return 'Rollback the last migration batch.';
    }

    public function handle(array $arguments = []): int
    {
        $this->line('Rolling back migrations...');

        foreach ($this->migrator->rollback() as $message) {
            $this->line('[OK] ' . $message);
        }

        $this->line('Done.');

        return 0;
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
