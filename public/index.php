<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Fab\Controllers;
use Fab\Router;

$router = new Router\Router();

$router->get('/', 'MainController', 'index');
$router->get('/portfolio', 'ItemsController', 'showAllItems');
$router->get('/portfolio/[\w\d]+', 'ItemsController', 'single_item');
$router->get('/about', 'MainController', 'about');
$router->get('/contact', 'MainController', 'contact');
$router->get('/admin/dashboard', 'AdminController', 'index');
$router->get('/admin/dashboard/addItem', 'AdminController', 'addItem');
$router->get('/admin/dashboard/deleteItem', 'AdminController', 'deleteItem');

$router->post('/admin/addItem', 'AdminController', 'postAddItem');

//$router->get('/test', 'ItemsController', 'showAllItems');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

