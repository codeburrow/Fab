<?php
namespace Fab\Database;

use PDO;
use PDOException;

class DB
{
    protected $servername;
    protected $port;
    protected $dbname;
    protected $username;
    protected $password;
    protected $conn;

    /**
     * DB constructor. By default connect to Homestead virtual DB server and to the 'kourtis' database schema.
     * @param string $servername
     * @param string $port
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct($servername = "127.0.0.1", $port = "33060", $dbname = "fab", $username = "homestead", $password = "secret")
    {
        $this->servername = $servername;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    public function connect()
    {
        try {
            $conn = new PDO("mysql:host=$this->servername;port:$this->port;dbname=$this->servername", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = $conn;
//            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getAllItems()
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.items");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getItem($urlName)
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.items WHERE urlName LIKE '%$urlName%'");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        
        return $result;
    }

    public function addItem($data, $imageName)
    {
        $tags = "";
        if ($tags != null)
        foreach ($data['tags'] as $tag){
            $tags .= $tag . ' ';
        }

        $stmt = $this->conn->prepare( "INSERT INTO fab.items (`image`, `description`, `title`, `subtitle`, `urlName`, `tags`)
    VALUES (:image, :description, :title, :subtitle, :urlName, :tags)" );
        $stmt->bindParam(':image', $imageName);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':subtitle', $data['subtitle']);
        $stmt->bindParam(':urlName', $data['urlName']);
        $stmt->bindParam(':tags', $tags);
        $result = $stmt->execute();

        return $result;
    }

    public function getCarouselPosts()
    {
        $stmt = $this->conn->prepare("SELECT * FROM kourtis.posts WHERE kourtis.posts.inCarousel = 1");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNewestPosts($numberOfPosts = 3)
    {
        $stmt = $this->conn->prepare("SELECT * 
      FROM kourtis.posts 
      ORDER BY kourtis.posts.id DESC
      LIMIT $numberOfPosts");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

}