<?php

namespace Sys\Database\Seeders;

use Sys\Database\Seeding\AbstractSeeder;

class SystemModuleSeeder extends AbstractSeeder
{
    public function run(): string
    {
        $modules = [
            ['id' => '10000000-0000-4000-8000-000000000001', 'moduleName' => 'Operacional', 'order' => 1],
            ['id' => '10000000-0000-4000-8000-000000000002', 'moduleName' => 'Configuracoes', 'order' => 9],
            ['id' => '10000000-0000-4000-8000-000000000003', 'moduleName' => 'Dashboards', 'order' => 2],
        ];

        foreach ($modules as $module) {
            $this->save('systemModule', $module, ['moduleName', 'order']);
        }

        return 'System modules seeded with ORM.';
    }
}
