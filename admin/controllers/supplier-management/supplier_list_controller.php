<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //if its project risk creation
  if(isset($_POST['add-supplier-list']))
  {
    //$reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $supplier_name = mysqli_real_escape_string($dbc,strip_tags($_POST['supplier_name']));
    $contact = mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    $sector= mysqli_real_escape_string($dbc,strip_tags($_POST['sector']));

  $recorded_by = $_SESSION['name'];

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);
      $sql_insert_issue = mysqli_query($dbc,"INSERT INTO supplier
                            (supplier_name,contact, email, sector, recorded_by)
                    VALUES
                            ('".$supplier_name."', '".$contact."', '".$email."', '".$sector."', '".$recorded_by."')
                   ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Added a supplier ".$supplier_name;
    $action_name = "Supplier Creation";
    $action_icon = "fad fa-pennant text-info";
    $page_id = "supplier-management-link";
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
  //end of add project risk

  
  else if(isset($_POST['close_issue_log']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);


    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['sid']));
    $status = mysqli_real_escape_string($dbc,strip_tags($_POST['status']));

    $change_status = mysqli_query($dbc,"UPDATE pm_issue_logs SET status='$status' WHERE id='".$id."'");

    //log the action
    $action_reference = "Changed the status of the project issue log with the id " . $id. " to ".$status;
    $action_name = "Project Issue Log Status Change";
    $action_icon = "fal fa-exclamation-square text-info";
    $page_id = "project-issue-logs-tab";
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
  //end close project risk






}

//END OF POST REQUEST


?>
