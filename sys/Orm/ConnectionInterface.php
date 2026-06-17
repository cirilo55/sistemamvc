<?php

namespace Sys\Orm;

use PDOStatement;

/**
 * Small database contract used by the ORM.
 *
 * The ORM depends on this interface instead of a concrete PDO wrapper so the
 * persistence layer can later be tested or swapped without changing repositories.
 */
interface ConnectionInterface
{
    public function prepareAndExecute(string $query, array $params = []): PDOStatement;

    public function lastInsertId(): string;
}
