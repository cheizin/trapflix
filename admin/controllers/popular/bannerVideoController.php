<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{




  //start of edit project
  if(isset($_POST['edit_banner']))
  {

    // mysqli_real_escape_string($dbc,strip_tags($_POST['senior_user']));

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $video_id = mysqli_real_escape_string($dbc,strip_tags($_POST['video_id']));

    $date_recorded = date('d-M-y');
    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    //insert records to sql

        $sql_insert = mysqli_query($dbc,"UPDATE banner_videos SET video_id ='".$video_id."' WHERE id='".$id."'");

  //log the action
  $action_reference = "Modified the with the id " . $id;
  $action_name = "Project Modification";
  $action_icon = "far fa-project-diagram text-warning";
  $page_id = "monitor-projects-link";
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

  //end of edit project





}

//END OF POST REQUEST


?>
