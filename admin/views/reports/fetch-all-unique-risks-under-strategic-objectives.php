<?php

session_start();
include("../../controllers/setup/connect.php");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

 if (!isset($_SESSION['email']))
 {
    exit("unauthenticated");
 }
  $strategic_objective = mysqli_real_escape_string($dbc,strip_tags($_POST['strategic_objective']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));


  /*if($_SESSION['access_level'] == 'standard')
  {
    $sql = mysqli_query($dbc,
    "SELECT DISTINCT
    e.risk_reference AS RR, b.strategic_objective_id AS SOB, b.strategic_objective_description AS SD,
    e.risk_description AS RD, h.current_overall_score AS SCORE , e.impact AS IMPACT, g.department_name AS DEP_ID, g.department_id AS DEP_CODE
    FROM
    strategic_objectives b
        JOIN perfomance_management a ON a.strategic_objective_id = b.strategic_objective_id
        JOIN activity_related_risks d ON d.activity_id =a.activity_id
        JOIN risk_management e ON e.risk_reference =d.risk_reference
        JOIN update_risk_status h ON e.risk_reference = h.reference_no
        JOIN performance_update f ON a.activity_id = f.activity_id
        JOIN departments g ON a.department_id = g.department_id
    WHERE e.changed = 'No'
    AND h.period_from = '".$year_id."'
    AND h.quarter = '".$quarter_id."'
    AND h.changed = 'no'
    AND b.strategic_objective_id ='".$strategic_objective."'
    AND d.risk_reference IS NOT NULL
    AND f.year_id = '".$year_id."'
    AND f.quarter_id = '".$quarter_id."'
    AND a.department_id = '".$_SESSION['department_code']."'
    ORDER BY h.current_overall_score DESC
    "
                            );
  }
  else
  {
  */
    $sql = mysqli_query($dbc,
                            "SELECT DISTINCT
                            e.risk_reference AS RR, b.strategic_objective_id AS SOB, b.strategic_objective_description AS SD, e.risk_opportunity AS RO,
                            e.risk_description AS RD, h.current_overall_score AS SCORE , e.impact AS IMPACT, g.department_name AS DEP_ID, g.department_id AS DEP_CODE
                            FROM
                            strategic_objectives b
                                JOIN perfomance_management a ON a.strategic_objective_id = b.strategic_objective_id
                                JOIN activity_related_risks d ON d.activity_id =a.activity_id
                                JOIN risk_management e ON e.risk_reference =d.risk_reference
                                JOIN update_risk_status h ON e.risk_reference = h.reference_no
                                JOIN performance_update f ON a.activity_id = f.activity_id
                                JOIN departments g ON a.department_id = g.department_id
                            WHERE e.changed = 'No'
                            AND h.period_from = '".$year_id."'
                            AND h.quarter = '".$quarter_id."'
                            AND h.risk_status='open'
                            AND h.changed = 'no'
                            AND b.strategic_objective_id ='".$strategic_objective."'
                            AND d.risk_reference IS NOT NULL
                            AND f.year_id = '".$year_id."'
                            AND f.quarter_id = '".$quarter_id."'
                            ORDER BY h.current_overall_score DESC
                            "
                            );
/*  }*/

  if($sql)
  {
    $total_rows = mysqli_num_rows($sql);
    if($total_rows > 0)
    {

    ?>
    <div class="card">
     <div class="card-header bg-light">
       All Unique Risks Under  Strategic Objective:<br/>
              <?php
                  $sql_select_strategic_objectives =mysqli_fetch_array(mysqli_query($dbc,"SELECT strategic_objective_id,strategic_objective_description
                                                    FROM strategic_objectives WHERE strategic_objective_id='".$strategic_objective."'"));
              echo $sql_select_strategic_objectives['strategic_objective_description'];

              ;?>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="all_unique_risks_under_strategic_objectives_table">
            <thead>
              <tr>
                <td>NO</td>
                <td>Risk/Opportunity Description</td>
                <td>Risk/Opportunity</td>
                <td>Impact</td>
                <td>Department</td>
                <td>Risk/Opportunity Rating</td>
              </tr>
            </thead>
            <?php
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
                <tr onclick="ViewRisk('<?php echo $row['RR'];?>','<?php echo $row['DEP_CODE'];?>');" style="cursor:pointer;">
                  <td><?php echo $number++ ;?></td>
                  <td><?php echo $row['RD'];?> </td>
                  <td><?php echo $row['RO'] ;?></td>
                  <td><?php echo $row['IMPACT'] ;?></td>
                  <td><?php echo $row['DEP_ID'] ;?></td>
                  <td><?php echo $row['SCORE'] ;?></td>
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
