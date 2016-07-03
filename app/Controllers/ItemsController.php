<?php
namespace Fab\Controllers;

use Fab\Database\DB;

class ItemsController extends Controller
{
    public function __construct($item=null)
    {
        parent::__construct($item);
    }

    public function showAllItems()
    {
        $myDB = new DB();

        $items = $myDB->getAllItems();

        echo $this->twig->render( 'portfolio.twig', array('items' => $items) );
    }

    public function single_item()
    {
        $DB = new DB();

        $item = $DB->getItem($this->item);
        //ToDo: What if there is no $item[0]?
        $item = $item[0];

        echo $this->twig->render('single_item.twig', array('item' => $item));
    }

}