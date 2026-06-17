<?php
use App\Controller\AuthController;
use App\Service\AuthService;
use Sys\Router;

$router = new Router();
$container = $app['container'] ?? null;

$router->addRoute('GET', '/', function () {

});

include('./routes/userRoutes.php');
include('./routes/taskRoutes.php');
include('./routes/mainConfigRoutes.php');
include('./routes/clientsController.php');

$router->addRoute('GET', '/login', function () use ($container) {
    (new AuthController($container->get(AuthService::class)))->showLogin();
});

$router->addRoute('POST', '/login', function () use ($container) {
    (new AuthController($container->get(AuthService::class)))->login();
});

$router->addRoute('GET', '/SignOut', function () use ($container) {
    (new AuthController($container->get(AuthService::class)))->logout();
});

$router->handleRequest();
