<?php

namespace Sys\Database\Seeders;

use Sys\Database\Seeding\AbstractSeeder;

class MainConfigSeeder extends AbstractSeeder
{
    public function run(): string
    {
        $this->save('mainConfig', [
            'id' => '40000000-0000-4000-8000-000000000001',
            'createdAt' => $this->now(),
            'updatedAt' => $this->now(),
        ], ['updatedAt']);

        return 'Main config seeded with ORM.';
    }
}
