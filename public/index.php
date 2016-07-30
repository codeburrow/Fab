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
$router->get('/portfolio', 'ItemsController', 'showAllProjects');
$router->get('/portfolio/[\w\d\-\!\?]+', 'ItemsController', 'showAllProjectItems');
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
//Admin - Projects
$router->get('/admin/dashboard/addProject', 'AdminController', 'addProject');
$router->get('/admin/dashboard/editProject', 'AdminController', 'editProject');
$router->get('/admin/dashboard/deleteProject', 'AdminController', 'deleteProject');

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
//Admin - Projects
$router->post('/admin/addProject', 'AdminController', 'postAddProject');
$router->post('/admin/editProject', 'AdminController', 'postEditProject');
$router->post('/admin/deleteProject', 'AdminController', 'postDeleteProject');


////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

