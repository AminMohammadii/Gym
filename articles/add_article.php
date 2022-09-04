<?php 

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

// in this file we add a new article
// using post method.


//initialize database/
require_once('../../dbConnect.php');

// use this file to upload our article image to server
require_once('../../upload_file.php');

/**
 * @OA\Post(path="/******/add_article.php",
    * tags={"Admin-Article"},
    * summary="Create a new article",
    * @OA\RequestBody(
        *    @OA\MediaType(
        *        mediaType="multipart/form-data",
        *        @OA\Schema(
        *            @OA\Property(
        *                property="title",
        *                type="string",
        *            ),
        *            @OA\Property(
        *                property="context",
        *                type="integer",
        *            ),
        *            @OA\Property(
        *                property="file",
        *                type="file",
        *            ),
        *        ),
        *    ),
        * ),
 * @OA\Response(response="200", description="Success!!!"),
 * @OA\Response(response="404", description="Not Found!!!")
 * )
 */

$tableName='article';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'] ?? null;
    $context = $_POST['context'] ?? null;

    // cause title and context are essential, we check that they aren't empty
    if($title != null and $context != null){

        try {
            $conn = new PDO(sprintf("mysql:host=%s;dbname=%s",HOST,DB_NAME),USERNAME,PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $conn->query("SET CHARACTER SET utf8");


            // if we have a file we set file_name value.
            $uploadResult = upload_file($_FILES['file']['name'] ?? null,
                                        $_FILES['file']['tmp_name'] ?? null,
                                        $_FILES['file']['size'] ?? null);

            // if we have an image and upload susseccfully to server,
            // upload_file will return true result,
            // then we set fileName value 
            if($uploadResult["status"]){
                $fileName = $uploadResult["fileName"];
                }
            else {
                $fileName = "";
            }


            // command to mysql to create new food
            $sql = "INSERT INTO $tableName(title,context,file_name) 
                VALUES ('$title','$context','$fileName')";
            
            $result = $conn->prepare($sql);
            $result->execute();

            echo json_encode(array("message" => "article added sussessfully, ". $uploadResult["message"],
                                    "satus" => true));


        } catch (PDOException $value1) {
            echo $value1->getMessage();
        }

        $conn = null;

    }
    else {
        $error_msg = json_encode(array("message" => "title or context is not defiend" ,
                                                "status" => false));
            echo $error_msg;
    }

}
else {
    $error_msg = json_encode(array("message" => "you are using wrong request method, please use 'POST' method. " ,
                                    "status" => false));
    echo $error_msg;
}

?>