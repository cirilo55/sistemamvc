<?php
use App\Controller\TaskController;

$router->addRoute('GET', '/tarefas' , function() {
    $controller = new TaskController();
    $controller->index();
});
$router->addRoute('GET', '/tarefas/add' , function() {
    $controller = new TaskController();
    $controller->add();
});

$router->addRoute('POST', '/tarefas/submit', function () {
    ob_clean();
    $controller = new TaskController();
    $controller->submitForm();
    header("Location: /tarefas");

    exit;
});