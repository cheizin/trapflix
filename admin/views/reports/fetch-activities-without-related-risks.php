<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));

  $sql = mysqli_query($dbc,
                          "SELECT
                           a.strategic_objective_id AS SOB,b.strategic_objective_description AS SOB_DESC,
                           a.activity_id AS ACT_ID, a.activity_description AS ACT_DESC,
                           d.risk_reference AS RR
                          FROM  strategic_objectives b
                          JOIN perfomance_management a ON a.strategic_objective_id = b.strategic_objective_id
                          LEFT OUTER JOIN activity_related_risks d ON d.activity_id =a.activity_id
                          INNER JOIN performance_update c ON a.activity_id = c.activity_id
                          AND d.risk_reference IS NULL
                          WHERE a.department_id='".$selected_department."'
                          AND c.quarter_id = '".$quarter_id."'
                          AND c.year_id = '".$year_id."'
                          AND c.changed = 'no'
                          AND a.activity_status='open'
                          ORDER BY a.activity_id
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
       <h3 class="card-title">Activities Without Related Risks:<br/><b>
              <?php
                  $sql_select_department =mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name
                                                    FROM departmentS WHERE department_id='".$selected_department."'"));
              echo $sql_select_department['department_name'];

              ;?>
            </b>
       </h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="all_activities_without_related_risks_table">
            <thead>
              <tr>
                <td>NO</td>
                <td width="400">Strategic Objective</td>
                <td>Activity Description</td>
              </tr>
            </thead>
            <?php
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
                <tr onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                  <td><?php echo $number++ ;?></td>
                  <td><?php echo $row['SOB_DESC'] ;?></td>
                  <td>  <?php echo $row['ACT_DESC'];?></td>

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
