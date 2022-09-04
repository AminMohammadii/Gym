<?php 
require_once("dbConnect.php");

$server = SERVER_IP;
$directory = FILES_DIR;

// this function try to delete file from the category.
function delete_file($file_name, $info=""){
    global $server,$directory;
    // check file_name is empty or not
    if(! ($file_name === "" or $file_name == null) ){

        $relDirectory = str_ireplace("/******", "../../..", $directory);

        $full_path = $relDirectory . $file_name;

        // check if file exist in directory or not
        if(file_exists($full_path)){
    
            // try to delete file
            if(unlink($full_path)){
                return array("deleteMessage" => "$info file deleted successfully",
                "deleteStatus" => true);                  
                  
            }else{
                return array("deleteMessage" => "file did not delete!",
                "deleteStatus" => false);
            }
        }
        else {
            return array("deleteMessage" => "could not find file '$file_name' in directory to delete",
            "deleteStatus" => false);
        }
    }
    else {
            return array("deleteMessage" => "file name did not found, delete operation canceled",
            "deleteStatus" => false);
    }

}

?>
