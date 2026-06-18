<?php

namespace Sys\Database\Seeders;

use Sys\Database\Seeding\AbstractSeeder;

class ClientSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $clients = [
            [
                'id' => '30000000-0000-4000-8000-000000000001',
                'clientName' => 'empresa n1',
                'createdAt' => $this->now(),
                'updatedAt' => $this->now(),
            ],
            [
                'id' => '30000000-0000-4000-8000-000000000002',
                'clientName' => 'empresa n2',
                'createdAt' => $this->now(),
                'updatedAt' => $this->now(),
            ],
        ];

        foreach ($clients as $client) {
            $this->upsert('clients', $client, ['clientName', 'updatedAt']);
        }
    }
}
