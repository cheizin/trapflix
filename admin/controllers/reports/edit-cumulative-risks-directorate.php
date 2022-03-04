<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $cumulative_risk_description = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_risk_description']));
  $cumulative_activity_description = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_activity_description']));
  $cumulative_risk_score = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_risk_score']));

  $impact_score = mysqli_real_escape_string($dbc,strip_tags($_POST['impact_score']));
  $likelihood_score = mysqli_real_escape_string($dbc,strip_tags($_POST['likelihood_score']));
  $overall_score = mysqli_real_escape_string($dbc,strip_tags($_POST['overall_score']));

  $cumulative_activity_score = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_activity_score']));
  $cumulative_outcome_description = mysqli_real_escape_string($dbc,strip_tags($_POST['cumulative_outcome_description']));
  $directors_cumulative_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directors_cumulative_id']));

  $select_directorate= mysqli_real_escape_string($dbc,strip_tags($_POST['directorate_id']));

  $year_id= mysqli_real_escape_string($dbc,strip_tags($_POST['year_id']));
  $quarter_id= mysqli_real_escape_string($dbc,strip_tags($_POST['quarter_id']));

  $created_by = $_SESSION['name'];

  //start transaction
  mysqli_query($dbc,"START TRANSACTION");

  $sql_insert = mysqli_query($dbc,"UPDATE directors_cumulative_table SET
                        cumulative_risk_score='".$cumulative_risk_score."',
                        likelihood_score='".$likelihood_score."',
                        impact_score='".$impact_score."',
                        overall_score='".$overall_score."',
                        cumulative_activity_score='".$cumulative_activity_score."',
                        cumulative_risk_description='".$cumulative_risk_description."',
                        cumulative_activity_description='".$cumulative_activity_description."',
                        expected_cumulative_outcomes='".$cumulative_outcome_description."',
                        created_by='".$created_by."'

                WHERE directors_cumulative_id='".$directors_cumulative_id."'
                && year_id='".$year_id."'
                && quarter_id='".$quarter_id."'

              ") or die (mysqli_error($dbc));



    if($sql_insert)
    {
      mysqli_query($dbc,"COMMIT");
      echo("success");
    }
    else
    {
      mysqli_query($dbc,"ROLLBACK");
      echo(mysqli_error($dbc));
    }
}


?>
