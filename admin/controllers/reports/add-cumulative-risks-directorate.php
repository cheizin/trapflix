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

  $select_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['select_directorate']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['year_id']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['quarter_id']));
  $risk_opportunity = mysqli_real_escape_string($dbc,strip_tags($_POST['risk_opportunity']));
  $created_by = $_SESSION['name'];

  //create directors_risk id
   $select_last_id_sql = mysqli_query($dbc,"SELECT directors_cumulative_id,time_recorded FROM directors_cumulative_table
                                            WHERE directorate_id='".$select_directorate."' && risk_opportunity='".$risk_opportunity."' ORDER BY
                                         time_recorded DESC LIMIT 1") or die(mysqli_error($dbc));
   $id_row = mysqli_fetch_array($select_last_id_sql);
   $id = $id_row['directors_cumulative_id'];
   $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
   $int = $int+1;

   $directory_prefix = $select_directorate;
   if($directory_prefix == "CES OFFICE")
   {
     $directory_prefix = "CE";
   }
   if($risk_opportunity == "opportunity")
   {
     $directory_prefix = $directory_prefix."/O"."/";
   }
   $directors_cumulative_id = $directory_prefix.$int;


  //insert into directors cumulative table

  $sql_insert = mysqli_query($dbc,"INSERT INTO directors_cumulative_table (directors_cumulative_id,cumulative_risk_score,risk_opportunity,
                                                                    likelihood_score,impact_score,overall_score,
                                                                    cumulative_activity_score,cumulative_risk_description,
                                                                    cumulative_activity_description,expected_cumulative_outcomes,
                                                                    directorate_id,year_id,quarter_id,created_by)
                                                                      VALUES
                                                                      ('".$directors_cumulative_id."','".$cumulative_risk_score."','".$risk_opportunity."',
                                                                        '".$impact_score."','".$likelihood_score."','".$overall_score."',
                                                                        '".$cumulative_activity_score."','".$cumulative_risk_description."',
                                                                        '".$cumulative_activity_description."','".$cumulative_outcome_description."',
                                                                        '".$select_directorate."','".$year_id."','".$quarter_id."',
                                                                        '".$created_by."')"
                                                                        )
                                                                        or die(mysqli_error($dbc));

      foreach ($_POST['directors_strategic_objective_id'] as $selectedOption)
              {

                $select_last_id_sql = mysqli_query($dbc,"SELECT directors_strategic_objective_risk_id,time_created FROM directors_risk_strategic_objective ORDER BY
                                                        time_created DESC LIMIT 1") or die(mysqli_error($dbc));
                $id_row = mysqli_fetch_array($select_last_id_sql);
                $id = $id_row['directors_strategic_objective_risk_id'];
                $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
                $int = $int+1;

                $directors_strategic_objective_risk_id = "DSR".$int;
                $query = mysqli_query($dbc,"INSERT INTO directors_risk_strategic_objective (directors_strategic_objective_risk_id,
                                                          directors_cumulative_id,strategic_objective_id)
                                            VALUES
                                              ('".$directors_strategic_objective_risk_id."','".$directors_cumulative_id."','".$selectedOption."')
                                                "
                                                )
                                       or die (mysqli_error($dbc));

              }
    //update directors_risk_table
    $sql_update = mysqli_query($dbc,"UPDATE directors_risk_table SET directors_cumulative_id='".$directors_cumulative_id."'
                                      WHERE directors_cumulative_id IS NULL && directorate_id='".$select_directorate."'");

    if($sql_insert && $sql_update && $query)
    {
      exit("success");
    }
    else
    {
      exit(mysqli_error($dbc));
    }


}


?>
