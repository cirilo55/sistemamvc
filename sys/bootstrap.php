<?php

use Sys\Autoloader;
use Sys\Config;
use Sys\Container;
use Sys\Console\Commands\MigrateCommand;
use Sys\Console\Commands\MigrateFreshCommand;
use Sys\Console\Commands\MigrateRollbackCommand;
use Sys\Console\Commands\SeedCommand;
use Sys\Console\ConsoleApplication;
use Sys\Database;
use Sys\Database\Migrations\MigrationRepository;
use Sys\Database\Migrations\Migrator;
use Sys\Database\Seeding\DatabaseSeeder;
use Sys\Database\Seeders\ClientSeeder;
use Sys\Database\Seeders\MainConfigSeeder;
use Sys\Database\Seeders\ModuleItemSeeder;
use Sys\Database\Seeders\SystemModuleSeeder;
use Sys\Database\Seeders\UserSeeder;
use Sys\ErrorHandler;
use Sys\Logger;
use Sys\Orm\ConnectionInterface;
use Sys\Orm\EntityMapper;
use Sys\Orm\EntityRepository;
use App\Entity\UserMetadata;
use App\Repository\UserRepository;
use App\Service\AuthService;

define('BASE_PATH', dirname(__DIR__));
define('STORAGE_PATH', BASE_PATH . '/storage');
define('SYS_LOG_PATH', __DIR__ . '/logs');

require_once __DIR__ . '/Autoloader.php';

$autoloader = new Autoloader();
$autoloader->addNamespace('App\\Controller', BASE_PATH . '/app/controller');
$autoloader->addNamespace('App\\Entity', BASE_PATH . '/app/Entity');
$autoloader->addNamespace('App\\Model', BASE_PATH . '/app/model');
$autoloader->addNamespace('App\\Repository', BASE_PATH . '/app/repository');
$autoloader->addNamespace('App\\Service', BASE_PATH . '/app/service');
$autoloader->addNamespace('Sys\\Component', BASE_PATH . '/sys/component');
$autoloader->addNamespace('App', BASE_PATH . '/app');
$autoloader->addNamespace('Sys', BASE_PATH . '/sys');
$autoloader->register();

$config = Config::fromEnvironment(BASE_PATH . '/.env');
$logger = new Logger(SYS_LOG_PATH);
$container = new Container();

(new ErrorHandler($logger, $config->isDebug()))->register();

$container->set(Config::class, fn() => $config);
$container->set(Logger::class, fn() => $logger);
$container->set(Database::class, fn() => new Database());
$container->set(ConnectionInterface::class, fn(Container $container) => $container->get(Database::class));
$container->set(MigrationRepository::class, fn(Container $container) => new MigrationRepository($container->get(ConnectionInterface::class)));
$container->set(Migrator::class, fn(Container $container) => new Migrator(
    $container->get(ConnectionInterface::class),
    $container->get(MigrationRepository::class),
    BASE_PATH . '/database/migrations'
));
$container->set(DatabaseSeeder::class, fn(Container $container) => new DatabaseSeeder([
    new UserSeeder($container->get(ConnectionInterface::class)),
    new SystemModuleSeeder($container->get(ConnectionInterface::class)),
    new ModuleItemSeeder($container->get(ConnectionInterface::class)),
    new ClientSeeder($container->get(ConnectionInterface::class)),
    new MainConfigSeeder($container->get(ConnectionInterface::class)),
]));
$container->set(ConsoleApplication::class, fn(Container $container) => new ConsoleApplication([
    new MigrateCommand($container->get(Migrator::class), $container->get(DatabaseSeeder::class)),
    new MigrateFreshCommand($container->get(Migrator::class), $container->get(DatabaseSeeder::class)),
    new MigrateRollbackCommand($container->get(Migrator::class)),
    new SeedCommand($container->get(DatabaseSeeder::class)),
]));
$container->set(EntityMapper::class, fn() => new EntityMapper());
$container->set('orm.metadata.user', fn() => UserMetadata::make());
$container->set('orm.repository.user', fn(Container $container) => new EntityRepository(
    $container->get(ConnectionInterface::class),
    $container->get(EntityMapper::class),
    $container->get('orm.metadata.user')
));
$container->set(UserRepository::class, fn(Container $container) => new UserRepository($container->get('orm.repository.user')));
$container->set(AuthService::class, fn(Container $container) => new AuthService($container->get(UserRepository::class)));

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

setlocale(LC_TIME, 'pt_BR.utf8');

require_once __DIR__ . '/helpers.php';

return [
    'config' => $config,
    'container' => $container,
    'logger' => $logger,
];
