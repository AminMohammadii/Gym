<?php 

// in this file we add a new article
// using post method.


//initialize database/
require_once('../dbConnect.php');

// use this file to upload our article image to server
require_once('../upload_file.php');

$tableName='article';
$imageURL = "upload";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // cause title and context are essential, we check that they aren't empty
    if(isset($_POST['title'],$_POST['context'])){

        try {
            $conn = new PDO(sprintf("mysql:host=%s;dbname=%s",HOST,DB_NAME),USERNAME,PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $conn->query("SET CHARACTER SET utf8");


            $title = $_POST['title'];
            $context = $_POST['context'];

            $uploadResult = upload_file($_FILES['file']['name'] ?? null,
                            $_FILES['file']['tmp_name'] ?? null,
                            $_FILES['file']['size'] ?? null, $imageURL);

            // if we have an image and upload susseccfully to server,
            // upload_file will return true result,
            // then we set fileName value 
            if($uploadResult["status"]){
                $fileName = $_FILES['file']['name'];
            }
            else {
                $fileName = "";
                $imageURL = "";
            }


            // command to mysql to create new food
            $sql = "INSERT INTO $tableName(title,context,file_name,image_url) 
                VALUES ('$title','$context','$fileName','$imageURL')";
            
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