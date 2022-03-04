<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  if($_POST['departments'] == "all")
  {
    $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
    $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
    $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
    session_start();
    require_once('../setup/connect.php');
    require_once('../libraries/mpdf7/vendor/autoload.php');

    $sql = mysqli_query($dbc,
                            "SELECT risk_reference,risk_description, department_code, risk_reference,risk_opportunity,impact FROM risk_management
                             WHERE risk_reference NOT IN
                                        (SELECT DISTINCT risk_reference FROM activity_related_risks  WHERE changed ='no' &&
                                        year_id='".$year_id."' && quarter_id='".$quarter_id."')
                            AND changed ='no'
                            AND risk_reference IN
                                      (SELECT reference_no FROM update_risk_status WHERE changed='no' && period_from='".$year_id."'
                                      && quarter='".$quarter_id."' && risk_status='open')
	                          AND risk_status='open'
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
         <h3 class="card-title">Risks Without Activities for All Departments:<br/>
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
                  <td>Department</td>
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
                    <td><?php echo $row['risk_description'];?></td>
                    <td><?php echo $row['risk_opportunity'] ;?></td>
                    <td><?php echo $row['impact'] ;?></td>
                    <td><?php
                          $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name FROM
                                                                departments WHERE department_id='".$row['department_code']."'"));
                          echo $department_name['department_name'] ;?>


                    </td>
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
    $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
    $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
    $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));

    $sql = mysqli_query($dbc,
                            "SELECT risk_reference,risk_description, department_code, risk_reference,risk_opportunity,impact FROM risk_management
                             WHERE risk_reference NOT IN
                                        (SELECT DISTINCT risk_reference FROM activity_related_risks  WHERE changed ='no' &&
                                        year_id='".$year_id."' && quarter_id='".$quarter_id."')
                            AND changed ='No' AND department_code='".$selected_department."'
                            AND risk_reference IN
                                      (SELECT reference_no FROM update_risk_status WHERE changed='no' && period_from='".$year_id."'
                                      && quarter='".$quarter_id."' && dep_code='".$selected_department."' && risk_status='open')
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
                    $sql_select_department_name =mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name
                                                      FROM departmentS WHERE department_id='".$selected_department."'"));
                echo $sql_select_department_names['department_name'];

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
                  <tr onclick="ViewRisk('<?php echo $row['risk_reference'];?>','<?php echo $row['department_code'];?>');" style="cursor:pointer;">
                    <td><?php echo $number++ ;?></td>
                    <td><?php echo $row['risk_description'];?></td>
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


}
else
{
  exit("NO data");
  ?>

 <?php
}
 ?>
