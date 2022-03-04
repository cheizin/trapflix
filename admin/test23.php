<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //if its project risk creation
  if(isset($_POST['add-youtube-list']))
  {
    //$reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $textname = mysqli_real_escape_string($dbc,strip_tags($_POST['textname']));
    $video_description = mysqli_real_escape_string($dbc,strip_tags($_POST['video_description']));
  //  $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    $title = mysqli_real_escape_string($dbc,strip_tags($_POST['title']));

      $videoname = mysqli_real_escape_string($dbc,strip_tags($_POST['videoname']));

        $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));

          $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));

  //    $token = bin2hex(random_bytes(35));

  $email = $_SESSION['email'];



      $uploadDir = '../../images/favorite/';
      $uploadStatus = 1;
      $uploadedFile = '';
      if(!empty($_FILES["file"]["name"])){

          // File path config
          $fileName = basename($_FILES["file"]["name"]);
          $targetFilePath = $uploadDir . $fileName;
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

          // Allow certain file formats
          $allowTypes = array('JPEG', 'JPG', 'jpg','PNG', 'png', 'mp4','avi','mov', 'pdf');
          if(in_array($fileType, $allowTypes)){
              // Upload file to the server
              if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                  $uploadedFile = $fileName;
              }else{
                  $uploadStatus = 0;
                  exit('error-uploading');
              }
          }else{
              $uploadStatus = 1;
              exit('invalid-file');
          }
      }
          if($uploadStatus == 0)
          {
      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);
      $sql_insert_issue = mysqli_query($dbc,"INSERT INTO videos
                            (email, sort_id, textname,video_description, title, videoname, token, thumbnail)
                    VALUES
                            ('".$email."','".$reference_no."','".$textname."', '".$video_description."', '".$title."', '".$videoname."', '".$token."', '".$file."')
                   ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Added a client ".$customer_name;
    $action_name = "Client Creation";
    $action_icon = "fad fa-pennant text-info";
    $page_id = "customer-management-link";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                    (email,action_name,action_reference,action_icon,page_id,time_recorded)
                        VALUES
                ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
                 );

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

  }
  //end of add project risk

  else if(isset($_POST['edit_customer_list']))
{
  $id= mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
  $customer_name = mysqli_real_escape_string($dbc,strip_tags($_POST['customer_name']));
  $contact = mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));
  $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $sql_modify_lesson = "
                              UPDATE customer SET
                                      customer_name='$customer_name',
                                      contact='$contact',
                                      email='$email'
                                WHERE id='$id'
                            ";

    mysqli_query($dbc,$sql_modify_lesson);

  //log the action
  $action_reference = "Modified a customer".$customer_name;
  $action_name = "Customer Modification";
  $action_icon = "fal fa-chalkboard-teacher text-warning";
  $page_id = "Customer-tab";
  $time_recorded = date('Y/m/d H:i:s');

  $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                  (email,action_name,action_reference,action_icon,page_id,time_recorded)
                      VALUES
              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                      '".$action_icon."','".$page_id."','".$time_recorded."')"
               );

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
else if(isset($_POST['DeleteCustomer']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);


    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['sid']));
    $delete_lesson = mysqli_query($dbc,"DELETE FROM customer WHERE id='".$id."'");

    //log the action
    $action_reference = "Deleted the customer " . $id;
    $action_name = "Project Lesson Learnt Deletion";
    $action_icon = "fal fa-trash text-danger";
    $page_id = "project-lessons-learnt-tab";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                    (email,action_name,action_reference,action_icon,page_id,time_recorded)
                        VALUES
                ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
                 );

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


}

//END OF POST REQUEST


?>
