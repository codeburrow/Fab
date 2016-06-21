<?php
namespace Fab\Controllers;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller
{
    protected $twig;

    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/');
        $this->twig = new Twig_Environment($loader);
    }
}