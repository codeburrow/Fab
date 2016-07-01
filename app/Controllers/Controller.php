<?php
namespace Fab\Controllers;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller
{
    protected $twig;
    protected $item;

    public function __construct( $item=null )
    {
        //Environment Variable
        //define('CSS_PATH', 'http://fab.app/public/css/');
        
        $this->item = $item;
        
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/');
        $this->twig = new Twig_Environment($loader);
    }
}