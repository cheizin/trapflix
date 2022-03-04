<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //start close channel

  if(isset($_POST['close_channel']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
     
     
     // an admin can delete any channel
     if(isset($_SESSION['access_level']))
     {
         if($_SESSION['access_level'] == 'admin')
         {
             mysqli_query($dbc,"DELETE FROM main_categories WHERE id ='".$id."'");
         }
     }
     
     
     // a standard user can only delete his/her channel
    if(isset($_SESSION['access_level']))
     {
         if($_SESSION['access_level'] == 'standard')
         {
             mysqli_query($dbc,"DELETE FROM main_categories WHERE id ='".$id."' && user_id='".$_SESSION['id']."'");
         }
     }

    


    //log the action
    $action_reference = "Deleted the Channel with id " . $id['id'];
    $action_name = "Project Deletion";
    $action_icon = "fal fa-trash-alt text-danger";
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
  //end close project


}

//END OF POST REQUEST


?>
