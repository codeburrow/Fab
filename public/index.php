<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Fab\Controllers;
use Fab\Router;

$router = new Router\Router();

$router->get('/', 'MainController', 'index');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

