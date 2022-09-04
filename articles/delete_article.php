<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

// in this file we try to delete a article from our database and 
// relation photo about that from server.

// only input is id and using GET method.

// initialize database
require_once('../../dbConnect.php');

// use this file to delete specefic things that we want.
require_once('../../delete_file.php');

/**
 * @OA\Delete(path="/******/delete_article.php",
     * tags={"Admin-Article"},
     * summary="delete article by id",
     * @OA\Parameter(
     *    name="id",
     *    in="query",
     *    required=true,
     *    description="delete the intended workout",
     *    @OA\Schema(
     *       type="integer"
     *    ),
     * ),
 * @OA\Response(response="200", description="Success!!!"),
 * @OA\Response(response="404", description="Not Found!!!")
 * )
 */

$tableName = 'article';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

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
                $deleteResult = delete_file($fileName);
        
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
    $error_msg = json_encode(array("message" => "you are using wrong request method, please use 'DELETE' method. " ,
                                    "status" => false));
    echo $error_msg;
}

?>