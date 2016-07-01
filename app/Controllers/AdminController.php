<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/1/16
 * Time: 9:24 PM
 */
namespace Fab\Controllers;

use Twig_Environment;
use Twig_Loader_Filesystem;

class AdminController extends Controller
{
    public function __construct($item)
    {
        parent::__construct($item);

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/admin');
        $this->twig = new Twig_Environment($loader);
    }

    public function index()
    {
        echo $this->twig->render('dashboard.twig');
    }

}