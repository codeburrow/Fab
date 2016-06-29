<?php
namespace Fab\Controllers;

use Fab\Database\DB;

class MainController extends Controller
{

    public function __construct($item)
    {
        parent::__construct($item);
    }

    public function index()
    {
        echo $this->twig->render('index.twig');
    }

    public function portfolio()
    {
        echo $this->twig->render('portfolio.twig');
    }

    public function about()
    {
        echo $this->twig->render('about.twig');
    }

    public function contact()
    {
        echo $this->twig->render('contact.twig');
    }

    public function single_item()
    {
        $DB = new DB();

        $item = $DB->getItem($this->item);
        $item = $item[0];

        echo $this->twig->render('single_item.twig', array('item' => $item));
    }

    public function test()
    {
        
    }

}