<?php

namespace Sys\Database\Seeders;

use Sys\Database\Seeding\AbstractSeeder;

class MainConfigSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $this->upsert('mainConfig', [
            'id' => '40000000-0000-4000-8000-000000000001',
            'createdAt' => $this->now(),
            'updatedAt' => $this->now(),
        ], ['updatedAt']);
    }
}
