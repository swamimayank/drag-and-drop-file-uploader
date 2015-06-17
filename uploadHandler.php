<?php

$uploa_dir = "uploads/";

if (!empty($_FILES)  ) {
    $access = FALSE;
    $file_name = $_FILES['file']['name'];

    $image_extantion = pathinfo($file_name, PATHINFO_EXTENSION);
    
    /* You can choose file extansion you want to allow */
    $whitelist = array(".zip",".jpeg", ".jpg", ".png");

    foreach ($whitelist as $item) {

        if (preg_match("/$item\$/i", $file_name)) {
            $new_file_name = sha1(alphanumeric_token(4)) . "-" . time();
            $access = True;
            $uploadfile = $uploa_dir . $new_file_name . "." . $image_extantion;
            

        }
    }

       if ($access) {

        if ( getimagesize($_FILES['file']['tmp_name']) || !file_exists($uploadfile)) {

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                 
                if (file_exists($uploadfile)  ) {
                     /** you can make database query here and to upload information about file like *
                       *
                       * $query = $db->prepare("INSERT INTO album_images(image_name, image_album_id) VALUE (:imageName,:albumId)");
                       * $query->execute(array(":imageName" => $imageName, ":albumId" => $ablumId));
                       *  ...                                                                   
                       */
                     

                     logger ("successful uploaded " .$file_name."-----".$uploadfile);
                }
            }else{
                logger("error".$file_name." not moved");
            }
        }else{
            logger("error ".$file_name."not found {$_FILES["file"]["error"]}-----".implode($_FILES["file"]) ."--------file exists..".file_exists($uploadfile)."---".implode(getimagesize($_FILES['file']['tmp_name'])));
        }
    }else{
        logger("error ".$file_name." access not {$access}");
    }


}

function logger($str = ''){
    $file = 'log.txt';
    $current = file_get_contents($file);
    file_put_contents($file, $str.PHP_EOL , FILE_APPEND);
}


function alphanumeric_token($length=5){
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $string;
}