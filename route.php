<?php
use Sys\Router;

$router = new Router();

$router->addRoute('GET', '/', function () {

});

include('./routes/userRoutes.php');
include('./routes/taskRoutes.php');
include('./routes/mainConfigRoutes.php');
include('./routes/clientsController.php');

$router->addRoute('GET', '/login', function () {
    include('login.php');
});

$router->addRoute('GET', '/SignOut', function () {
    session_destroy();
    header("Location: /login");

});

$router->handleRequest();
