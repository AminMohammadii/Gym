<?php 
// this file help us to upload a file to our server with specefic conditions.



    // this is where we set the maximum size of fie that allowed to upload.
$maximum_file_size = 20000000 ;

    // this is where we set the format of files that allowed to upload.
$valid_formats = array("jpg", "jpeg", "png") ;




    // with this method we upload the file to server.
function upload_file($file_name, $temp_name, $file_size, $dir){

        //check that file name is not empty pr null
    if(!($file_name === "" or $file_name === null
        or $dir === "" or $dir === null)){

        $full_path = $dir . "/" . $file_name ; // ex : uploads/mypictue.png

            // check that format is allowed or not.
        if(check_format($file_name )){

                // check that file is not already exist in server.
            if(!file_exists($full_path)){

                global $maximum_file_size ;

                    // check file size is less than allowed maximum file size.
                if($file_size < $maximum_file_size ){
                    
                        // check that file uploaded sussesscully to server .
                    if( move_uploaded_file($temp_name, $full_path) ){
                        // echo json_encode(array("massage" => "file uploaded successfully",
                        // "status" => true)) ;
                        return array("message" => "file uploaded successfully",
                        "status" => true);
                    }
                    else {
                        // $error_msg = json_encode(array("message" => "file did not uploaded successfully",
                        //                                 "status" => false)) ;
                        // echo $error_msg ;
                        return array("message" => "file did not uploaded successfully",
                        "status" => false);
                    }
                }
                else {
                    // $error_msg = json_encode(array("message" => "file is too large, upload under 20 MB size",
                    //                         "status" => false)) ;
                    // echo $error_msg ;

                    return array("message" => "file did not uploaded successfully",
                    "status" => false) ;
                }
            }
            else {
                // $error_msg = json_encode(array("message" => "file already exists, please check directory",
                //                             "status" => false)) ;
                // echo $error_msg ;
                return array("message" => "file already exists, please check directory",
                "status" => false) ;
            }
        }
        else {
            // $error_msg = json_encode(array("message" => "format of the file is not accaepable",
            //                                 "status" => false)) ;
            // echo $error_msg ;
            return array("message" => "format of the file is not accaepable",
            "status" => false) ;
        }
    }
    else {
        // $error_msg = json_encode(array("message" => "file_name or directory is not defiend, upload has failed",
        //                                 "status" => false)) ;
        // echo $error_msg ;
        return array("message" => "file_name or directory is not defiend, upload has failed",
        "status" => false) ;
    }
}
    // check that file format is in allowed format , if yes return true otherwise return false.
function check_format($file_name){
    
    global $valid_formats ;
    $file_format = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if(in_array($file_format, $valid_formats)){
        return true ;
    }
    return false ;
}





?>