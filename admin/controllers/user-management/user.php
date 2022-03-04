<?php
require_once('../setup/connect.php');
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //collect form fields


    $password = mysqli_real_escape_string($dbc,strip_tags($_POST['password']));
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));

    //check for duplicate programme name before inserting values to database
    /*$sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM phpc_staff_users WHERE EmpNo='".$emp_no."'"));
    if($sql_check > 0)
        {
        exit("duplicate");
        }*/
  /*  $sql_statement = "INSERT INTO phpc_staff_users

                        (EmpNo, Name, Email,DepartmentCode,access_level, date_recorded,time_recorded)

                      VALUES
                          (MD5('".$EmpNo."'), '".$Name."', '".$Email."', '".$DepartmentCode."', '".$access_level."', '".$date_recorded."', '".$time_recorded ."')
                            ";
*/

        $sql_statement = "UPDATE staff_users SET
                                                password = MD5('".$password."')

                                                WHERE Email ='".$email."'";

    //check if query runs

    if($insert_query = mysqli_query($dbc,$sql_statement))
    {


        exit ("success");

    }
    else
    {
        exit ("failed");
    }
}

?>
