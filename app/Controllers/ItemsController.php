<?php
namespace Fab\Controllers;

use Fab\Database\DB;

class ItemsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showAllItems()
    {
        $myDB = new DB();

        $items = $myDB->getAllItems();

        echo $this->twig->render( 'portfolio.twig', array('items' => $items) );

    }

}