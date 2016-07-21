<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Fab\Controllers;
use Fab\Router;

//Load .env variables
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../app/');
$dotenv->load();

$router = new Router\Router();

/******** GET ********/
//Public
$router->get('/', 'MainController', 'index');
$router->get('/portfolio', 'ItemsController', 'showAllItems');
$router->get('/portfolio/[\w\d]+', 'ItemsController', 'single_item');
$router->get('/about', 'MainController', 'about');
$router->get('/contact', 'MainController', 'contact');
//Admin - Items
$router->get('/admin/dashboard', 'AdminController', 'addItem');
$router->get('/admin/dashboard/addItem', 'AdminController', 'addItem');
$router->get('/admin/dashboard/deleteItem', 'AdminController', 'deleteItem');
$router->get('/admin/dashboard/editItem', 'AdminController', 'editItem');
$router->get('/admin/dashboard/contactSupport', 'AdminController', 'contactSupport');
//Admin - Carousel
$router->get('/admin/dashboard/editCarousel', 'AdminController', 'editCarousel');
$router->get('/admin/dashboard/postEditCarousel', 'AdminController', 'postEditCarousel');
$router->get('/admin/dashboard/uploadCarousel', 'AdminController', 'uploadCarousel');
$router->get('/deleteFromCarouselDB', 'AdminController', 'deleteFromCarousel');
//Admin - General
$router->get('/admin/login', 'AdminController', 'login');
$router->get('/admin/logout', 'AdminController', 'logout');

/******** POST ********/
//Public
$router->post('/contact', 'MainController', 'postContact');
//Admin - Items
$router->post('/admin/addItem', 'AdminController', 'postAddItem');
$router->post('/admin/deleteItem', 'AdminController', 'postDeleteItem');
$router->post('/admin/editItem', 'AdminController', 'postEditItem');
//Admin - General
$router->post('/admin/login', 'AdminController', 'postLogin');
$router->post('/admin/contact', 'AdminController', 'postContact');
//Admin - Carousel
$router->post('/admin/dashboard/uploadCarousel', 'AdminController', 'postUploadCarousel');


////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

