<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/1/16
 * Time: 9:24 PM
 */
namespace Fab\Controllers;

use Fab\Database\DB;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Fab\Services\UploadImage;

class AdminController extends Controller
{
    public function __construct($item = null)
    {
        parent::__construct($item = null);

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/admin');
        $this->twig = new Twig_Environment($loader);
    }

    public function index()
    {
        echo $this->twig->render('dashboard.twig');
    }

    public function addItem()
    {
        echo $this->twig->render('addItem.twig');
    }
    
    public function postAddItem()
    {
        $DB = new DB();
        $uploadImageService = new UploadImage();
        $result = false;

        $uploadError = $uploadImageService->uploadImage();
        if ( $uploadError == ""){

            //ToDo: Add Exception when try to put as urlName invalid characters.
            //ToDo: File Size whould be bigger than it is right now

            $result = $DB->addItem($_POST, $_FILES['image']['name']);
            if( $result == TRUE){
                $flashMessage = "Item Succesfully Added";
            } else {
                $flashMessage = "Error: Could not add item. Please check the values you have given.";
            }
        } else {
            $flashMessage = $uploadError . "\nError: Could not upload image.";
        }


        echo $this->twig->render('addItem.twig', array('flashMessage'=>$flashMessage, 'result'=>$result));
    }

}