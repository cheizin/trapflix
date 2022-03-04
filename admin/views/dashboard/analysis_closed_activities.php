<?php
if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "director")
{
  $sql = mysqli_query($dbc,"SELECT * FROM perfomance_management WHERE
                                        activity_status='closed' ");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Closed Activities</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table width="100%" class="table table-hover" id="analysis-closed-activities-table" width="100%">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Department</td>
                    <td>Activity Description</td>
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
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>No</th>
                      <th>Department</th>
                      <th>Activity Description</th>
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
      <strong><i class="fa fa-info-circle"></i> No Closed Activities Found</strong>
    </div>
    <?php
  }
}
else
{

  $sql = mysqli_query($dbc,"SELECT * FROM perfomance_management
                                        WHERE department_id='".$_SESSION['department_code']."'
                                        && activity_status='closed'");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Closed Activities</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table class="table table-hover" id="analysis-closed-activities-table" width="100%">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Activity Description</td>
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
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>No</th>
                      <th>Activity Description</th>
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
      <strong><i class="fa fa-info-circle"></i> No Closed Activities Found</strong>
    </div>
    <?php
  }
}



 ?>
