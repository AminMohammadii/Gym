<?php 

require_once("dbConnect.php");

$server = SERVER_IP;
$directory = FILES_DIR;

    // this is where we set the maximum size of fie that allowed to upload.
$maximum_file_size = 20000000 ;

    // this is where we set the format of files that allowed to upload.
$valid_photo_formats = array("jpg", "jpeg", "png");
$valid__video_formats = array("mp4");
$valid_formats = array_merge($valid__video_formats,$valid_photo_formats);

    // with this method we upload the file to server.
function upload_file($file_name, $temp_name, $file_size){
    global $directory,$server;

    $fileError = check_file_error($file_name,$file_size);
    if($fileError != null){
        return $fileError;
    }

    $relDirectory = str_ireplace("/******", "../../..", $directory);

    $fileFormat = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $newFileName = gen_uuid() . "." . $fileFormat;

    $full_path = $relDirectory . $newFileName;

    if( move_uploaded_file($temp_name, $full_path) ){

        return array("message" => "file uploaded successfully"
                    ,"fileName" => $newFileName
                    ,"status" => true);
    }
    else {
        return array("message" => "file did not uploaded successfully",
        "status" => false);
    }

}    


function gen_uuid($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


function check_file_error($fileName, $fileSize){
    global $maximum_file_size, $valid_formats;

    //check that file name is not empty pr null
    if($fileName === "" or $fileName === null){
        return array("message" => "file_name is not defiend, upload has failed",
                        "status" => false);
    }

                // check file size is less than allowed maximum file size.
    if($fileSize > $maximum_file_size ){
        return array("message" => "file size is too big to upload!(Max:20 MB)",
        "status" => false);
    }

    // check that file format is in allowed format , if yes return true otherwise return false.
    $fileFormat = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if(!(in_array($fileFormat, $valid_formats))){
        return array("message" => "format of the file is not acceptable",
        "status" => false);
    }

    return false;

}

?>
