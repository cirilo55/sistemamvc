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

    /**
     * @return list<string>
     */
    public function run(): array
    {
        $messages = [];

        foreach ($this->seeders as $seeder) {
            $messages[] = $seeder->run();
        }

        return $messages;
    }
}
