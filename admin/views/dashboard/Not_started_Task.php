<div class="col-xs-12">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Not started Task</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive no-padding">
        <table width="100%" class="table table-hover" id="analysis-downgraded-risks-table">
          <thead>
            <tr>
           <td>#</td>
           <td>Task Name</td>
           <td>Milestone</td>
           <td>Start</td>
           <td>End Date</td>
           <td>Days Due</td>
           <td>Resources</td>
           <td>Status</td>
         </tr>
       </thead>
       <?php
      

       $sql_tasks = mysqli_query($dbc,"SELECT * FROM pm_activities WHERE task_id IN
                                             (SELECT id FROM pm_activity_updates WHERE status ='Not Started')");
        $no = 1;
        while($row_tasks = mysqli_fetch_array($sql_tasks))
        {
          $milestone = mysqli_fetch_array(mysqli_query($dbc,"SELECT milestone_name FROM pm_milestones WHERE id='".$row_tasks['milestone_id']."'"));
          ?>
          <tr style="cursor: pointer;">
            <td width="50px"> <?php echo $no++;?>.

            </td>
            <td>

              <?php echo $row_tasks['task_id'];?>

            </td>
            <td><?php echo $milestone['milestone_name'];?></td>
            <td><?php echo $row_tasks['start_date'];?></td>
            <td><?php echo $row_tasks['end_date'];?></td>
            <td>
              <?php
              $todays_date = date('d-M-yy');
              $date1 = new DateTime($row_tasks['end_date']); //inclusive
              $date2 = new DateTime($todays_date); //exclusive
              $diff = $date2->diff($date1);
              echo $diff->format("%a");

               ?>
            </td>
            <td>
                <?php
                $sql_resources = mysqli_query($dbc,"SELECT * FROM pm_resources WHERE activity_id='".$row_tasks['task_id']."'");
                while($resources = mysqli_fetch_array($sql_resources))
                {
                  ?>
                    <small class="border-bottom">
                      <?php echo $resources['resource_name'];?>
                      <a href="#" class="btn btn-link float-right" onclick="DeleteResource('<?php echo $resources['resource_id'];?>');"
                          title="Remove <?php echo $resources['resource_name'];?> from <?php echo $resources['activity_id'];?>">
                         <i class="far fa-user-times text-danger"></i>
                      </a><br/>
                    </small><br/>
                  <?php
                }

                 ?>
            </td>

            <?php
            //most recent updated task
                $sql_status = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_activity_updates WHERE task_id='".$row_tasks['task_id']."'
                                                                     ORDER BY id DESC LIMIT 1 "));
                if($sql_status['color_code'] == "one")
                {
                  $text_color = "text-white";
                }
                else
                {
                  $text_color = "text-dark";
                }
             ?>
            <td class="<?php echo $sql_status['color_code'];?>" width="40px;">
              <small class="<?php echo $text_color ;?>"><?php echo $sql_status['status'];?></small>

            </td>
          </tr>
          <?php
        }
        ?>
     </table>
   </div>
 </div>
</div>
