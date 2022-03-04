<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  $directorates= mysqli_real_escape_string($dbc,strip_tags($_POST['directorates']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));

  $sql = mysqli_query($dbc,
                          "SELECT e.risk_reference AS RR,e.risk_opportunity AS RISK_OPP, g.directorate_name AS DN, e.risk_description AS RD, h.current_overall_score AS SCORE,
                          a.department_id AS DEP_ID, e.impact AS IMPACT, f.treatment_action AS TREATMENT, f.period_from, f.quarter
                          FROM directorates g
                          JOIN departments c ON g.directorate_id=c.directorate_id
                          JOIN perfomance_management a ON c.department_id =a.department_id
                          JOIN activity_related_risks d ON d.activity_id =a.activity_id
                          JOIN risk_management e ON e.risk_reference =d.risk_reference
                          JOIN update_risk_status h ON e.risk_reference = h.reference_no
                          JOIN risk_drivers f ON e.risk_reference =f.risk_reference
                          WHERE e.changed = 'no'
                          AND f.changed = 'no'
			  AND e.risk_status='open'
        AND h.risk_status='open'
			  AND a.activity_status='open'


   AND d.risk_reference is not null
   AND d.changed='no'
   AND d.year_id = '".$year_id."'
   AND d.quarter_id = '".$quarter_id."'
   AND f.period_from ='".$year_id."'
   and f.quarter = '".$quarter_id."'
   and g.directorate_id ='".$directorates."'
   AND h.period_from = '".$year_id."'
   AND h.quarter = '".$quarter_id."'
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
       <h3 class="card-title">All Unique Risks Under directorates:<br/><b>
              <?php

              echo   $directorates;

              ;?>
            </b>
       </h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="all_unique_risks_under_directorate_table">
            <thead>
              <tr>
                <td>NO</td>
                <td>Description</td>
		<td>Risk / Opportunity</td>
                <td>Impact</td>
                <td>Department</td>
                <td>Rating</td>
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
		  <td><?php echo ucwords($row['RISK_OPP']) ;?></td>
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

 ?>
