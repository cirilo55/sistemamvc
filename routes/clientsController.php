<?php
use App\Controller\ClientsController;

$router->addRoute('GET', '/clientes', function () {
    $controller = new ClientsController();
    $controller->index();

});
