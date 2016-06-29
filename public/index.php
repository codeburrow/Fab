<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Fab\Controllers;
use Fab\Router;

$router = new Router\Router();

$router->get('/', 'MainController', 'index');
$router->get('/portfolio', 'MainController', 'portfolio');
$router->get('/portfolio/[\w\d]+', 'MainController', 'single_item');
$router->get('/about', 'MainController', 'about');
$router->get('/contact', 'MainController', 'contact');

$router->get('/test', 'ItemsController', 'showAllItems');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

