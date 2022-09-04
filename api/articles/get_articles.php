<?php 

// in this file we find article by their id (if id is null or 'all' return all articles)
// input is an int that contains id, and using get method.

// initialize databse
require_once('dbConnect.php');

$tableName='article';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id = $_GET['id'] ?? null;

    // if id is not assigned or value=all, return all articles.
    if ($id == null or $id == "all") {
        $sql = "SELECT * FROM $tableName ";
    }
    else {
        $sql = "SELECT * FROM $tableName WHERE id = '$id'";
    }

    try {
        $conn = new PDO(sprintf("mysql:host=%s;dbname=%s",HOST,DB_NAME),USERNAME,PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $conn->query("SET CHARACTER SET utf8");

        $result = $conn->query($sql);

        $articles = array();
        // output data of each row
        while($row = $result->fetch()){
            array_push($articles, array(

                "id" => $row['id'],
                "title" => $row['title'],
                "releaseDate" => $row['release_at'],
                "context" => $row['context'],
                "fileName" => $row["file_name"],
                "imageURL" => $row['image_url']
            ));
        }
        // check that there is a article with that id in database or not.
        // if yes, send it / otherwise send error massage.
        if(empty($articles)){
            $error_msg = json_encode(array("message" => "couldn't find any article" ,
                                        "status" => false));
            echo $error_msg;
        }
        else {
            echo json_encode($articles);
        }

    } catch (PDOException $value1) {
        echo $value1->getMessage();
    }
    $conn = null;

} else {
    $error_msg = json_encode(array("message" => "you are using wrong request method, please use 'GET' method. " ,
                                    "status" => false));
    echo $error_msg;
}
