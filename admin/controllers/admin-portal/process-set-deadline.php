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
    $deadline = mysqli_real_escape_string($dbc,strip_tags($_POST['deadline']));
    $created_by = $_SESSION['name'];


    //start transaction
    mysqli_query($dbc,"START TRANSACTION");

    $years_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM years ORDER BY id DESC LIMIT 1"));
    $period = $years_row['period'];
    $quarter = $years_row['quarter'];




    $sql_statement = "INSERT INTO deadline

                        (deadline, period, quarter,created_by)

                      VALUES
                          ('".$deadline."', '".$period."', '".$quarter."', '".$created_by."')
                            ";

    //check if query runs

    $action_reference = "Set a deadline for quarter submissions" . $deadline . " " . $period;
    $action_name = "Deadline";
    $action_icon = "fal fa-calendar-times";
    $page_id = "admin-period-management-link";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                    (email,action_name,action_reference,action_icon,page_id,time_recorded)
                        VALUES
                ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
                 );

    $insert_query = mysqli_query($dbc,$sql_statement);

    if($insert_query && $sql_log)
    {
      mysqli_query($dbc,"COMMIT");
      echo "success";
    }
    else
    {
      mysqli_query($dbc,"ROLLBACK");
      echo mysqli_error($dbc);
    }

}

?>
