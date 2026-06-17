<?php
// process_login.php
namespace Sys;
use App\Controller\AuthController;
use App\Service\AuthService;

$app = require dirname(__DIR__) . '/sys/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController($app['container']->get(AuthService::class)))->login();
}

header('Location: /login');
exit();
