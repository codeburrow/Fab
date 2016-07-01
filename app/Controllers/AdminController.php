<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/1/16
 * Time: 9:24 PM
 */
namespace Fab\Controllers;

class AdminController extends Controller
{
    public function __construct($item)
    {
        parent::__construct($item);
    }

    public function index()
    {
        echo $this->twig->render('dashboard.twig');
    }

}