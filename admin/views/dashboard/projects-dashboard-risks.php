<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>

 <table class="table table-bordered table-striped table-hover" id="dashboard-active-project-risks-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Risk Decsription</th>
      <th scope="col">Impact</th>
      <th scope="col">Rating</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no =1;
        $projects_sql = mysqli_query($dbc,"SELECT * FROM pm_risks_updates WHERE changed='no'
                                                && risk_id IN
                                                (SELECT risk_id FROM pm_risks WHERE status='open')
                                                && project_id IN
                                                (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no' ORDER BY id DESC)");
        while($projects_row = mysqli_fetch_array($projects_sql))
        {
          $project_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_name FROM pm_projects WHERE project_id='".$projects_row['project_id']."'
                                                                        ORDER BY id DESC LIMIT 1"));

            if($projects_row['phase'] == 'all')
            {
              $phase = 'All Phases';
            }
            else
            {
              $phase = ucwords(str_replace("_"," ",$projects_row['phase']));
              $phase = " the " .$phase. " phase";
            }
          ?>
          <tr>
            <th scope="row"><?php echo $no++;?></th>
            <td>
              <span tabindex="0" data-html="true" data-toggle="popover" data-trigger="focus"
                    data-content="<div class='monitor-projects-link text-primary' style='cursor:pointer;'>This risk affects <?php echo $project_name['project_name'];?> on <?php echo $phase;?></div>"
                    style="cursor:pointer;" data-original-title="<b>Affected Project</b>">
                <small class="text-primary">
                  <?php echo $projects_row['risk_description'];?>
                </small><br/>
              </span>
            </td>
            <td><small><?php echo $projects_row['impact'];?></small></td>
            <td>
              <?php
                  $overall_score = $projects_row['overall_score'];
                  if($overall_score < 26 && $overall_score > 19 )
                  {
                    ?>
                    <span class="badge five"><?php echo $overall_score;?></span>
                    <?php
                  }
                  if($overall_score < 17 && $overall_score > 9 )
                  {
                    ?>
                    <span class="badge four"><?php echo $overall_score;?></span>
                    <?php
                  }
                  if($overall_score < 10 && $overall_score > 5 )
                  {
                    ?>
                    <span class="badge three"><?php echo $overall_score;?></span>
                    <?php
                  }
                  if($overall_score < 5 && $overall_score > 2 )
                  {
                    ?>
                    <span class="badge two"><?php echo $overall_score;?></span>
                    <?php
                  }
                  if($overall_score < 3 && $overall_score > 0 )
                  {
                    ?>
                    <span class="badge one"><?php echo $overall_score;?></span>
                    <?php
                  }

               ?>
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
