<?php

use Sys\Database\Seeding\DatabaseSeeder;

$app = require __DIR__ . '/../sys/bootstrap.php';

/** @var DatabaseSeeder $seeder */
$seeder = $app['container']->get(DatabaseSeeder::class);

$attempts = 20;

while ($attempts > 0) {
    try {
        $seeder->run();
        echo 'Database seed applied.' . PHP_EOL;
        exit(0);
    } catch (PDOException $exception) {
        $attempts--;

        if ($attempts === 0) {
            throw $exception;
        }

        sleep(2);
    }
}

