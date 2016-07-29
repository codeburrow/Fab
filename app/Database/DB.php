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
//    public function __construct()
//    {
//        $this->host = getenv('HOST');
//        $this->port = getenv('PORT');
//        $this->dbname = getenv('DBNAME');
//        $this->username = getenv('USERNAME');
//        $this->password = getenv('PASSWORD');
//
//        $this->connect();
//    }

    /**
     * Alternative DB constructor for connection to the Homestead virtual DB server
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
    
    public function getAllProjects()
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.projects");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getAllProjectsForPortfolio()
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT * FROM fab.items
            INNER JOIN (
              SELECT MIN(title) title, projectID FROM fab.items
              WHERE projectID IS NOT NULL
              GROUP BY projectID
            ) b ON items.projectID = b.projectID and items.title LIKE b.title;
            ");
            $stmt->execute();

            // set the resulting array to associative
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAllProjectItemsByProjectID($projectID)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM fab.items 
                JOIN fab.projects ON projects.id = items.projectID 
                WHERE projectID = :projectID");
            $stmt->bindParam('projectID', $projectID);
            $stmt->execute();

            // set the resulting array to associative
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getItem($urlName)
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.items WHERE urlName LIKE :urlName");
        $stmt->bindParam(':urlName', $urlName);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

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

    public function getNextProject($item)
    {
        $stmt = $this->conn->prepare(" 
             select * from fab.items 
             where projectID = (
                 select min(projectID) from fab.items 
                 where projectID > :projectID
             ); ");
        $stmt->bindParam(':projectID', $item['projectID']);
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

    public function getPreviousProject($item)
    {
        $stmt = $this->conn->prepare(" select * from fab.items where projectID = (select max(projectID) from fab.items where projectID < :projectID) ");
        $stmt->bindParam(':projectID', $item['projectID']);
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

    public function addProject($data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO fab.projects (`name`, `projectDescription`)
                                          VALUES (:name, :description);");
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $result = $stmt->execute();

            $result == true ? $result = "" : $result = "Error inserting into database.";
        } catch (PDOException $e) {
            $result = "Error: This project name already exists!\n" . $e->getMessage();
        }

        return $result;
    }

    public function deleteProjects($data)
    {
        if (isset($data['projects'])) {
            $projects = $data['projects'];

            foreach ($projects as $project => $id) {

                try {
                    //Delete row from db
                    $stmt = $this->conn->prepare("DELETE FROM fab.projects
WHERE id=:id ;");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();

                    if ($stmt == true) {
                        $result['success'] = true; //all good
                        $result['message'] = "Success! Projects Deleted.";
                        break;
                    } else {
                        $result['success'] = false; //sth went wrong
                        $result['message'] = "Error: Sth went wrong.";
                        break;
                    }
                } catch (PDOException $e) {
                    $result['success'] = false; //sth went wrong
                    $result['message'] = "Error: " . $e->getMessage();
                }
            }
        } else {
            $result['success'] = false; //No items selected in form
            $result['message'] = "Error: No projects selected!";
        }

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


    /** CAROUSEL ADMIN **/

    public function getCarouselGallery()
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.carousel WHERE included = 0");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getCarouselImages()
    {
        $stmt = $this->conn->prepare("SELECT * FROM fab.carousel WHERE included = 1 ORDER BY POSITION ASC ");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function includeInCarousel($id, $position)
    {
        $stmt = $this->conn->prepare("update fab.carousel set included = ?, POSITION = ? WHERE id = ? ");

        try {
            $stmt->bindValue(1, "1");
            $stmt->bindValue(2, $position);
            $stmt->bindValue(3, $id);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function notIncludeInCarousel($id)
    {
        $stmt = $this->conn->prepare("update fab.carousel set included = ?, POSITION = ? WHERE id = ? ");

        try {
            $stmt->bindValue(1, "0");
            $stmt->bindValue(2, null);
            $stmt->bindValue(3, $id);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function addCarouselImage($data, $imageName)
    {
        if (isset($data['include'])) {
            $included = $data['include'];
        } else {
            $included = 0;
        }

        if (isset($data['description']) && !empty($data['description'])) {
            $description = $data['description'];
        } else {
            $description = null;
        }

        $stmt = $this->conn->prepare("INSERT INTO fab.carousel (`name`, `included`, `position`,  `description`)
    VALUES (:name, :included, :position, :description)");
        $stmt->bindValue(':name', $imageName);
        $stmt->bindValue(':included', $included);
        $stmt->bindValue(':position', null);
        $stmt->bindValue(':description', $description);
        $result = $stmt->execute();

        return $result = $result == true ? $result = "" : $result = "Error inserting image into carousel database.";

    }

    public function deleteFromCarousel($id)
    {
        $stmt = $this->conn->prepare("delete from fab.carousel WHERE id = ? ");

        try {
            $stmt->bindValue(1, $id);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
        }
    }

}