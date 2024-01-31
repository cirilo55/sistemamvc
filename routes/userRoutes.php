<?php

use App\Controller\UserController;


$router->addRoute('GET', '/users', function () {
    $controller = new UserController();
    $controller->index();
});

$router->addRoute('GET', '/users/find/{:id}', function ($params) {
    $title = 'Editar Perfil';
    $controller = new UserController();
    $controller->edit($params);
});

$router->addRoute('GET', '/users/add', function () {
    $controller = new UserController();
    $controller->add();
});

$router->addRoute('GET', '/myprofile', function () {
    $controller = new UserController();
    $controller->myProfile();
});

$router->addRoute('POST', '/users/remove/{:id}', function ($params) {
    $controller = new UserController();
    $controller->delete($params);
    header("Location: /users");
});

$router->addRoute('POST', '/users/submit', function () {
    $controller = new UserController();
    $controller->submitForm();
    header("Location: /users");
    exit;
});

$router->addRoute('POST', '/users/submitUpdate', function () {
    $controller = new UserController();
    $controller->submitForm();
    header("Location: /myprofile");
});

$router->addRoute('POST', '/users/submitProfile', function () {
    $controller = new UserController();
    $controller->submitProfile();
    header("Location: /myprofile");
});