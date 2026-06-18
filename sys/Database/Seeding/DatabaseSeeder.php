<?php

namespace Sys\Database\Seeding;

class DatabaseSeeder
{
    /**
     * @param list<SeederInterface> $seeders
     */
    public function __construct(private array $seeders)
    {
    }

    public function run(): void
    {
        foreach ($this->seeders as $seeder) {
            $seeder->run();
        }
    }
}
