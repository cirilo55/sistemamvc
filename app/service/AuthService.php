<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function attempt(string $username, string $password): ?User
    {
        $user = $this->users->findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }
}
