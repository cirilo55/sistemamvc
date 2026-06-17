<?php

namespace App\Repository;

use App\Entity\User;
use Sys\Orm\EntityRepository;
use Sys\Orm\QueryResult;

class UserRepository
{
    public function __construct(private EntityRepository $users)
    {
    }

    public function find(int|string $id): ?User
    {
        return $this->users->find($id);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->users
            ->where('userName', '=', $username)
            ->first();
    }

    public function all(): array
    {
        return $this->users->all();
    }

    public function create(array $data): User
    {
        return $this->users->create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        return $this->users->update($id, $data);
    }

    public function delete(int|string $id): bool
    {
        return $this->users->delete($id);
    }

    public function count(): int
    {
        return $this->users->count();
    }

    public function paginate(int $page = 1, int $perPage = 15): QueryResult
    {
        return $this->users->paginate($page, $perPage);
    }

    public function query(): EntityRepository
    {
        return $this->users;
    }
}
