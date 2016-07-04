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
        if (preg_match("/^[a-zA-Z0-9 ]*$/", $data['urlName'])) {

            $tags = "";
            if ($data['tags'] != null)
                foreach ($data['tags'] as $tag) {
                    $tags .= $tag . ' ';
                }

            $stmt = $this->conn->prepare("INSERT INTO fab.items (`image`, `description`, `title`, `subtitle`, `urlName`, `tags`)
    VALUES (:image, :description, :title, :subtitle, :urlName, :tags)");
            $stmt->bindParam(':image', $imageName);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':subtitle', $data['subtitle']);
            $stmt->bindParam(':urlName', $data['urlName']);
            $stmt->bindParam(':tags', $tags);
            $result = $stmt->execute();

            return $result = $result == true ? $result = "" : $result = "Error inserting into database.";
        } else {
            $errorMessage = "Only letters and numbers allowed in the URL name";

            return $errorMessage;
        }
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

    public function deleteItems($data)
    {
        if (isset($data['items'])) {
            $items = $data['items'];

            foreach ($items as $item => $id) {

                //Get the image for the current item so that we delete it from server
                $getNameOfImage = $this->conn->prepare("SELECT image 
      FROM fab.items WHERE id = :id");
                $getNameOfImage->bindParam(':id', $id);
                $getNameOfImage->execute();

                // set the resulting array to associative
                $getNameOfImage->setFetchMode(PDO::FETCH_ASSOC);
                $arrayWithNameOfImage = $getNameOfImage->fetchAll();
//                var_dump($arrayWithNameOfImage);

                //Delete uploaded image from server
                $nameOfImage = $arrayWithNameOfImage[0]['image'];
//                var_dump("Image: " . $nameOfImage);
                $resultRemoveImage = unlink("images/$nameOfImage");
                if ($resultRemoveImage == false){
                    $result = 3; //Image was not removed from server
                    break;
                }

                //Delete row from db
                $stmt = $this->conn->prepare("DELETE FROM fab.items
WHERE id=:id ;");
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                if ($stmt == true) {
                    $result = 0; //all good
                } else {
                    $result = 2; //sth went wrong
                    break;
                }
            }
        } else {
            $result = 1; //No items selected in form
        }

        return $result;
    }

}