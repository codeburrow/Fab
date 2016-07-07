<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/7/16
 * Time: 1:17 PM
 */
namespace Fab\Models;

use Fab\Database\DB;

class User
{
    protected $myDB;
    
    protected $username;
    protected $password;
    protected $isAdmin;
    
    public function __construct($username, $password)
    {
        $this->myDB = new DB();
        
        $this->identifyUser($username, $password);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function identifyUser($username, $password)
    {
        $user = $this->myDB->getUser($username, $password);

        if ( empty($user) ) {
            $this->isAdmin = null;
        } else {
            $user = $user[0];

            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->isAdmin = $user['isAdmin'];
        }
    }
    
    public function isAdmin()
    {        
        if ( $this->getIsAdmin() === '1' ){
            return "";
        } elseif ( is_null($this->isAdmin) ) {
            return "The credentials you entered are wrong";
        } elseif ( $this->isAdmin === '0' ) {
            return "You are a user but not an admin..";
        } else {
            return "If you forgot your credentials contact support";
        }
    }
    
}