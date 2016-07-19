<?php
namespace Fab\Database;

use PDO;
use PDOException;

class DB
{
    protected $host;
    protected $port;
    protected $dbname;
    protected $username;
    protected $password;
    protected $conn;

    /**
     * DB constructor. By default connect to papaki.gr DB (MySQL) and to the 'fab' database schema.
     */
    public function __construct()
    {
        $this->host = getenv('HOST');
        $this->port = getenv('PORT');
        $this->dbname = getenv('DBNAME');
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');

        $this->connect();
    }

    /**
     * Alternative DB constructor for connection to the Homestead virtual DB server
     * @param string $servername
     * @param string $port
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
//    public function __construct($servername = "127.0.0.1", $port = "33060", $dbname = "fab", $username = "homestead", $password = "secret")
//    {
//        $this->servername = $servername;
//        $this->port = $port;
//        $this->dbname = $dbname;
//        $this->username = $username;
//        $this->password = $password;
//
//        $this->connect();
//    }

    public function connect()
    {
        try {
            $conn = new PDO("mysql:host=$this->host;port:$this->port;dbname=$this->host", $this->username, $this->password);
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
        $stmt = $this->conn->prepare("SELECT * FROM fab.items WHERE urlName LIKE :urlName");
        $stmt->bindParam(':urlName', $urlName);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNextItem($item)
    {
        $stmt = $this->conn->prepare(" select * from fab.items where id = (select min(id) from fab.items where id > :id) ");
        $stmt->bindParam(':id', $item['id']);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getPreviousItem($item)
    {
        $stmt = $this->conn->prepare(" select * from fab.items where id = (select max(id) from fab.items where id < :id) ");
        $stmt->bindParam(':id', $item['id']);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function addItem($data, $imageName)
    {
        if (preg_match("/^[a-zA-Z0-9 ]*$/", $data['urlName'])) {

            $tags = "";
            if (isset($data['tags']))
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
                if ($resultRemoveImage == false) {
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

    public function editItems($data)
    {
        if (isset($data['items'])) {

            foreach ($data['items'] as $itemID) {

                $query_selectItems = "SELECT * FROM fab.items WHERE id=:id;";

                try {
                    $db = $this->conn;
                    $stmt_editItems = $db->prepare($query_selectItems);
                    $stmt_editItems->bindParam(':id', $itemID);
                    $result_items = $stmt_editItems->execute();
                } catch (PDOException $ex) {
                    // For testing, you could use a die and message.
                    //die("Failed to run query: " . $ex->getMessage());

                    //or just use this use this one to product JSON data:
                    $response["success"] = 0;
                    $response["message"] = "Database Error. Please Try Again!";

                    return $response;
                }

                //fetching all the rows from the query
                $dbItems = $stmt_editItems->fetch();

                if (!empty($dbItems)) {

                    $urlName = $data['urlName'][$itemID];
                    $title = $data['title'][$itemID];
                    $subtitle = $data['subtitle'][$itemID];
                    $tags = $data['tags'][$itemID];
                    $description = $data['description'][$itemID];

                    try {
                        $update_item = $this->conn->prepare("UPDATE fab.items SET urlName=:urlName, title=:title, subtitle=:subtitle, tags=:tags, description=:description WHERE id=:id;");
                        $update_item->bindParam(':id', $itemID);
                        $update_item->bindParam(':urlName', $urlName);
                        $update_item->bindParam(':title', $title);
                        $update_item->bindParam(':subtitle', $subtitle);
                        $update_item->bindParam(':tags', $tags);
                        $update_item->bindParam(':description', $description);
                        $result_editItem = $update_item->execute();
                    } catch (PDOException $ex) {
                        // For testing, you could use a die and message.
                        //die("Failed to run query: " . $ex->getMessage());

                        //or just use this use this one to product JSON data:
                        $response["success"] = 0;
                        $response["message"] = "Database Error 2. Please Try Again!";

                        return $response;
                    }

                    if ($result_editItem) {
                        $response["success"] = 1;
                        $response["message"] = "Item(s) Successfully Edited ";
                    } else {
                        $response["success"] = 0;
                        $response["message"] = "Item(s) Could Not Be Edited ";
                    }

                } else {
                    // no routes found
                    $response["success"] = 0;
                    $response["message"] = "Error. No item with ID $itemID was found.";

                    return $response;
                }
            }

            return $response;
        } else {
            $response["success"] = 2;
            $response["message"] = "Error. You did NOT select any items!";

            return $response;
        }
    }

    public function getUser($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.users WHERE username LIKE :username AND password LIKE :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }


//    public function getCarouselPosts()
//    {
//        $stmt = $this->conn->prepare("SELECT * FROM kourtis.posts WHERE kourtis.posts.inCarousel = 1");
//        $stmt->execute();
//
//        // set the resulting array to associative
//        $stmt->setFetchMode(PDO::FETCH_ASSOC);
//        $result = $stmt->fetchAll();
//
//        return $result;
//    }
//
//    public function getNewestPosts($numberOfPosts = 3)
//    {
//        $stmt = $this->conn->prepare("SELECT *
//      FROM kourtis.posts
//      ORDER BY kourtis.posts.id DESC
//      LIMIT $numberOfPosts");
//        $stmt->execute();
//
//        // set the resulting array to associative
//        $stmt->setFetchMode(PDO::FETCH_ASSOC);
//        $result = $stmt->fetchAll();
//
//        return $result;
//    }

}