<?php 

// this function try to delete file from the category.
function delete_file($path, $file_name, $info=""){

    // check file_name is empty or not
    if(! ($file_name === "" or $file_name == null) ){

        $full_path = $path . "/" . $file_name; // ex : movies/سرشانه.png


        // check if file exist in directory or not
        if(file_exists($full_path)){
    
            // try to delete file
            if(unlink($full_path)){
                // echo json_encode(array("message" => "$info file deleted successfully" ,
                                                // "status" => true)) ;  
                return array("deleteMessage" => "$info file deleted successfully",
                "deleteStatus" => true);                  
                  
            }else{
                // $error_msg = json_encode(array("message" => "file did not delete!" ,
                //                                 "status" => false)) ;  
                // echo $error_msg ;
                return array("deleteMessage" => "file did not delete!",
                "deleteStatus" => false);
            }
        }
        else {
            // $error_msg = json_encode(array("message" => "file '$file_name' did not found in '$path' directory" ,
            //                                 "status" => false)) ;
            // echo $error_msg ;
            return array("deleteMessage" => "file '$file_name' did not found in directory",
            "deleteStatus" => false);
        }
    }
    else {
        // $error_msg = json_encode(array("message" => "file name did not found, so no file has deleted" ,
        //                                     "status" => false)) ;
        //     echo $error_msg ;
            return array("deleteMessage" => "file name did not found, so no file has deleted",
            "deleteStatus" => false);
    }


}





?>