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
                          "SELECT e.risk_reference AS RR, e.risk_description AS RD, h.current_overall_score AS SCORE, e.risk_opportunity AS RO,
                            a.department_id AS DEP_ID, e.impact AS IMPACT, f.treatment_action AS TREATMENT, f.period_from, f.quarter FROM
                            perfomance_management a
                                JOIN
                                activity_related_risks d ON d.activity_id =a.activity_id
                                JOIN
                                risk_management e ON e.risk_reference =d.risk_reference
                                JOIN
                                update_risk_status h ON e.risk_reference = h.reference_no
                                JOIN
                                risk_drivers f ON e.risk_reference =f.risk_reference
                            WHERE e.changed = 'no'
                            AND f.changed = 'no'
                            AND d.changed='no'
                            AND d.year_id='".$year_id."'
                            AND d.quarter_id='".$quarter_id."'
                            AND d.risk_reference IS NOT NULL
                            AND f.period_from ='".$year_id."'
                            AND f.quarter = '".$quarter_id."'
                            AND f.changed='no'
                            AND a.department_id = '".$selected_department."'
                            AND h.period_from = '".$year_id."'
                            AND h.quarter = '".$quarter_id."'
                            AND h.risk_status='open'
                            AND h.changed = 'no'
                            GROUP BY e.risk_reference
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
       <h3 class="card-title">All Unique Risks Under Department:<br/><b>
              <?php
                  $sql_select_strategic_objectives =mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name
                                                    FROM departmentS WHERE department_id='".$selected_department."'"));
              echo $sql_select_strategic_objectives['department_name'];

              ;?>
            </b>
       </h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="all_unique_risks_under_departments_table">
            <thead>
              <tr>
                <td>NO</td>
                <td>Risk/Opportunity Description</td>
                <td>Risk/Opportunity</td>
                <td>Impact</td>
                <td>Risk /Opportunity Rating</td>
              </tr>
            </thead>
            <?php
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
                <tr onclick="ViewRisk('<?php echo $row['RR'];?>','<?php echo $row['DEP_CODE'];?>');" style="cursor:pointer;">
                  <td><?php echo $number++ ;?></td>
                  <td><?php echo $row['RD'];?>  </td>
                  <td><?php echo $row['RO'] ;?></td>
                  <td><?php echo $row['IMPACT'] ;?></td>
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
      <!-- The footer of the card -->
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
