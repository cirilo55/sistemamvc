<?php

namespace Sys\Console\Commands;

use Sys\Console\CommandInterface;
use Sys\Database\Seeding\DatabaseSeeder;

class SeedCommand implements CommandInterface
{
    public function __construct(private DatabaseSeeder $seeder)
    {
    }

    public function name(): string
    {
        return 'db:seed';
    }

    public function description(): string
    {
        return 'Run database seeders.';
    }

    public function handle(array $arguments = []): int
    {
        $this->line('Running seeders...');

        foreach ($this->seeder->run() as $message) {
            $this->line('[OK] ' . $message);
        }

        $this->line('Database seed completed successfully.');

        return 0;
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
