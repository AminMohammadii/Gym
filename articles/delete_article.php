<?php

// in this file we try to delete a article from our database and 
// relation photo about that from server.

// only input is id and using GET method.

// initialize database
require_once('../dbConnect.php');

// use this file to delete specefic things that we want.
require_once('../delete_file.php');

$tableName = 'article';
$imageURL = "upload";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tmpID = $_GET['id'] ?? null;

    // check that user gives id, if not -> send error message
    if($tmpID != null){

        try {
            $conn = new PDO(sprintf("mysql:host=%s;dbname=%s",HOST,DB_NAME),USERNAME,PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $conn->query("SET CHARACTER SET utf8");



            $sql = "SELECT id,file_name FROM $tableName WHERE `$tableName`.`id` = '$tmpID'" ;
            $result = $conn->prepare($sql);
            $result ->execute();

            while($row = $result->fetch()){
                $fileName = $row['file_name'];
                $id = $row['id'];
            } 
            
            // check that we have a article with that id in database or not.
            if( isset($id)){

                $sql = "DELETE FROM `$tableName` WHERE `$tableName`.`id` = '$id'" ;
                $conn->exec($sql);
        
                // use this method from delete_file.php to delete article photo.
                $deleteResult = delete_file($imageURL,$fileName);
        
                echo json_encode(array("message" => "article deleted successfully, ". $deleteResult["deleteMessage"],
                                        "status" => true)); 

                }
                else {
                    $error_msg = json_encode(array("message" => "article did not found, so delete operation CANCELED" ,
                                                    "status" => false));
                    echo $error_msg ;
                }

        } catch (PDOException $value1) {
            echo $value1->getMessage();
        }

        $conn = null;

        }
    else {
        $error_msg = json_encode(array("message" => "id is not defiend, please enter article id" ,
                                                "status" => false));
            echo $error_msg;
    }

} else {
    $error_msg = json_encode(array("message" => "you are using wrong request method, please use 'GET' method. " ,
                                    "status" => false));
    echo $error_msg;
}

?>