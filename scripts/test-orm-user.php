<?php

use App\Entity\User;
use App\Repository\UserRepository;

$app = require __DIR__ . '/../sys/bootstrap.php';

/** @var UserRepository $users */
$users = $app['container']->get(UserRepository::class);

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$username = 'orm_test_' . bin2hex(random_bytes(4));
$beforeCount = $users->count();

/** @var User $created */
$created = $users->create([
    'userName' => $username,
    'lastName' => 'Created',
    'password' => password_hash('1234', PASSWORD_BCRYPT),
    'userType' => 2,
]);

assertTrue($created->id !== null, 'create() should return an entity with id.');

$found = $users->find($created->id);
assertTrue($found instanceof User, 'find() should return a User entity.');
assertTrue($found->userName === $username, 'find() should hydrate the expected username.');

$first = $users->query()
    ->where('userName', '=', $username)
    ->firstOrFail();
assertTrue($first instanceof User, 'where()->firstOrFail() should return a User entity.');

$limited = $users->query()
    ->orderBy('id', 'DESC')
    ->limit(1)
    ->get();
assertTrue(count($limited) === 1, 'limit(1)->get() should return one entity.');

$page = $users->paginate(1, 2);
assertTrue($page->perPage === 2, 'paginate() should keep the requested page size.');
assertTrue($page->total >= $beforeCount + 1, 'paginate() should include the created entity in total count.');

$updated = $users->update($created->id, ['lastName' => 'Updated']);
assertTrue($updated === true, 'update() should return true for an existing entity.');

$updatedUser = $users->find($created->id);
assertTrue($updatedUser?->lastName === 'Updated', 'update() should persist new values.');

$deleted = $users->delete($created->id);
assertTrue($deleted === true, 'delete() should return true for an existing entity.');

$missing = $users->find($created->id);
assertTrue($missing === null, 'find() should return null after delete().');

echo 'ORM User checks passed.' . PHP_EOL;
