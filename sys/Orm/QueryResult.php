<?php

namespace Sys\Orm;

/**
 * Simple paginated result object.
 *
 * It mirrors the minimum data a controller/view needs without exposing SQL
 * details.
 */
class QueryResult
{
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $page,
        public readonly int $perPage
    ) {
    }

    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }
}
