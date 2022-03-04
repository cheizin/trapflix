<?php

session_start();
include("../../controllers/setup/connect.php");;
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

    $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE changed='no' && period_from='".$year_id."'
                                      && quarter='".$quarter_id."' && risk_status='open' && status='approved'
                                      && risk_opportunity='risk'
                                      ORDER BY dep_code ASC"
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {

      ?>
      <div class="card">
       <div class="card-header with-border">
         <h3 class="card-title">Risks With/Without Activities for All Departments:<br/>
         </h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="all_risks">
              <thead>
                <tr>
                  <td>NO</td>
                  <td>Reference Number</td>
                  <td>Risk Description</td>
                  <td>Risk/Opportunity</td>
                  <td>Department</td>
                  <td>Rating</td>
                </tr>
              </thead>
              <?php
              $number = 1;
              while($row = mysqli_fetch_array($sql))
              {
                ?>
                  <tr>
                    <td><?php echo $number++ ;?></td>
                    <td>
                      <?php echo $row['reference_no'];?>
                    </td>
                    <td>
                      <?php echo $row['risk_description'];?>
                    </td>
                    <td>
                      <?php echo ucfirst($row['risk_opportunity']);?>
                    </td>
                    <td><?php
                          $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name FROM
                                                                departments WHERE department_id='".$row['dep_code']."'"));
                          echo $department_name['department_name'] ;?>


                    </td>
                    <td>
                      <?php echo $row['current_overall_score'] ;

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
                            "SELECT * FROM update_risk_status WHERE changed='no' && period_from='".$year_id."'
                                                              && quarter='".$quarter_id."' && risk_status='open' && status='approved'
                                                              && dep_code='".$selected_department."'
                                                              ORDER BY dep_code ASC"
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {

      ?>
      <div class="card">
       <div class="card-header with-border">
         <h3 class="card-title">Risks With/Without Activities Under:<br/><b>
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
            <table class="table table-striped table-bordered table-hover" id="all_risks">
              <thead>
                <tr>
                  <td>NO</td>
                  <td>Reference Number</td>
                  <td>Risk Description</td>
                  <td>Risk / Opportunity</td>
                  <td>Department</td>
                  <td>Rating</td>
                </tr>
              </thead>
              <?php
              $number = 1;
              while($row = mysqli_fetch_array($sql))
              {
                ?>
                  <tr>
                      <td><?php echo $number++ ;?></td>
                      <td>
                        <?php echo $row['reference_no'];?>
                      </td>
                      <td>
                        <?php echo $row['risk_description'];?>
                      </td>
                      <td>
                        <?php echo ucfirst($row['risk_opportunity']);?>
                      </td>
                      <td><?php
                            $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name FROM
                                                                  departments WHERE department_id='".$row['dep_code']."'"));
                            echo $department_name['department_name'] ;?>


                      </td>
                      <td>
                        <?php echo $row['current_overall_score'] ;

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
