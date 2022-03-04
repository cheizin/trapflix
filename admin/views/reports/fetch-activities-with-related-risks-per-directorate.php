<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  $selected_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['directorates']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));

  $sql = mysqli_query($dbc,
                          "SELECT a.strategic_objective_id AS SOB_ID, a.activity_id ACT_ID, a.activity_description AS ACT_DESC,
                           b.strategic_objective_description AS SOB_DESC, a.department_id AS DEP_ID,
                           d.risk_reference AS RISK_REF

                          FROM  strategic_objectives b JOIN
                          perfomance_management a ON a.strategic_objective_id = b.strategic_objective_id
                          JOIN activity_related_risks d ON d.activity_id =a.activity_id
                          INNER JOIN performance_update c ON a.activity_id = c.activity_id
                          JOIN departments e ON a.department_id = e.department_id
                          WHERE e.directorate_id='".$selected_directorate."'
                          AND d.risk_reference IS NOT NULL
                          AND c.quarter_id = '".$quarter_id."'
                          AND c.year_id = '".$year_id."'
                          AND c.changed = 'no'
                          AND d.changed='no'
                          AND d.quarter_id = '".$quarter_id."'
                          AND d.year_id = '".$year_id."'
                          AND d.changed = 'no'
                          AND a.activity_status='open'
                          GROUP BY a.activity_id
                          "
                          );
  if($sql)
  {
    $total_rows = mysqli_num_rows($sql);
    if($total_rows > 0)
    {

    ?>
    <div class="card">
     <div class="card-header with-border">
       <h3 class="card-title">Activities With Related Risks:<br/><b>
              <?php
                  echo $selected_directorate;
              ;?>
            </b>
       </h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="all_activities_with_related_risks_table">
            <thead>
              <tr>
                <td>NO</td>
                <td width="160">Strategic Objective</td>
                <td>Activity Description</td>
                <td width="200">Risk Description</td>
                <td>Impact</td>
                <td>Department</td>
              </tr>
            </thead>
            <?php
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
                <tr  onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                  <td><?php echo $number++ ;?></td>
                  <td><?php echo $row['SOB_DESC'] ;?></td>
                  <td>  <?php echo $row['ACT_DESC'];?> </td>
                  <td>
                    <?php
                          $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                  && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                          if(mysqli_num_rows($sql_related_risks) > 0){
                            while($related_risks = mysqli_fetch_array($sql_related_risks))
                            {
                              $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."'"))
                              ?>
                              <table>
                                <tr><td><?php echo $risk_description['risk_description'] ;?></td>
                                  <hr style="solid black">
                                </tr>
                              </table>
                              <?php
                            }
                          }
                     ?>

                  </td>

                  <td>
                    <?php
                          $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                  && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                          if(mysqli_num_rows($sql_related_risks) > 0){
                            while($related_risks = mysqli_fetch_array($sql_related_risks))
                            {
                              $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."'"))
                              ?>
                            <table style="width:200px;">
                                <tr><td><?php echo $risk_description['impact'] ;?></td>
                                  <hr style="solid black">
                                </tr>
                              </table>
                              <?php
                            }
                          }
                     ?>

                  </td>
                  <td><?php echo $row['DEP_ID'] ;?></td>
                </tr>
              <?php
            }
             ?>
          </table>
        </div>
     </div>
     <!-- /.card-body -->
     <div class="card-footer">

     </div>
     <!-- card-footer -->
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
  else
  {
    exit(mysqli_error($dbc));
  }

}
else
{
  exit("NO data");
  ?>

 <?php
}

 ?>
