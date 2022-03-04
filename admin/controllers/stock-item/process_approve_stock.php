<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $risk_description =  mysqli_real_escape_string($dbc,strip_tags($_POST['new_risk_description']));
    $risk_or_opportunity_notification = mysqli_real_escape_string($dbc,strip_tags($_POST['new_risk_or_opportunity_notification']));
    $risk_reference = mysqli_real_escape_string($dbc,strip_tags($_POST['new_reference_no']));
    $person_responsible = mysqli_real_escape_string($dbc,strip_tags($_POST['new_person_responsible']));
    $status = mysqli_real_escape_string($dbc,strip_tags($_POST['new_approval_value']));
  //  $comments = mysqli_real_escape_string($dbc,strip_tags($_POST['comments']));

    if($status == "approved")
    {
      $mail_status = "approved";
    }
    if($status == "pending approval")
    {
      $mail_status = "pending approval";
    }
    if($status == "rejected")
    {
      $mail_status = "rejected";
    }

    $sql_insert = " UPDATE stock_item SET status='".$status."' WHERE reference_no ='".$risk_reference."' ";
    $query_insert = mysqli_query($dbc,$sql_insert);

    //log the action
    $action_reference = $status. " Stock Item Approvals".$risk_reference;
    $action_name = "Stock Approvals";
    if($status == 'approved')
    {
      $action_icon = "far fa-check-circle text-success";
    }
    else
    {
      $action_icon = "fad fa-times-circle text-danger";
    }

    $page_id = "risk-approvals-link";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                          (email,action_name,action_reference,action_icon,page_id,time_recorded)
                          VALUES
                        ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
              );

    if($query_insert && $sql_log)
    {
      exit("success");
    }
    else
    {
      exit("failed");
    }


}


?>
