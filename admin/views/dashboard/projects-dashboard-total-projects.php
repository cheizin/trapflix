<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>

 <table class="table table-bordered table-striped table-hover" id="dashboard-active-projects-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Project Name</th>
      <th scope="col">Phase</th>
      <th scope="col">Due</th>
      <th scope="col">Resources</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no =1;
        $projects_sql = mysqli_query($dbc,"SELECT * FROM pm_projects WHERE project_id IN
                                                (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no' ORDER BY id DESC)");
        while($projects_row = mysqli_fetch_array($projects_sql))
        {
          $projects_phase = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_phase FROM pm_projects_update WHERE project_id='".$projects_row['project_id']."'
                                                                        ORDER BY id DESC LIMIT 1"));
          ?>
          <tr>
            <th scope="row"><?php echo $no++;?></th>
            <td> <small><?php  echo $projects_row['project_name']; ?></small></td>
            <td>
                <?php
                    $phase = strtolower($projects_phase['project_phase']);
                    if($phase == 'pre_initiating')
                    {
                      ?>
                        <span class="badge badge-info"><?php echo $phase;?></span>
                      <?php
                    }
                    else if($phase == 'initiating')
                    {
                      ?>
                        <span class="badge badge-primary"><?php echo $phase;?></span>
                      <?php
                    }
                    else if($phase == 'planning')
                    {
                      ?>
                        <span class="badge badge-primary"><?php echo $phase;?></span>
                      <?php
                    }
                    else if($phase == 'executing')
                    {
                      ?>
                        <span class="badge badge-success"><?php echo $phase;?></span>
                      <?php
                    }
                    else if($phase == 'benefits_tracking')
                    {
                      ?>
                        <span class="badge badge-success"><?php echo $phase;?></span>
                      <?php
                    }

                 ?>

            </td>
            <td>
                <?php
                $todays_date = date('d-M-yy');

                $date1 = new DateTime($projects_row['end_date']); //inclusive
               $date2 = new DateTime($todays_date); //exclusive
               $diff = $date2->diff($date1);

               $days = $diff->format("%R");
               if($days == "-")
               {
                 ?>
                  <span class="badge badge-danger" data-toggle="tooltip" title="This project was supposed to end in <?php echo $projects_row['end_date'] ;?>"><?php echo $diff->format("%R%a") . " Days";?></span>
                 <?php
               }
               else
               {
                 ?>
                 <span class="badge badge-success" data-toggle="tooltip" title="This project is supposed to end in <?php echo $projects_row['end_date'] ;?>"><?php echo $diff->format("%R%a") . " Days";?></span>
                 <?php
               }
                 ?>

            </td>
            <td>
                <?php
                    $total_resources = mysqli_query($dbc,"SELECT * FROM pm_resources WHERE project_id='".$projects_row['project_id']."'
                                                                    && project_id IN
                                                                    (SELECT project_id FROM pm_projects WHERE status='Active')
                                                                    GROUP BY resource_name");

                 ?>
                 <span
                      tabindex="0"
                      data-html="true"
                      data-toggle="popover"
                      data-trigger="focus"
                      title="<b>Resources</b>"
                      data-content="<div><?php
                                             $count = 1;
                                              while($resource_row = mysqli_fetch_array($total_resources))
                                              {
                                               ?>
                                                <?php echo $count++;?> . <?php echo $resource_row['resource_name'];?></br>
                                                <?php
                                              }
                                              ?>
                                      </div>"

                  style="cursor:pointer;">
                      <i class="fad fa-users text-info"></i>

                      <?php echo $resources = mysqli_num_rows($total_resources); ?>
                 </span>

            </td>
          </tr>
          <?php
        }
     ?>
  </tbody>
</table>
<script>
$("[data-toggle=popover]").popover();
</script>
