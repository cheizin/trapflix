<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $activity_id = mysqli_real_escape_string($dbc,strip_tags($_POST['activity_id']));
  $risk_reference = mysqli_real_escape_string($dbc,strip_tags($_POST['risk_ref']));
  $directorate_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directorate_id']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['year_id']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['quarter_id']));
  $directors_risk_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directors_risk_id']));
  $directors_cumulative_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directors_cumulative_id']));
  $cumulative_risk_score = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_risk_score']));
  $cumulative_activity_score = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_activity_score']));
  $created_by = $_SESSION['name'];
echo $cumulative_risk_score;

  $delete = mysqli_query($dbc,"DELETE FROM directors_risk_table WHERE directors_risk_id=$directors_risk_id");
  $update = mysqli_query($dbc,"UPDATE directors_cumulative_table SET cumulative_risk_score=$cumulative_risk_score,
                                cumulative_activity_score=$cumulative_activity_score
                                WHERE directors_cumulative_id=$directors_cumulative_id");
  if($delete && $update)
  {
    exit("success");
  }
  else
  {
    echo mysqli_error($dbc));
  }


}


?>
