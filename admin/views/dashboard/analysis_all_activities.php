<?php
if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "director")
{
  $sql = mysqli_query($dbc,"SELECT * FROM performance_update WHERE
                                        year_id='".$current_quarter_and_year['period']."'
                                        && quarter_id = '".$current_quarter_and_year['quarter']."'
                                        && activity_id IN
                                        (SELECT activity_id FROM perfomance_management WHERE activity_status='open')
                                        && changed='no'");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header bg-light">
            All Updated Activities
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table width="100%" class="table table-hover" id="analysis-all-activities-table">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Department</td>
                    <td>Activity Description</td>
                    <td>Prior Performance Estimate</td>
                    <td>Current Current Performance Estimate</td>
                    <td>Activity Movement</td>
                  </tr>
                </thead>
              <?php

              while($row = mysqli_fetch_array($sql))
              {
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>
                  <td>
                      <?php
                            $select_dep = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id
                                                    FROM perfomance_management WHERE activity_id='".$row['activity_id']."'"));
                            echo $select_dep['department_id'];
                       ?>
                  </td>
                  <td>
                    <?php
                    $activity_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT activity_description FROM
                                                                      perfomance_management WHERE
                                                                      activity_id='".$row['activity_id']."'
                                                                      "));

                     ?>
                    <a href="#"
                          onclick="ViewActivity('<?php echo $row['activity_id'] ;?>','<?php echo $select_dep['department_id'];?>','<?php echo $fin_year;?>');"
                          title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                          <?php echo $activity_description['activity_description'];?>
                    </a>
                  </td>
                  <td>
                    <?php
                    $prior_estimate = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM performance_update WHERE
                                                          activity_id='".$row['activity_id']."'
                                                          && year_id='".$last_quarter_and_year['period']."'
                                                          && quarter_id = '".$last_quarter_and_year['quarter']."'
                                                          && changed='no'"));
                    $prior_estimate_percentage = $prior_estimate['estimated_current_performance'];
                    echo $prior_estimate_percentage;
                    ?>

                  </td>
                  <td><?php echo $row['estimated_current_performance'] . " %";?></td>
                  <td>
                      <?php
                      if($prior_estimate_percentage > $row['estimated_current_performance'])
                      {
                        //downgraded
                        ?>
                            Downgraded
                        <?php
                      }
                      if($prior_estimate_percentage < $row['estimated_current_performance'])
                      {
                        //upgraded
                        ?>
                        Upgraded
                        <?php
                      }
                      if($prior_estimate_percentage == $row['estimated_current_performance'])
                      {
                        //static
                        ?>
                        Static
                        <?php
                      }
                       ?>
                  </td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>No</th>
                      <th>Department</th>
                      <th>Activity Description</th>
                      <th>Prior Performance Estimate</th>
                      <th>Current Performance Estimate</th>
                      <th>Activity Movement</th>
                  </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    <?php
  }
  else
  {
    ?>
    <br/>
    <div class="alert alert-warning">
      <strong><i class="fa fa-info-circle"></i> No Updated Activities Found for the Current Quarter</strong>
    </div>
    <?php
  }
}
else
{

  $sql = mysqli_query($dbc,"SELECT * FROM performance_update WHERE
                                        activity_id IN (SELECT activity_id FROM
                                        perfomance_management WHERE department_id='".$_SESSION['department_code']."'
                                        && activity_status='open')
                                        && year_id='".$current_quarter_and_year['period']."'
                                        && quarter_id = '".$current_quarter_and_year['quarter']."'
                                        && changed='no'");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All Updated Activities</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table class="table table-hover" id="analysis-all-activities-table" width="100%">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Activity Description</td>
                    <td>Prior Performance Estimate</td>
                    <td>Current Current Performance Estimate</td>
                    <td>Activity Movement</td>
                  </tr>
                </thead>
              <?php

              while($row = mysqli_fetch_array($sql))
              {
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>
                  <td>
                    <?php
                    $activity_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT activity_description FROM
                                                                      perfomance_management WHERE
                                                                      activity_id='".$row['activity_id']."'
                                                                      "));

                     ?>
                    <a href="#"
                          onclick="ViewActivity('<?php echo $row['activity_id'] ;?>','<?php echo $select_dep['department_id'];?>','<?php echo $fin_year;?>');"
                          title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                          <?php echo $activity_description['activity_description'];?>
                    </a>
                  </td>
                  <td>
                    <?php
                    $prior_estimate = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM performance_update WHERE
                                                          activity_id='".$row['activity_id']."'
                                                          && year_id='".$last_quarter_and_year['period']."'
                                                          && quarter_id = '".$last_quarter_and_year['quarter']."'
                                                          && changed='no'"));
                    $prior_estimate_percentage = $prior_estimate['estimated_current_performance'];
                    echo $prior_estimate_percentage;
                    ?>

                  </td>
                  <td><?php echo $row['estimated_current_performance'] . " %";?></td>
                  <td>
                      <?php
                            if($prior_estimate_percentage > $row['estimated_current_performance'])
                            {
                              //downgraded
                              ?>
                                  Downgraded
                              <?php
                            }
                            if($prior_estimate_percentage < $row['estimated_current_performance'])
                            {
                              //upgraded
                              ?>
                              Upgraded
                              <?php
                            }
                            if($prior_estimate_percentage == $row['estimated_current_performance'])
                            {
                              //static
                              ?>
                              Static
                              <?php
                            }
                       ?>
                  </td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>No</th>
                      <th>Activity Description</th>
                      <th>Prior Performance Estimate</th>
                      <th>Current Performance Estimate</th>
                      <th>Activity Movement</th>
                  </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    <?php
  }
  else
  {
    ?>
    <br/>
    <div class="alert alert-warning">
      <strong><i class="fa fa-info-circle"></i> No Updated Activities Found for the current Quarter</strong>
    </div>
    <?php
  }
}



 ?>
