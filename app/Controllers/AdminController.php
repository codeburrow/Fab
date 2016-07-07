<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/1/16
 * Time: 9:24 PM
 */
namespace Fab\Controllers;

use Fab\Database\DB;
use Fab\Models\User;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Fab\Services\UploadImage;

class AdminController extends Controller
{
    public function __construct($item = null)
    {
        parent::__construct($item = null);

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/admin');
        $this->twig = new Twig_Environment($loader, array(
            'debug' => true
        ));
        $this->twig->addExtension(new Twig_Extension_Debug());
    }

    public function index()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === '1') {
            echo $this->twig->render('dashboard.twig');
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function addItem()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === '1') {
            echo $this->twig->render('addItem.twig');
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function postAddItem()
    {
        $DB = new DB();
        $uploadImageService = new UploadImage();
        $success = false;

        //Try to upload image
        $uploadError = $uploadImageService->uploadImage();
        if (empty($uploadError)) {

            //Add row to db
            $nameOfImage = $_FILES['image']['name'];
            $result = $DB->addItem($_POST, $nameOfImage);

            if (empty($result)) { //successfully added row
                $flashMessage = "Item Succesfully Added";
                $success = true;
            } else { //failed to add row
                $flashMessage = "Error: Could not add item. Please check the values you have given.";
                //Delete uploaded image from server
                unlink("images/$nameOfImage");
            }

        } else { //image failed to upload
            $flashMessage = $uploadError . "\nError: Could not upload image.";
        }

        echo $this->twig->render('addItem.twig', array('flashMessage' => $flashMessage, 'success' => $success));
    }

    public function deleteItem()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === '1') {
            $myDB = new DB();
            $items = $myDB->getAllItems();

            echo $this->twig->render('deleteItem.twig', array('items' => $items));        } else {
            echo $this->twig->render('login.twig');
        }

    }

    public function postDeleteItem()
    {
        $myDB = new DB();

        $result = $myDB->deleteItems($_POST);

        if ($result == 0) {
            $message = "Success! Items Deleted.";
        } elseif ($result == 1) {
            $message = "Failure. You did not select any items!";
        } elseif ($result == 2) {
            $message = "Failure. Something went wrong. Please try again.";
        } elseif ($result == 3) {
            $message = "Failure. Could not remove image. Make sure you selected a valid item.";
        }

        $items = $myDB->getAllItems();

        echo $this->twig->render('deleteItem.twig', array('items' => $items, 'result' => $result, 'message' => $message));
    }

    public function editItem()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === '1') {
            $myDB = new DB();
            $items = $myDB->getAllItems();

            echo $this->twig->render('editItem.twig', array('items' => $items));
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function postEditItem()
    {
        $myDB = new DB();
        $result = $myDB->editItems($_POST);

        $items = $myDB->getAllItems();

        echo $this->twig->render('editItem.twig', array('items' => $items, 'result' => $result));
    }

    public function contactSupport()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === '1') {
            echo $this->twig->render('contactSupport.twig');
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function postLogin()
    {
        $user = $_POST;

        $user = new User($user['username'], $user['password']); //find the user from db

        $loginSuccessMessage = $user->isAdmin(); //authenticate user

        if (empty($loginSuccessMessage)) { //if authentication successful

            //Start $_SESSION
            $status = session_status();
            if ($status == PHP_SESSION_NONE) {
                //There is no active session
                session_start();
            } elseif ($status == PHP_SESSION_DISABLED) {
                //Sessions are not available
            } elseif ($status == PHP_SESSION_ACTIVE) {
                //Destroy current and start new one
                session_destroy();
                session_start();
            }

            //Set $_SESSION variables
            $_SESSION['user'] = $user->getUsername();
            $_SESSION['isAdmin'] = $user->getIsAdmin();

            echo $this->twig->render('dashboard.twig');
        } else {
            echo $this->twig->render('login.twig', array('errorMessage' => $loginSuccessMessage));
        }
    }

    public function logout()
    {
        $status = session_status();
        if ($status == PHP_SESSION_NONE) {
            //There is no active session
            session_start();
        } elseif ($status == PHP_SESSION_DISABLED) {
            //Sessions are not available
        } elseif ($status == PHP_SESSION_ACTIVE) {
            //Destroy current and start new one
            session_destroy();
            session_start();
        }

        //Unset $_SESSION variables
        unset($_SESSION["user"]);
        unset($_SESSION["isAdmin"]);

        echo $this->twig->render('login.twig');
    }

}