<?php

use Sys\Autoloader;
use Sys\Config;
use Sys\Container;
use Sys\Database;
use Sys\ErrorHandler;
use Sys\Logger;
use App\Repository\UserRepository;
use App\Service\AuthService;

define('BASE_PATH', dirname(__DIR__));
define('STORAGE_PATH', BASE_PATH . '/storage');
define('SYS_LOG_PATH', __DIR__ . '/logs');

require_once __DIR__ . '/Autoloader.php';

$autoloader = new Autoloader();
$autoloader->addNamespace('App\\Controller', BASE_PATH . '/app/controller');
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
$container->set(UserRepository::class, fn(Container $container) => new UserRepository($container->get(Database::class)));
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
