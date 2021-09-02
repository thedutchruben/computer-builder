<?php

use Bramus\Router\Router;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();

$router->get('/', function() {
    include 'pages/HomePage.php';
});

$router->run();