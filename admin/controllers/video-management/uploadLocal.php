<?php
require_once('../setup/connect.php');
require_once('../middleware/RequestIsPost.php');
require_once('../middleware/UserIsAuthenticated.php');

  if(isset($_POST['upload-video']))
  {
    $textname = mysqli_real_escape_string($dbc,strip_tags($_POST['textname']));
    $video_description = mysqli_real_escape_string($dbc,strip_tags($_POST['video_description']));
    $title = mysqli_real_escape_string($dbc,strip_tags($_POST['title']));
    $videoname = mysqli_real_escape_string($dbc,strip_tags($_POST['videoname']));
    
       $youtube_vid = mysqli_real_escape_string($dbc,strip_tags($_POST['youtube_vid']));

    
    $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));
    $email = $_SESSION['email'];



        //start image compression
    function compressImage($source, $destination, $quality) { 
    // Get image info 
    $imgInfo = getimagesize($source); 
    $mime = $imgInfo['mime']; 
     
    // Create a new image from file 
    switch($mime){ 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            break; 
        default: 
            $image = imagecreatefromjpeg($source); 
    } 
     
    // Save image 
    imagejpeg($image, $destination, $quality); 
     
    // Return compressed image 
    return $destination; 
    } 
    
    
    //end image compression
    
    //start file upload
         //upload file
        $upload_dir = '../../../images/favoriteCompressed/';
         $uploadedFile = '';
         $path_parts = pathinfo($_FILES["thumbnail"]["name"]);
         $file_name = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
         $file_type = strtolower($_FILES['thumbnail']['type']);
         $file_size = $_FILES['thumbnail']['size'];
         $max_file_size =  10485760;
   
         $target_file_path = $upload_dir . $file_name;
 
         if($file_size > $max_file_size)
         {
            exit("big-file");
          }
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        //check file type]
    if (!in_array($extension, array('jpg', 'png', 'jpeg','jfif')))
        {
            exit("invalid-file");
        }
        
             //compress and upload
        
        $compressedImage = compressImage($_FILES["thumbnail"]["tmp_name"], $target_file_path, 50); //was 75 
        
        if(!$compressedImage)
        {
            exit("error-uploading");
        }

    /*
        //upload the file
        if(!move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file_path))
        {
            exit("error-uploading");
        }
        */
 
        $thumbnail = $file_name;   

    //start file upload
         //upload file
        $upload_dir = '../../../videos/';
         $uploadedFile = '';
         $path_parts = pathinfo($_FILES["videoname"]["name"]);
         $file_name = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
         $file_type = strtolower($_FILES['videoname']['type']);
         $file_size = $_FILES['thumbnail']['size'];
       $max_file_size =  2147483648;
   
         $target_file_path = $upload_dir . $file_name;
 
         if($file_size > $max_file_size)
         {
            exit("big-file");
          }
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        //check file type]
    if (!in_array($extension, array('mp4', 'mov', 'avi','flv', 'swf','webm','mov')))
        {
            exit("invalid-file");
        }
        
     //compress and upload
        
        $compressedImage = compressImage($_FILES["videoname"]["tmp_name"], $target_file_path, 50); //was 75 
        
        if(!$compressedImage)
        {
            exit("error-uploading");
        }

    /*
        //upload the file
        if(!move_uploaded_file($_FILES["videoname"]["tmp_name"], $target_file_path))
        {
            exit("error-uploading");
        }
        
        */
 
        $videoname = $file_name; 

    //end file upload


     /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);
     $sql_statement = mysqli_query($dbc,"INSERT INTO videos
                                                (email,textname,video_description, title, youtube_vid, videoname, token,thumbnail)
                                        VALUES
                                                ('".$email."','".$textname."', '".$video_description."', '".$title."','".$youtube_vid."', '".$videoname."', '".$token."','".$thumbnail."')
                                       ") or die (mysqli_error($dbc));
    
                                       if(mysqli_commit($dbc))
                                        {
                                          exit("success");
                                        }
                                        else
                                        {
                                          mysqli_rollback($dbc);
                                          exit("failed");
                                        }

  }





?>
