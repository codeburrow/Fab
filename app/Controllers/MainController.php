<?php
namespace Fab\Controllers;

class MainController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo $this->twig->render('index.twig');
    }

    public function portfolio()
    {
        echo $this->twig->render('portfolio.twig');
    }

    public function contact()
    {
        echo $this->twig->render('contact.twig');
    }
    
}