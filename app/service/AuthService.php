<?php

namespace App\Service;

use App\Repository\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function attempt(string $username, string $password): ?array
    {
        $user = $this->users->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }
}
