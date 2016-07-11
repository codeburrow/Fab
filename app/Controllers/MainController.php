<?php
namespace Fab\Controllers;

use Fab\Services\SwiftMailer;

class MainController extends Controller
{

    public function __construct($item=null)
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

    public function postContact()
    {
        $mailer = new SwiftMailer();
        
        $result = $mailer->sendEmail($_POST);
        
        if ($result==true) 
            $message="Thank you for your email.\n We'll be in touch soon.";
        else
            $message="There was an error. We couldn't send your email. \n Please contact us at 'fab.agia@gmail.com'." ;

        echo $this->twig->render('contact.twig', array('result'=>$result, 'message'=>$message));
    }

    public function error404()
    {
        echo $this->twig->render('error404.twig');
    }

}