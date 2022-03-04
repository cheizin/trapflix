<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{


  //if its project risk creation
  if(isset($_POST['add-end-product']))
  {
    //$reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
    $unit_price = mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
    $qtt = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
    $total= mysqli_real_escape_string($dbc,strip_tags($_POST['total']));
      $customer_id = mysqli_real_escape_string($dbc,strip_tags($_POST['customer_id']));
    $product_start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_start_date']));
    $product_end_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_end_date']));
    $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));


    $date_recorded = date('d-M-yy');

  $recorded_by = $_SESSION['name'];



      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);
      $sql_insert_issue = mysqli_query($dbc,"INSERT INTO end_product
                            (product_name,unit_price, qtt, total, customer_id, start_date, end_date, duration, date_recorded, recorded_by)
                    VALUES
                            ('".$product_name."', '".$unit_price."', '".$qtt."', '".$total."', '".$customer_id ."',
                             '".$product_start_date."','".$product_end_date."', '".$duration."', '".$date_recorded."', '".$recorded_by."')
                   ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Added an End Product".$product_name;
    $action_name = "End Product Creation";
    $action_icon = "fad fa-pennant text-info";
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
  //end of add project risk

  //start of monitor project risk
  else if(isset($_POST['edit_project_issue_log']))
  {
    $issue_id = mysqli_real_escape_string($dbc,strip_tags($_POST['issue_id']));
    $project_id = mysqli_real_escape_string($dbc,strip_tags($_POST['project_id']));
    $issue_description = mysqli_real_escape_string($dbc,strip_tags($_POST['project_issue_description']));
    $issue_type = mysqli_real_escape_string($dbc,strip_tags($_POST['issue_type']));
    $raised_by= mysqli_real_escape_string($dbc,strip_tags($_POST['raised_by']));
    $issue_date_raised = mysqli_real_escape_string($dbc,strip_tags($_POST['issue_date_raised']));
    $issue_due_date = mysqli_real_escape_string($dbc,strip_tags($_POST['issue_due_date']));
    $priority = mysqli_real_escape_string($dbc,strip_tags($_POST['priority']));
    $severity = mysqli_real_escape_string($dbc,strip_tags($_POST['severity']));
    $next_action = mysqli_real_escape_string($dbc,strip_tags($_POST['next_action']));
    $person_responsible = mysqli_real_escape_string($dbc,strip_tags($_POST['person_responsible_for_next_action']));

    $todays_date = date('d-M-yy');
    $recorded_by = $_SESSION['name'];

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      $sql_insert_issue_update = "
                                INSERT INTO pm_issue_logs_updates
                                        (issue_id,project_id,issue_description,issue_type,date_raised,
                                        due_date,raised_by,issue_author,
                                        priority,severity,next_action, person_responsible)
                                VALUES
                                        ('".$issue_id."', '".$project_id."','".$issue_description."','".$issue_type."','".$issue_date_raised."',
                                        '".$issue_due_date."','".$raised_by."','".$recorded_by."',
                                        '".$priority."','".$severity."','".$next_action."',
                                        '".$person_responsible."')
                              ";
      mysqli_query($dbc,$sql_insert_issue_update);

    //log the action
    $action_reference = "Updated the issue log with id: " . $issue_id . " for the project id ".$project_id;
    $action_name = "Project Issue Log Update";
    $action_icon = "fad fa-pennant text-info";
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

  //end of monitor project risk

  //start close project risk

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
