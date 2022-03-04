<?php
require_once('../setup/connect.php');
session_start();
if(isset($_REQUEST["sid"]))
{
  $id = $_REQUEST["sid"];

  //start transaction
  mysqli_query($dbc,"START TRANSACTION");

  $check_period = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directors_risk_table WHERE directors_cumulative_id='".$id."'"));

  $year_id = $check_period["year_id"];
  $quarter_id = $check_period["quarter_id"];

  $update_performance = mysqli_query($dbc,"UPDATE performance_update SET directorate_risk='no' WHERE
                                                  activity_id IN(SELECT activity_id FROM directors_risk_table
                                                  WHERE directors_cumulative_id='".$id."')

                                                  && year_id='".$year_id."' && quarter_id='".$quarter_id."'");
  $delete_directors_cumulative_table = mysqli_query($dbc,"DELETE FROM directors_cumulative_table WHERE directors_cumulative_id='".$id."'");
  $delete_directors_risk_strategic_objective = mysqli_query($dbc,"DELETE FROM directors_risk_strategic_objective  WHERE directors_cumulative_id='".$id."'");
  $delete_directors_risk_table = mysqli_query($dbc,"DELETE FROM directors_risk_table WHERE directors_cumulative_id='".$id."'
                                                          && year_id='".$year_id."' && quarter_id='".$quarter_id."'");


    //$query1 = mysqli_query($dbc,$delete_directors_cumulative_table);
    //$query2 = mysqli_query($dbc,$delete_directors_risk_strategic_objective);
    //$query3 = mysqli_query($dbc,$delete_directors_risk_table);
    //$query4 = mysqli_query($dbc,$update_performance);

  if($delete_directors_cumulative_table && $delete_directors_risk_strategic_objective && $delete_directors_risk_table && $update_performance)
  {

    mysqli_query($dbc,"COMMIT");
    echo("success");
  }
  else
  {
    mysqli_query($dbc,"ROLLBACK");
    echo "error";
    //echo mysqli_error($dbc)
  }

}
else
{
  echo "no request";
}


?>
