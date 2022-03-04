<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $activity_id = mysqli_real_escape_string($dbc,strip_tags($_POST['activity_id']));
  $risk_reference = mysqli_real_escape_string($dbc,strip_tags($_POST['risk_ref']));
  $directorate_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directorate_id']));
  //$year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['year_id']));
  //$quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['quarter_id']));
  $directors_risk_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directors_risk_id']));
  $created_by = $_SESSION['name'];

  echo $year_id;
  echo $quarter_id;

  mysqli_query($dbc,"START TRANSACTION");

  $check_period = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directors_risk_table WHERE directors_risk_id='".$directors_risk_id."'"));

  $year_id = $check_period['year_id'];
  $quarter_id = $check_period['quarter_id'];

  $delete = mysqli_query($dbc,"DELETE FROM directors_risk_table WHERE directors_risk_id='".$directors_risk_id."'
                                              && year_id='".$year_id."' && quarter_id='".$quarter_id."'");
  $update = mysqli_query($dbc,"UPDATE performance_update SET directorate_risk='no' WHERE activity_id='".$activity_id."'
                                    && year_id='".$year_id."' && quarter_id='".$quarter_id."'");
  if($delete && $update)
  {
    mysqli_query($dbc,"COMMIT");
    echo("success");
  }
  else
  {
    mysqli_query($dbc,"ROLLBACK");
    exit(mysqli_error($dbc));
  }


}


?>
