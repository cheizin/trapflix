
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Resource status</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table width="100%" class="table table-hover" id="analysis-downgraded-risks-table">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Resource Name</td>
                    <td>Assigned Tasks</td>
                    <td>Start</td>
                    <td>Finish </td>
                    <td>Days Due </td>
                  </tr>
                </thead>
                <?php

                $sql= mysqli_query($dbc,"SELECT DISTINCT resource_name FROM pm_resources
                                              ORDER BY resource_name");
            $number = 1;
              while($resources = mysqli_fetch_array($sql))
              {
                
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>

                  <td><?php echo $resources['resource_name'];?></td>
                   <td>
                      <?php
                   $actName = mysqli_query($dbc,"SELECT activity_name FROM pm_activities WHERE task_id='".$resources['activity_id']."'");
                     while($act= mysqli_fetch_array($actName))
                     {

                       ?>
                       <?php echo $act['activity_name'];?>

                       <?php
                     }
                     ?>

                  </td>
                  <td>--</td>
                  <td>--</td>
                  <td>--</td>

                </tr>
                <?php
              }
              ?>

            </table>
          </div>
        </div>
      </div>
