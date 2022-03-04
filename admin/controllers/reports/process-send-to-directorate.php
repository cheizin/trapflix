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
  $created_by = $_SESSION['name'];

  //create directors_risk id
   $select_last_id_sql = mysqli_query($dbc,"SELECT directors_risk_id,time_recorded FROM directors_risk_table ORDER BY
                                         time_recorded DESC LIMIT 1") or die(mysqli_error($dbc));
   $id_row = mysqli_fetch_array($select_last_id_sql);
   $id = $id_row['directors_risk_id'];
   $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
   $int = $int+1;
   $directors_risk_id = "DRI".$int;


  //insert into directors risk table

  $sql_insert = mysqli_query($dbc,"INSERT INTO directors_risk_table (directors_risk_id,risk_reference,
                                                                    activity_id,year_id,quarter_id,directorate_id,
                                                                      created_by)
                                                                      VALUES
                                                                      ('".$directors_risk_id."','".$risk_reference."',
                                                                        '".$activity_id."','".$year_id."','".$quarter_id."','".$directorate_id."',
                                                                        '".$created_by."')"
                                                                        )
                                                                        or die(mysqli_error($dbc));
    //update set directorate risk to true
    //$sql_update = mysqli_query($dbc,"UPDATE perfomance_management SET directorate_risk='yes' WHERE activity_id='".$activity_id."'") or die(mysqli_error($dbc));
    $sql_update = mysqli_query($dbc,"UPDATE performance_update SET directorate_risk='yes' WHERE activity_id='".$activity_id."'
                                                && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));

    if($sql_insert && $sql_update)
    {
      exit("success");
    }
    else
    {
      exit(mysqli_error($dbc));
    }


}


?>
