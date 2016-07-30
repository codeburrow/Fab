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
use Fab\Services\SwiftMailer;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Fab\Services\UploadImageService;

class AdminController extends Controller
{
    protected $user;

    public function __construct($data = null)
    {
        parent::__construct($data = null);

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/admin');
        $this->twig = new Twig_Environment($loader, array(
            'debug' => true
        ));
        $this->twig->addExtension(new Twig_Extension_Debug());

        if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
            $this->user = new User($_SESSION['user'], $_SESSION['password']);
        }
    }

    /**** GENERAL ****/
//    public function index()
//    {
//        if ($this->adminIsLoggedIn())
//            echo $this->twig->render('dashboard.twig');
//        else
//            $this->login();
//    }

    public function contactSupport()
    {
        if ($this->adminIsLoggedIn())
            echo $this->twig->render('contactSupport.twig');
        else
            echo $this->twig->render('login.twig');
    }

    public function postContact()
    {
        $mailer = new SwiftMailer();

        $result = $mailer->sendEmailToSupport($_POST);

        echo $this->twig->render('contactSupport.twig', array('result' => $result));
    }

    public function login($errorMessage = null)
    {
        if (isset($errorMessage))
            echo $this->twig->render('login.twig', array('errorMessage' => $errorMessage));
        else
            echo $this->twig->render('login.twig');
    }

    public function postLogin()
    {
        $myDB = new DB();

        $user = $myDB->getUser($_POST['username'], $_POST['password']);

        if (empty($user)) {
            $errorMessage = "Wrong Credentials.";
            $this->login($errorMessage);
        } else {
            $this->user = new User($_POST['username'], $_POST['password']); //find the user from db

            $errorMessage = $this->user->isAdmin(); //authenticate user

            if (empty($errorMessage)) { //if authentication successful

                $this->user->login(); //set Cookies and Session

                $this->addItem(); //show addItem page
            } else {
                $this->login($errorMessage); //redirect to login page
            }
        }
    }

    public function logout()
    {
        if ($this->adminIsLoggedIn()) {
            $this->user->logout();
            $this->login();
        }
    }

    public function adminIsLoggedIn()
    {
        if (isset($this->user) && $this->user->isLoggedIn() && empty($this->user->isAdmin()))
            return true;
        else
            return false;
    }

    /**** ITEMS ****/
    public function addItem()
    {
        if ($this->adminIsLoggedIn()) {
            $myDB = new DB();

            $projects = $myDB->getAllProjects();    
        
            echo $this->twig->render('addItem.twig', array('projects'=>$projects));
        }
        else
            echo $this->twig->render('login.twig');
    }

    public function postAddItem()
    {
        $DB = new DB();
        $uploadImageService = new UploadImageService();
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
        if ($this->adminIsLoggedIn()) {
            $myDB = new DB();
            $items = $myDB->getAllItems();

            echo $this->twig->render('deleteItem.twig', array('items' => $items));
        } else {
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

    public function editItem($result=null)
    {
        if ($this->adminIsLoggedIn()) {
            $myDB = new DB();
            $items = $myDB->getAllItems();
            $projects = $myDB->getAllProjects();

            echo $this->twig->render('editItem.twig', array('items'=>$items, 'projects'=>$projects, 'result' => $result));
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function postEditItem()
    {
        $myDB = new DB();
        
        $result = $myDB->editItems($_POST);

        $this->editItem($result);
    }

    /**** PROJECTS ****/
    public function addProject()
    {
        if ($this->adminIsLoggedIn())
            echo $this->twig->render('addProject.twig');
        else
            echo $this->twig->render('login.twig');
    }

    public function postAddProject()
    {
        $DB = new DB();
        $success = false;

        //Add row to db
        $result = $DB->addProject($_POST);

        if (empty($result)) { //successfully added row
            $flashMessage = "Item Succesfully Added";
            $success = true;
        } else { //failed to add row
            $flashMessage = $result;
        }

        echo $this->twig->render('addProject.twig', array('flashMessage' => $flashMessage, 'success' => $success));
    }

    public function deleteProject()
    {
        if ($this->adminIsLoggedIn()) {
            $myDB = new DB();
            $projects = $myDB->getAllProjects();

            echo $this->twig->render('deleteProject.twig', array('projects' => $projects));
        } else {
            echo $this->twig->render('login.twig');
        }

    }

    public function postDeleteProject()
    {
        $myDB = new DB();

        $result = $myDB->deleteProjects($_POST);

        $projects = $myDB->getAllProjects();

        echo $this->twig->render('deleteProject.twig', array('projects' => $projects, 'result' => $result));
    }

    public function editProject()
    {
        if ($this->adminIsLoggedIn()) {
            $myDB = new DB();
            $projects = $myDB->getAllProjects();

            echo $this->twig->render('editProject.twig', array('projects' => $projects));
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function postEditProject()
    {
        $myDB = new DB();
        $result = $myDB->editProjects($_POST);

        $projects = $myDB->getAllProjects();

        echo $this->twig->render('editProject.twig', array('projects' => $projects, 'result' => $result));
    }


    /**** CAROUSEL ****/
    public function editCarousel($success = null, $flashMessage = null)
    {
        if ($this->adminIsLoggedIn()) {

            $myDB = new DB();
            $gallery = $myDB->getCarouselGallery();
            $carouselImages = $myDB->getCarouselImages();

            if (isset($success) && isset($flashMessage)) {
                echo $this->twig->render('editCarousel.twig', array('gallery' => $gallery, 'carouselImages' => $carouselImages, 'success' => $success, 'flashMessage' => $flashMessage));
            } else {
                echo $this->twig->render('editCarousel.twig', array('gallery' => $gallery, 'carouselImages' => $carouselImages));
            }

        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function postEditCarousel()
    {
        if ($this->adminIsLoggedIn()) {

            $myDB = new DB();
            $i = 0;
            $flashMessage = "Carousel Successfully Edited!";
            $success = true;

            if (isset($_GET['included'])) {
                $incl = $_GET['included'];
                foreach ($incl as $includeID) {
                    $i++;
                    echo "ID: " . $includeID . " POSITION: " . $i;
                    $result = $myDB->includeInCarousel($includeID, $i);

                    if ($result == false) {
                        $success = false;
                        $flashMessage = "Error: Something went wrong!";
                        break;
                    }
                }
            }

            if (isset($_GET['notIncluded'])) {
                $notIncl = $_GET['notIncluded'];
                foreach ($notIncl as $notInclID) {
                    echo "Not Included ID: " . $notInclID;
                    $result = $myDB->notIncludeInCarousel($notInclID);

                    if ($result == false) {
                        $success = false;
                        $flashMessage = "Error: Something went wrong!";
                        break;
                    }
                }
            }

            $this->editCarousel($success, $flashMessage);

        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function uploadCarousel($success = null, $flashMessage = null)
    {
        if ($this->adminIsLoggedIn()) {
            if (isset($success) && isset($flashMessage)) {
                echo $this->twig->render('uploadCarousel.twig', array('success' => $success, 'flashMessage' => $flashMessage));
            } else {
                echo $this->twig->render('uploadCarousel.twig');
            }
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    public function postUploadCarousel()
    {
        if ($this->adminIsLoggedIn()) {

            $DB = new DB();
            $uploadDir = 'images/carousel/';
            $uploadImageService = new UploadImageService();
            $success = false;

            //Try to upload image
            $uploadError = $uploadImageService->uploadImage($uploadDir);
            if (empty($uploadError)) {

                //Add row to db
                $nameOfImage = $_FILES['image']['name'];
                $result = $DB->addCarouselImage($_POST, $nameOfImage);

                if (empty($result)) { //successfully added row
                    $flashMessage = "Item Succesfully Added";
                    $success = true;
                } else { //failed to add row
                    $flashMessage = "Error: Could not add item. Please check the values you have given.";
                    //Delete uploaded image from server
                    unlink($uploadDir . $nameOfImage);
                }

            } else { //image failed to upload
                $flashMessage = $uploadError . "\nError: Could not upload image.";
            }

            $this->uploadCarousel($success, $flashMessage);

        } else {

            echo $this->twig->render('login.twig');

        }
    }

    public function deleteFromCarousel()
    {
        if ($this->adminIsLoggedIn()) {

            $myDB = new DB();

            if (isset($_GET['ID']) && isset($_GET['path'])) {

                $id = $_GET['ID'];
                $path = $_GET['path'];

                $myDB->deleteFromCarousel($id);

                //deletes the file from the server
                echo unlink(getenv('DOCUMENT_ROOT') . $path);
            }

        } else {
            echo $this->twig->render('login.twig');
        }
    }

}