<?php
namespace Fab\Controllers;

use Fab\Database\DB;

class PostsController extends Controller
{
    protected $staticBlogRepo;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function showAllPosts()
    {
        $myDB = new DB();

        $posts = $myDB->getAllPosts();

        echo $this->twig->render( 'post_list.twig', array('posts' => $posts) );

    }

    public function single_post()
    {
        echo $this->twig->render('single_post.twig');
    }

}