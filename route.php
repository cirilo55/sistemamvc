<?php
use Sys\Router;
use App\Controller\UserController;
use App\Controller\MainConfigController;

$router = new Router();

$router->addRoute('GET', '/', function () {

});

$router->addRoute('GET', '/users', function () {
    $controller = new UserController();
    $users = $controller->index();
});

$router->addRoute('GET', '/user/{:id}', function ($params) {
    $controller = new UserController();
    $controller->edit($params);

});

$router->addRoute('GET', '/users/add', function () {
    $controller = new UserController();
    $controller->add();
});
$router->addRoute('POST', '/user/remove/{:id}', function ($params) {
    $controller = new UserController();
    $controller->delete($params);
    header("Location: /users");
});

$router->addRoute('POST', '/users/submitUpdate', function () {
    
    $controller = new UserController();
    $controller->submitUpdate();
    header("Location: /users");
});

$router->addRoute('GET', '/myprofile', function () {
    $controller = new UserController();
    $controller->myProfile();
    // $controller->add();
});

$router->addRoute('POST', '/users/submit', function () {
    $controller = new UserController();
    $controller->submitForm();
    header("Location: /users");

});

$router->addRoute('GET', '/login', function () {
    include('login.php');
});

$router->addRoute('GET', '/SignOut', function () {
    session_destroy();
    header("Location: /");

});
$router->addRoute('GET', '/config', function () {
    $controller = new MainConfigController();
    $controller->index();

});

// Adicione outras rotas conforme necessário

$router->handleRequest();
?>  