<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>

 <table class="table table-bordered table-striped table-hover" id="dashboard-project-reources-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Resource</th>
      <th scope="col">Projects</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no =1;
        $projects_sql = mysqli_query($dbc,"SELECT * FROM pm_resources WHERE project_id IN
                                                (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no' ORDER BY id DESC)
                                                GROUP BY resource_name");
        while($projects_row = mysqli_fetch_array($projects_sql))
        {
          ?>
          <tr>
            <th scope="row"><?php echo $no++;?></th>
            <td>
              <span>
                <small>
                  <?php echo $projects_row['resource_name'];?>
                </small><br/>
              </span>
            </td>
            <td>
                <?php
                      $total_resource_projects = mysqli_query($dbc,"SELECT project_id FROM pm_resources WHERE
                                                                                    resource_name='".$projects_row['resource_name']."'
                                                                                    &&
                                                                                    project_id IN
                                                                                    (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no'
                                                                                      ORDER BY id DESC)
                                                                                      GROUP BY project_id"
                                                                                    );
                      $total_projects = mysqli_num_rows($total_resource_projects);
                      //echo $total_projects;
                      $p = 1;
                      while($projects = mysqli_fetch_array($total_resource_projects))
                      {
                        $tasks  = mysqli_query($dbc,"SELECT activity_name FROM pm_activities WHERE project_id='".$projects['project_id']."'");
                        $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_name FROM pm_projects WHERE project_id='".$projects['project_id']."'"))
                        ?>
                          <p>
                            <?php
                                  echo $p++ .". ". $name['project_name'] . "<br/>";

                                  //get tasks

                                  while($row_tasks = mysqli_fetch_array($tasks))
                                  {
                                    ?>
                                        <small class="text-muted pl-4"> - <?php echo $row_tasks['activity_name']  ;?></small><br/>
                                    <?php
                                  }
                             ?>


                          </p>
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
