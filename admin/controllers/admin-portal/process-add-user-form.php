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
    $created_by = $_SESSION['name'];
    $date_recorded = date("m/d/Y");
    $time_recorded = date("h:i:sa");



    //check for duplicate programme name before inserting values to database
    $sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM staff_users WHERE EmpNo='".$emp_no."' || Email='".$email."'"));
    if($sql_check > 0)
        {
        exit("duplicate");
        }
    $sql_statement = "INSERT INTO staff_users

                        (EmpNo, Name, Email,DepartmentCode,access_level, designation)

                      VALUES
                          ('".$emp_no."', '".$name."', '".$email."','".$department."',
                          '".$access_level."', '".$designation."')
                            ";

    //check if query runs

    $action_reference = "Added a new user" . $name . " " . $emp_no;
    $action_name = "New User";
    $action_icon = "far fa-user-plus";
    $page_id = "admin-user-management-link text-success";
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
        exit ("failed");
    }
}

?>
