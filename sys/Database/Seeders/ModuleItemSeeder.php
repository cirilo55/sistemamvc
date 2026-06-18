<?php

namespace Sys\Database\Seeders;

use Sys\Database\Seeding\AbstractSeeder;

class ModuleItemSeeder extends AbstractSeeder
{
    public function run(): string
    {
        $items = [
            [
                'id' => '20000000-0000-4000-8000-000000000001',
                'itemName' => 'Usuarios',
                'idModulo' => '10000000-0000-4000-8000-000000000002',
                'archorValue' => 'users',
            ],
            [
                'id' => '20000000-0000-4000-8000-000000000002',
                'itemName' => 'Tarefas',
                'idModulo' => '10000000-0000-4000-8000-000000000001',
                'archorValue' => 'tarefas',
            ],
            [
                'id' => '20000000-0000-4000-8000-000000000003',
                'itemName' => 'Clientes',
                'idModulo' => '10000000-0000-4000-8000-000000000001',
                'archorValue' => 'clientes',
            ],
            [
                'id' => '20000000-0000-4000-8000-000000000004',
                'itemName' => 'Configuracoes Gerais',
                'idModulo' => '10000000-0000-4000-8000-000000000002',
                'archorValue' => 'config',
            ],
        ];

        foreach ($items as $item) {
            $this->save('moduleItem', $item, ['itemName', 'idModulo', 'archorValue']);
        }

        return 'Module items seeded with ORM.';
    }
}
