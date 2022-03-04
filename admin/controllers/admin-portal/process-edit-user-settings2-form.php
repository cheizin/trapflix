<?php
include("../../controllers/setup/connect.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if($_SESSION['access_level']!='admin')
    {
        exit("unauthorized");
    }
    //collect form fields
    $emp_no = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_no']));
    $name = mysqli_real_escape_string($dbc,strip_tags($_POST['name']));
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    $access_level = mysqli_real_escape_string($dbc,strip_tags($_POST['access_level']));
    $department = mysqli_real_escape_string($dbc,strip_tags($_POST['department']));
    $designation = mysqli_real_escape_string($dbc,strip_tags($_POST['designation']));
    $status = mysqli_real_escape_string($dbc,strip_tags($_POST['status']));

    //$photoss= $_FILES['emp_photo']['name'];
    $created_by = $_SESSION['name'];
    $date_recorded = date("m/d/Y");
    $time_recorded = date("h:i:sa");

    $sql_statement = "UPDATE staff_users SET
                        EmpNo='".$emp_no."', Name= '".$name."', Email='".$email."',
                         DepartmentCode='".$department."',
                        access_level='".$access_level."', designation='".$designation."',
                        status='".$status."'

                        WHERE EmpNo='".$emp_no."'
                        ";


    //check if query runs

    $action_reference = "Modified user information " . $name . " " . $emp_no;
    $action_name = "User Modification";
    $action_icon = "fal fa-user-alt";
    $page_id = "admin-user-management-link";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                    (email,action_name,action_reference,action_icon,page_id,time_recorded)
                        VALUES
                ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
                 );

    if($insert_query = mysqli_query($dbc,$sql_statement) && $sql_log)
    {
        exit ("success");
    }
    else
    {
        exit (mysqli_error($dbc));
      }
}



?>
