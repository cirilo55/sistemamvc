<?php
use App\Controller\MainConfigController;

$router->addRoute('GET', '/config', function () {
    $controller = new MainConfigController();
    $controller->index();

});