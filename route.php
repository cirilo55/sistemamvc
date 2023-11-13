<?php
use Sys\Router;
use App\Controller\UserController;
use App\Controller\MainConfigController;
use App\Controller\ClientsController;

$router = new Router();
$router->addRoute('GET', '/', function () {

});

$router->addRoute('GET', '/users', function () {
    $controller = new UserController();
    $users = $controller->index();
    // echo json_encode($users);

});

$router->addRoute('GET', '/users/find/{:id}', function ($params) {
    $controller = new UserController();
    $controller->edit($params);

});

$router->addRoute('GET', '/users/add', function () {
    $controller = new UserController();
    $controller->add();
});

$router->addRoute('POST', '/users/remove/{:id}', function ($params) {
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
    exit;
});

$router->addRoute('GET', '/login', function () {
    include('login.php');
});

$router->addRoute('GET', '/SignOut', function () {
    session_destroy();
    header("Location: /login");

});
$router->addRoute('GET', '/config', function () {
    $controller = new MainConfigController();
    $controller->index();

});
$router->addRoute('GET', '/clientes', function () {
    $controller = new ClientsController();
    $controller->index();

});

$router->handleRequest();
?>  