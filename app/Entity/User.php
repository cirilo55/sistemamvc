<?php

namespace App\Entity;

/**
 * User entity.
 *
 * Entities represent application data with strong typing. They do not know how
 * to query or save themselves; repositories own persistence.
 */
class User
{
    public ?string $id = null;
    public string $userName;
    public ?string $lastName = null;
    public ?string $userEmail = null;
    public string $password;
    public int $userType = 0;
    public ?string $imagePath = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
}
