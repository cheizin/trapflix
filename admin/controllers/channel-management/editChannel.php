<?php
require_once('../setup/connect.php');
session_start();

  //end of add project

  //start of edit project
  if(isset($_POST['reorder_channel']))
  {

    // mysqli_real_escape_string($dbc,strip_tags($_POST['senior_user']));

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

    $channel = mysqli_real_escape_string($dbc,strip_tags($_POST['channel']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    //insert records to sql
    $sql_insert = "UPDATE main_categories SET

    order_id ='$channel'

    WHERE id ='$id'
    ";

    $insert_project = mysqli_query($dbc,$sql_insert) or die (mysqli_error($dbc));



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




?>
