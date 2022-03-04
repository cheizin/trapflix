<?php
session_start();
require_once('../setup/connect.php');
$time_signed_out  = date('Y/m/d H:i:s');
$sql = "UPDATE sign_in_logs
            SET
            time_signed_out='".$time_signed_out."'
            WHERE email='".$_SESSION['email']."'
            && time_signed_in='".$_SESSION['time_signed_in']."'";


if(isset($_SESSION['test_user']) == true)
{


  $query = mysqli_query($dbc,$sql);
  if($query)
  {
      if(session_destroy())
      {
        echo "success";
      }
      else
      {
        echo("failed");
      }

  }
  else
  {
      echo mysqli_error($dbc);
  }
}
else
{
  //insert into activity logs
  $action_reference = "Logged out of the system";
  $action_name = "Logged out";
  $action_icon = "far fa-sign-out text-danger";
  $page_id = "log-out-link";
  $time_recorded = $time_signed_out;

  $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                  (email,action_name,action_reference,action_icon,page_id,time_recorded)
                      VALUES
              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                      '".$action_icon."','".$page_id."','".$time_recorded."')"
               );

  $query = mysqli_query($dbc,$sql);
  if($query && $sql_log)
  {
      if(session_destroy())
      {
        echo "success";
      }
      else
      {
        echo("failed");
      }

  }
  else
  {
      echo mysqli_error($dbc);
  }
}



?>
