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

    $directorate_sql = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,directorate_id FROM departments WHERE
                                                                      directorate_id='".$selected_directorate."'"));
    $directorate = $directorate_sql['directorate_id'];
    $department = $directorate_sql['department_id'];

    $sql = mysqli_query($dbc,
                            "SELECT risk_reference,risk_description, department_code, risk_reference,risk_opportunity,impact FROM risk_management
                             WHERE risk_reference NOT IN
                                        (SELECT risk_reference FROM activity_related_risks  WHERE changed ='no' &&
                                        year_id='".$year_id."' && quarter_id='".$quarter_id."')

                                        AND department_code IN
                                       (SELECT department_id FROM 	departments WHERE directorate_id='".$directorate."')
                            AND changed ='no'
			                      AND risk_status='open'
                            AND risk_reference IN
                                      (SELECT reference_no FROM update_risk_status WHERE changed='no' && period_from='".$year_id."'
                                      && quarter='".$quarter_id."' && risk_status='open')
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
         <h3 class="card-title">Risks Without Activities Under:<br/><b>
                <?php
                    echo $directorate;
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
                  <td>Department</td>
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
                  <tr onclick="ViewRisk('<?php echo $row['risk_reference'];?>','<?php echo $row['department_code'];?>');" style="cursor:pointer;">
                    <td><?php echo $number++ ;?></td>
                    <td><?php echo $row['risk_description'];?>  </td>
                    <td><?php echo $row['department_code'] ;?></td>
                    <td><?php echo $row['risk_opportunity'] ;?></td>
                    <td><?php echo $row['impact'] ;?></td>
                    <td>
                      <?php echo $row['score'] ;
                            $current_overall_score = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE changed='no'
                                                                          && period_from='".$year_id."' && quarter='".$quarter_id."'
                                                                          && reference_no='".$row['risk_reference']."'"));
                            echo $current_overall_score['current_overall_score'];

                      ?>


                    </td>
                  </tr>
                <?php
              }
               ?>
            </table>
          </div>
       </div>
       <!-- /.card-body -->
       <div class="card-footer">
         <!--The footer of the card-->
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
