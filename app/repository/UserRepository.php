<?php

namespace App\Repository;

use PDO;
use Sys\Database;

class UserRepository
{
    public function __construct(private Database $database)
    {
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->database->prepareAndExecute(
            'SELECT * FROM users WHERE userName = :username',
            [':username' => $username]
        );

        $user = $stmt?->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
