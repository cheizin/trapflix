<?php
session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }
  //for superuser
$select_period = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$select_quarter = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$year_id= mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$select_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['directorates']));

?>

<!-- start corporate activities with corporate risks -->
<?php
$sql = mysqli_query($dbc,
                        "SELECT * FROM directors_cumulative_table WHERE
                                    year_id='".$select_period."' &&
                                    quarter_id='".$select_quarter."' &&
                                    directorate_id='".$select_directorate."'
                                    ORDER BY overall_score DESC

                           "
                        );
if($sql)
{
  $total_rows = mysqli_num_rows($sql);
  if($total_rows > 0)
  {



  ?>
  <input type="hidden" value="<?php echo $select_period;?>" name="year_id">
  <input type="hidden" value="<?php echo $select_quarter;?>" name="quarter_id">

 <!-- start departmental activities with  risks -->
 <div class="card">
  <div class="card-header with-border">
    <h3 class="card-title">Cumulative Risks:<br/><b>
         </b>
    </h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body table-responsive no-padding">
       <table class="table table-striped table-bordered table-hover" id="detailed_corporate_activities_related_risks_table" width="100%" style="overflow:hidden;" autosize="1">
         <thead>
           <tr>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="30">No</td>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="30">Edit</td>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="30">Delete</td>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="250">Strategic Objective</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Cumulative Risk</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Cumulative Activity</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Expected Cumulative Outcome</td>
           </tr>
         </thead>
         <?php
         $number = 1;
         while($row = mysqli_fetch_array($sql))
         {
           ?>
             <tr>
               <td><p class="activity-font-directorate"><?php echo $number++ ;?></p></td>
               <td>
                     <?php
                     $sql_directors_directorate = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directorates WHERE
                                                   director_id = '".$_SESSION['staff_id']."' "));

                     $directors_directorate = $sql_directors_directorate['directorate_id'];
                     $directors_name_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directorates WHERE
                                                                 directorate_id='".$select_directorate."'"));
                     $directors_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users
                                                              WHERE EmpNo='".$directors_name_row['director_id']."'"));

                     $directors_delegation_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM delegations WHERE
                                                           delegated_from_name='".$directors_id['Name']."'
                                                           && delegated_to_name='".$_SESSION['name']."'
                                                           && status='active'"));
                     $directors_delegation = $directors_delegation_row['delegated_to_name'];

                     if( $directors_directorate == $select_directorate || $_SESSION['access_level'] == 'superuser'  || $_SESSION['access_level'] == 'admin' || $directors_delegation == $_SESSION['name'])
                         {
                           ?>
                           <form method="post" id="edit-cumulative-risk-<?php echo $row['directors_cumulative_id'];?>">
                               <input type="hidden" value="<?php echo $row['directors_cumulative_id'];?>" name="directors_cumulative_id">
                               <input type="hidden" value="<?php echo $row['directorate_id'];?>" name="directorate_id">
                               <input type="hidden" value="<?php echo $select_period;?>" name="year_id">
                               <input type="hidden" value="<?php echo $select_quarter;?>" name="quarter_id">
                               <button type="submit" class="btn btn-warning" title="Click to Edit"
                                        onclick="EditCumulativeRisk('<?php echo $row['directors_cumulative_id'];?>');"><i class="fa fa-edit"></i>
                                </button>
                           </form>
                           <?php
                         }
                         else
                         {
                           ?>
                               <button disabled type="button" class="btn btn-success" title="YOU HAVE TO BE THE DIRECTOR FOR <?php echo $select_directorate;?> TO EDIT"><i class="fa fa-edit"></i></button>
                           <?php
                         }


                      ?>
               </td>
               <td>
                     <?php
                     $sql_directors_directorate = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directorates WHERE
                                                   director_id = '".$_SESSION['staff_id']."' "));

                     $directors_directorate = $sql_directors_directorate['directorate_id'];
                     $directors_name_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directorates WHERE
                                                                 directorate_id='".$select_directorate."'"));
                     $directors_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users
                                                              WHERE EmpNo='".$directors_name_row['director_id']."'"));

                     $directors_delegation_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM delegations WHERE
                                                           delegated_from_name='".$directors_id['Name']."'
                                                           && delegated_to_name='".$_SESSION['name']."'
                                                           && status='active'"));
                     $directors_delegation = $directors_delegation_row['delegated_to_name'];

                     if( $directors_directorate == $select_directorate || $_SESSION['access_level'] == 'superuser'  || $_SESSION['access_level'] == 'admin' || $directors_delegation == $_SESSION['name'])
                         {
                           ?>
                               <button  class="btn btn-danger" onclick="deleteCumulativeRisk('<?php echo $row['directors_cumulative_id'];?>');"><i class="fa fa-trash"></i></button>


                           <?php
                         }
                         else
                         {
                           ?>
                               <button disabled type="button" class="btn btn-danger" title="YOU HAVE TO BE THE DIRECTOR FOR <?php echo $select_directorate;?> TO DELETE"><i class="fa fa-trash"></i></button>
                           <?php
                         }


                      ?>
               </td>
               <td>
                  <?php
                        $directors_activity = mysqli_query($dbc,"SELECT * FROM directors_risk_strategic_objective WHERE
                                                              directors_cumulative_id='".$row['directors_cumulative_id']."'
                                                              ");
                        while($directors_activity_row = mysqli_fetch_array($directors_activity))
                        {
                          $so_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_objectives WHERE
                                                                  strategic_objective_id='".$directors_activity_row['strategic_objective_id']."'"));
                          ?>
                          <table>
                            <tr style="border-style:hidden;">
                              <td style="overflow: wrap;border-style:hidden;">
                                <p class="activity-font-directorate">- <?php echo $so_description['strategic_objective_description']; ?></p>
                              </td>

                            </tr>
                          </table>
                          <?php

                        }
                   ?>

               </td>
                 <?php
                 $overall_score = $row['overall_score'];
                 if($overall_score < 26 && $overall_score > 19)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>

                   <?php
                 }
                 if($overall_score < 17 && $overall_score > 9)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFC200; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 10 && $overall_score > 5)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFFF00; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 5 && $overall_score > 2)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#00FF00; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 3 && $overall_score > 0)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#006400; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 ?>


               <?php
                 //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                   $int = $row['cumulative_activity_score'];
                   if($int < 20 && $int > 0)
                   {
                     ?>
                     <td style="background-color:#FF0000;color:white;">
                        <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 40 && $int > 19)
                   {
                     ?>
                     <td style="background-color:#FFC200;color:black;">
                        <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 60 && $int > 39)
                   {
                     ?>
                     <td style="background-color:#FFFF00;color:black;">
                        <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 80 && $int > 59)
                   {
                     ?>
                     <td style="background-color:#00FF00; color:black;">
                        <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 101 && $int > 79)
                   {
                     ?>
                     <td style="background-color:#006400; color:white;">
                        <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 1)
                   {
                     ?>
                     <td>N/A</td>
                     <?php
                   }

                ?>
                <td>
                   <p class="activity-font-directorate"><?php echo $row['expected_cumulative_outcomes'];?></p>
                </td>

             </tr>
           <?php
         }
          ?>
       </table>
  </div>
</div>
<!-- /.card -->
  <?php
} // end num row
else  //no rows
{
  ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>No Records!<br/></strong> Sorry, no records found for the selected combination.
  </div>
  <?php
}
}

}
 ?>
