<?php
namespace Fab\Controllers;

use Fab\Database\DB;

class ItemsController extends Controller
{
    public function __construct($data=null)
    {
        parent::__construct($data);
    }

    public function showAllItems()
    {
        $myDB = new DB();

        $items = $myDB->getAllItems();

        echo $this->twig->render( 'portfolio.twig', array('items' => $items) );
    }

    public function showAllProjectItems()
    {
        $myDB = new DB();

        $urlName = $this->item;

        $item = $myDB->getItem($urlName);
        $nextItem = $myDB->getNextProject($item);
        $previousItem = $myDB->getPreviousProject($item);
        
        $projectID = $item['projectID'];

        $items = $myDB->getAllProjectItemsByProjectID($projectID);

        echo $this->twig->render( 'single_item.twig', array('items' => $items, 'nextItem'=>$nextItem, 'previousItem'=>$previousItem) );
    }

    public function showAllProjects()
    {
        $myDB = new DB();

        $items = $myDB->getAllProjectsForPortfolio();
        
        echo $this->twig->render( 'portfolio.twig', array('items' => $items) );
    }

    public function single_item()
    {
        $DB = new DB();

        $item = $DB->getItem($this->item);

        if ( !empty($item) ){

            $nextItem = $DB->getNextItem($item);
            $previousItem = $DB->getPreviousItem($item);
            
            echo $this->twig->render('single_item.twig', array('item'=>$item, 'nextItem'=>$nextItem, 'previousItem'=>$previousItem));
            
        } else { //if no items found
            echo $this->twig->render('error404.twig');
        }
    }

}