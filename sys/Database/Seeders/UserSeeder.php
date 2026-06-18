<?php

namespace Sys\Database\Seeders;

use Sys\Database\Seeding\AbstractSeeder;

class UserSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => '00000000-0000-4000-8000-000000000001',
                'userName' => 'Admin',
                'lastName' => 'Sistema',
                'userType' => 0,
                'password' => '$2y$10$BWisgENvxN0w7ruaHLS.ruRH84f7slYOADvWWVEHRq1kuc4cQ13NK',
                'createdAt' => $this->now(),
                'updatedAt' => $this->now(),
            ],
        ];

        foreach ($users as $user) {
            $this->upsert('users', $user, ['userName', 'lastName', 'userType', 'password', 'updatedAt']);
        }
    }
}
