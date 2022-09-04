<?php 

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

// initialize databse
require_once('../../dbConnect.php');

/**
 * @OA\Get(path="/******/get_articles.php",
     * tags={"Admin-Article"},
     * summary="Get articles by id (if id is null or 'all' return all articles)",
     * @OA\Parameter(
     *    name="id",
     *    in="query",
     *    required=false,
     *    description="Only workouts with this category name will return",
     *    @OA\Schema(
     *       type="string"
     *    ),
     * ),
 * @OA\Response(response="200", description="Success!!!"),
 * @OA\Response(response="404", description="Not Found!!!")
 * )
 */

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
                "image_url" => SERVER_IP . FILES_DIR
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
