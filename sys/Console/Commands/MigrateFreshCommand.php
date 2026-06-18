<?php

namespace Sys\Console\Commands;

use Sys\Console\CommandInterface;
use Sys\Database\Migrations\Migrator;
use Sys\Database\Seeding\DatabaseSeeder;

class MigrateFreshCommand implements CommandInterface
{
    public function __construct(
        private Migrator $migrator,
        private DatabaseSeeder $seeder
    ) {
    }

    public function name(): string
    {
        return 'migrate:fresh';
    }

    public function description(): string
    {
        return 'Drop all tables and run all migrations again.';
    }

    public function handle(array $arguments = []): int
    {
        $this->line('Refreshing database...');

        foreach ($this->migrator->fresh() as $message) {
            $this->line('[OK] ' . $message);
        }

        if (in_array('--seed', $arguments, true)) {
            $this->line('');
            $this->line('Running seeders...');

            foreach ($this->seeder->run() as $message) {
                $this->line('[OK] ' . $message);
            }
        }

        $this->line('Database refreshed successfully.');

        return 0;
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
