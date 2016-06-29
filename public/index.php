<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Fab\Controllers;
use Fab\Router;

$router = new Router\Router();

$router->get('/', 'MainController', 'index');
$router->get('/portfolio', 'MainController', 'portfolio');
$router->get('/about', 'MainController', 'about');
$router->get('/contact', 'MainController', 'contact');
$router->get('/single_item', 'MainController', 'single_item');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

