<?php

use App\Controllers\ContactController;
use App\Router;

$router = new Router();

$router->get('/', function () {
    echo "Home";
});

$router->get('/about', function () {
    echo "About";
});

$router->get('/contact', [ContactController::class, 'index']);

$router->addNotFoundHandler(function () {
    require_once(__DIR__ . "/../templates/404.php");
});

$router->run();
