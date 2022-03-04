<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

  $resource_name = mysqli_real_escape_string($dbc,strip_tags($_POST['resource_name']));

 ?>

 <table class="table table-bordered table-striped table-hover dashboard-tasks-per-resource-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Task</th>
      <th scope="col">Status</th>
      <th scope="col">Due</th>
      <th scope="col">Comments</th>
      <th scope="col">Last Update</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no =1;
        $tasks_sql = mysqli_query($dbc,"SELECT * FROM pm_activities WHERE task_id IN
                                                    (SELECT activity_id FROM pm_resources WHERE resource_name='".$resource_name."')
                                                    && project_id IN
                                                    (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no' ORDER BY id DESC)");
        while($tasks_row = mysqli_fetch_array($tasks_sql))
        {
          $tasks_phase = mysqli_fetch_array(mysqli_query($dbc,"SELECT status,color_code,comments,date_recorded,recorded_by FROM pm_activity_updates WHERE task_id='".$tasks_row['task_id']."'
                                                                        ORDER BY id DESC LIMIT 1"));
          ?>
          <tr>
            <th scope="row"><?php echo $no++;?></th>
            <td> <small><?php  echo $tasks_row['activity_name']; ?></small></td>
            <td> <span class="badge <?php echo $tasks_row['color_code'];?>"><?php echo $tasks_phase['status'];?></span></td>
            <td>
                <?php
                $todays_date = date('d-M-yy');

                $date1 = new DateTime($tasks_row['end_date']); //inclusive
               $date2 = new DateTime($todays_date); //exclusive
               $diff = $date2->diff($date1);

               $days = $diff->format("%R");
               if($days == "-")
               {
                 ?>
                  <span class="badge badge-danger" data-toggle="tooltip" title="This task was supposed to end in <?php echo $tasks_row['end_date'] ;?>"><?php echo $diff->format("%R%a") . " Days";?></span>
                 <?php
               }
               else
               {
                 ?>
                 <span class="badge badge-success" data-toggle="tooltip" title="This task is supposed to end in <?php echo $tasks_row['end_date'] ;?>"><?php echo $diff->format("%R%a") . " Days";?></span>
                 <?php
               }
                 ?>

            </td>
            <td>
              <small><?php echo $tasks_phase['comments'];?></small>
            </td>
            <td>
              <small><?php echo $tasks_phase['date_recorded'];?></small>
              <br/>
                <small>By <br/> <?php echo $tasks_phase['recorded_by'];?></small>
            </td>
          </tr>
          <?php
        }
     ?>
  </tbody>
</table>

<script>
$('.dashboard-tasks-per-resource-table').DataTable({
      destroy: true,

  });
</script>
