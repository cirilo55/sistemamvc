<?php

namespace App\Entity;

use Sys\Orm\EntityMetadata;

/**
 * Mapping rules for the users table.
 *
 * Keeping metadata outside the entity preserves the entity as a plain PHP
 * object, similar to keeping EF configuration separate from domain classes.
 */
class UserMetadata
{
    public static function make(): EntityMetadata
    {
        return new EntityMetadata(
            entityClass: User::class,
            table: 'users',
            primaryKey: 'id',
            fields: [
                'id',
                'userName',
                'lastName',
                'userEmail',
                'password',
                'userType',
                'imagePath',
                'createdAt',
                'updatedAt',
            ],
            fillable: [
                'userName',
                'lastName',
                'userEmail',
                'password',
                'userType',
                'imagePath',
                'createdAt',
                'updatedAt',
            ]
        );
    }
}
