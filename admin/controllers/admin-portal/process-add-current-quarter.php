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
    $current_period = mysqli_real_escape_string($dbc,strip_tags($_POST['current-period']));
    $current_quarter = mysqli_real_escape_string($dbc,strip_tags($_POST['current-quarter']));
    $specific_year = substr($current_period, strpos($current_period, "-") + 1);

    if($current_quarter == 'July - September (Quarter 1)')
    {
      $specific_month = '9' ;
      $specific_day = '30';
    }
    if($current_quarter == 'October - December (Quarter 2)')
    {
      $specific_month = '12';
      $specific_day = '31';
    }
    if($current_quarter == 'January - March (Quarter 3)')
    {
      $specific_month = '3';
      $specific_day = '31';
    }
    if($current_quarter == 'April - June (Quarter 4)')
    {
      $specific_month = '6';
      $specific_day = '30';
    }
    $created_by = $_SESSION['name'];
    $date_recorded = date("m/d/Y");
    $time_recorded = date("h:i:sa");

    //select linked programme, and fetch all its records
    //insert values to database
    $sql_statement = "INSERT INTO years

                        (period,quarter,specific_year,specific_month,specific_day,created_by,date_recorded,time_recorded)

                      VALUES
                          ('".$current_period."','".$current_quarter."','".$specific_year."','".$specific_month."','".$specific_day."','".$created_by."', '".$date_recorded."', '".$time_recorded."')
                        ";

    //check if query runs

    $action_reference = "Changed Current Quarter & Year to" . $current_period . " " . $current_quarter;
    $action_name = "Period Management";
    $action_icon = "fad fa-calendar-check";
    $page_id = "admin-period-management-link";
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
