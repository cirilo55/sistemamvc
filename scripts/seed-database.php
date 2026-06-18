<?php

use Sys\Console\ConsoleApplication;

$app = require __DIR__ . '/../sys/bootstrap.php';

/** @var ConsoleApplication $console */
$console = $app['container']->get(ConsoleApplication::class);

$attempts = 20;

echo 'Starting database seed...' . PHP_EOL;

while ($attempts > 0) {
    try {
        exit($console->run(['mvc', 'db:seed']));
    } catch (PDOException $exception) {
        $attempts--;

        if ($attempts === 0) {
            echo '[ERROR] Database seed failed: ' . $exception->getMessage() . PHP_EOL;
            throw $exception;
        }

        echo 'Database is not ready yet. Retrying in 2 seconds...' . PHP_EOL;
        sleep(2);
    }
}

