<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

  //dashboard queries
  //all  projects
  $projects_sql = mysqli_query($dbc,"SELECT * FROM pm_projects");
  //active projects
  $active_projects_sql = mysqli_query($dbc,"SELECT * FROM pm_projects WHERE project_id IN
                                                    (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')");

  $total_projects = mysqli_num_rows($projects_sql);
  $active_projects = mysqli_num_rows($active_projects_sql);

  //resources
  $resources_sql = mysqli_query($dbc,"SELECT * FROM pm_resources WHERE activity_id IN
                                              (SELECT task_id FROM pm_activities WHERE milestone_id IN
                                              (SELECT id FROM pm_milestones WHERE project_id IN
                                              (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')))
                                              GROUP BY resource_name");

  $active_resources = mysqli_num_rows($resources_sql);




  //risks
  $risks_sql = mysqli_query($dbc,"SELECT * FROM pm_risks WHERE status='open'");
  $active_risks = mysqli_num_rows($risks_sql);

  //cricical risks
  $critical_risks_sql = mysqli_query($dbc,"SELECT risk_id FROM pm_risks_updates
                                                  WHERE  risk_id IN
                                                  (SELECT risk_id FROM pm_risks WHERE status='open')
                                                  && overall_score >=20
                                                  && changed='no' ");

  $critical_risks = mysqli_num_rows($critical_risks_sql);


  //pie chart for task status
  $task_status = mysqli_query($dbc,"SELECT task_id, count(*) AS tasks,status FROM pm_activity_updates WHERE changed='no'
                                           && project_id IN
                                           (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')
                                           GROUP BY status");

  while ($row_task_status = mysqli_fetch_array($task_status))
  {
    $count_tasks[] = $row_task_status['tasks'];
    $task_status_description[] = $row_task_status['status'];
  }
  $counted_tasks = json_encode($count_tasks);
  $task_status_descriptions = json_encode($task_status_description);

  //donut chart resource utilization per project in active projects
  $resource_utilization_sql = mysqli_query($dbc,"SELECT project_id, count(*) AS no_of_projects FROM pm_resources
                                                      WHERE project_id IN
                                                      (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')
                                                      GROUP BY project_id");
  $data = array();
  while($row_resources = mysqli_fetch_array($resource_utilization_sql))
  {
    $project_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_name FROM pm_projects WHERE project_id='".$row_resources['project_id']."'"));
    /*$data[] = array(
   'label' => $project_name['project_description'],
   'value' => $row_resources['no_of_projects']
   );
*/
   $d[] = $row_resources['no_of_projects'];
   $e[] = $project_name['project_name'];

  }
  //$json_data = json_encode($data);  // convert to json array
  $dd = json_encode($d);
  $ee = json_encode($e);


  //get event dates from pm_activites
  $a = mysqli_query($dbc,"SELECT * FROM pm_activities WHERE project_id IN
                                (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')
                                 ");
$d1 = array();
  while($row_tasks = mysqli_fetch_array($a))
        {
        $date0= date_create($row_tasks['start_date']);
        $date1= date_format($date0,"Y-m-d");


        $date00= date_create($row_tasks['end_date']);
        $date11= date_format($date00,"Y-m-d");


          $name[] = $row_tasks['activity_name'];
          $date[] = $date1;


        //  $d1[]['startDate'] = $date1;
        //  $d1[]['endDate'] = $date11;
        //  $d1[]['summary'] = $row_tasks['activity_name'];
        $d1[] =  array (
            'startDate' =>  $date1,
            'endDate' => $date11,
            'summary' => $row_tasks['activity_name']
          );

        }
                                                      //$json_data = json_encode($data);  // convert to json array
        $activity_name = json_encode($name);
        $start_date = json_encode($date);
        $end_date = json_encode($date11);

        $calendar =  json_encode($d1);
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Project Management Dashboard</li>
     </ol>
</nav>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box" title="Total Projects - Active and Closed">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-project-diagram"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">Total Projects</span>
            <span class="info-box-number">
              <?php echo $total_projects ;?>
              <small>(<span class="text-success"><?php echo $active_projects ;?> Active</span>)</small>
            </span>
            <small class="float-right text-primary"><a href="#" class="open-total-projects-modal" data-toggle="modal" data-target="#total-projects-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- Total Projects Modal -->
        <div class="modal fade" id="total-projects-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title">Active Projects</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="total-projects-modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of total projects omdal -->

      </div>
      <!-- /.col -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3" title="Projects Payments for Active Projects">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-money-check-edit-alt"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">Projects Payments</span>
            <span class="info-box-number open-dashboard-project-payments-modal text-primary" data-toggle="modal" data-target="#dashboard-project-payments-modal" style="cursor:pointer;"><i class="fas fa-file-invoice-dollar"></i></span>
            <small class="float-right text-primary"><a href="#" class="open-dashboard-project-payments-modal" data-toggle="modal" data-target="#dashboard-project-payments-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- projects payments Modal -->
        <div class="modal fade" id="dashboard-project-payments-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title">Projects Payments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="dashboard-project-payments-modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of projects payments omdal -->
      </div>
      <!-- /.col -->

      <!-- fix for small devices only -->
      <div class="clearfix hidden-md-up"></div>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3" title="Total Resources for Active Projects">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">Resources</span>
            <span class="info-box-number"><?php echo $active_resources;?></span>
            <small class="float-right text-primary"><a href="#" class="open-dashboard-project-resources-modal" data-toggle="modal" data-target="#dashboard-project-resources-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- Total Project resources  Modal -->
        <div class="modal fade" id="dashboard-project-resources-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title">Project Resources</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="dashboard-project-resources-modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of total project resources modal -->
      </div>
      <!-- /.col -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3" title="Open Risks for Active Projects">
          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">Project Risks</span>
            <span class="info-box-number"><?php echo $active_risks;?>
              <small>(<span class="text-danger"><?php echo $critical_risks ;?> High Risk</span>)</small>
            </span>
            <small class="float-right text-primary"><a href="#" class="open-total-project-risks-modal" data-toggle="modal" data-target="#total-project-risks-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- Total Project Risks  Modal -->
        <div class="modal fade" id="total-project-risks-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title">Active Risks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="total-project-risks-modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of total project Risks omdal -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Resource Utilization</h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <p class="text-center">
                  <strong>Resource Distribution per Project</strong>
                </p>
                <div class="chart">
                  <!-- Resource Distribution Chart Canvas -->
                  <canvas id="resource-distribution-chart" width="477" height="300" style=" display: block;" class="chartjs-render-monitor"</canvas>
                </div>
                <!-- /.chart-responsive -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <p class="text-center">
                  <strong>Resource Summary</strong><br/>(<small class="text-muted">Task Distribution Per Resource </small>)
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-striped table-bordered" id="resource-summary-table">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Resource</th>
                        <th scope="col">Tasks</th>
                        <th scope="col">Health</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                          //task Distribution for tasks not completed
                          $resource_list_sql = mysqli_query($dbc,"SELECT resource_name, count(*) AS no_of_tasks FROM pm_resources
                                                                        WHERE
                                                                        project_id IN
                                                                        (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')
                                                                        GROUP BY resource_name ORDER BY resource_name ASC");

                          $no = 1;
                          while($resource_list_row = mysqli_fetch_array($resource_list_sql))
                          {
                            $resource = mysqli_fetch_array(mysqli_query($dbc,"SELECT EmpNo, Name FROM staff_users WHERE Name='".$resource_list_row['resource_name']."'"));
                            $resource_project_list_sql = mysqli_query($dbc,"SELECT project_id, count(*) AS no_of_projects FROM pm_resources
                                                                                  WHERE
                                                                                  resource_name='".$resource_list_row['resource_name']."'
                                                                                  GROUP BY project_id");

                            $resource_task_list_sql = mysqli_query($dbc,"SELECT activity_id, count(*) AS no_of_tasks FROM pm_resources
                                                                                WHERE
                                                                                resource_name='".$resource_list_row['resource_name']."'
                                                                                &&
                                                                                project_id IN
                                                                                (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')
                                                                                GROUP BY activity_id");

                           $resource_task_completed_sql = mysqli_query($dbc,"SELECT task_id FROM pm_activity_updates WHERE status='Completed' && changed='no'
                                                                                 && task_id IN
                                                                                 (SELECT activity_id FROM pm_resources WHERE resource_name='".$resource_list_row['resource_name']."'
                                                                                   &&
                                                                                   project_id IN
                                                                                   (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no'))
                                                                                 ORDER BY id DESC");

                            $resource_task_active_sql = mysqli_query($dbc,"SELECT task_id FROM pm_activity_updates WHERE status!='Completed' && changed='no'
                                                                                  && task_id IN
                                                                                (SELECT activity_id FROM pm_resources WHERE resource_name='".$resource_list_row['resource_name']."'
                                                                                  &&
                                                                                  project_id IN
                                                                                  (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no'))
                                                                                ORDER BY id DESC");

                            $resource_project_list = mysqli_num_rows($resource_project_list_sql);
                            $resource_task_list = mysqli_num_rows($resource_task_list_sql);
                            $resource_task_completed = mysqli_num_rows($resource_task_completed_sql);
                            $resource_task_active = mysqli_num_rows($resource_task_active_sql);
                            ?>
                            <tr>
                              <th scope="row"><?php echo $no++;?></th>
                              <td style="cursor:pointer;">
                                <small class="text-primary resource-name-link"  data-toggle="modal" data-target="#resource-name-<?php echo $resource['EmpNo'];?>"
                                        onclick="LoadResourceTasks('<?php echo $resource_list_row['resource_name'];?>');">
                                  <?php echo $resource['Name'];?>

                                </small>
                                <!-- Resource Infor Modal -->
                                <div class="modal fade" id="resource-name-<?php echo $resource['EmpNo'];?>"  role="dialog">
                                  <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-light">
                                           <h5 class="modal-title pl-3"> <i class="fal fa-user-circle fa-lg" style="font-size:40px;"></i>
                                             <?php echo $resource_list_row['resource_name'];?>  - Resource Plan
                                           </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body tasks-per-resource-modal-body">

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>


                              </td>
                              <td><small><?php echo $resource_list_row['no_of_tasks'];?></small></td>
                              <td>
                                <?php
                                    if($resource_list_row['no_of_tasks'] < 3)
                                    {
                                      //good
                                      ?>
                                        <i class="fas fa-smile text-success" title="This resource is not overworked"></i> <small>good</small>
                                      <?php
                                    }
                                    else if($resource_list_row['no_of_tasks'] > 2 && $resource_list_row['no_of_tasks'] < 4)
                                    {
                                      //neutral
                                      ?>
                                        <i class="fas fa-meh text-warning" title="This resource is on standby"></i> <small>neutral</small>
                                      <?php
                                    }
                                    else if($resource_list_row['no_of_tasks'] > 3)
                                    {
                                      //overworked
                                      ?>
                                        <i class="fas fa-tired text-danger" title="This resource might be overworked"></i> <small>overworked</small>
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
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- ./card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- start row task status -->
    <div class="row">
      <div class="col-sm-6 col-12">
        <div class="card bg-gradient-light">
           <div class="card-header border-0">
              <h3 class="card-title">
                 <i class="far fa-tasks"></i>
                 Tasks Status
              </h3>
              <!-- tools card -->
              <div class="card-tools">
                 <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                 <i class="fas fa-minus"></i>
                 </button>
              </div>
              <!-- /. tools -->
           </div>
           <!-- /.card-header -->
           <div class="card-body pt-0">
             <div class="chart">
               <!-- Resource Distribution Chart Canvas -->
               <canvas id="task-status-chart" height="300" style="height: 300px; display: block; width: 577px;" class="chartjs-render-monitor" width="577"></canvas>
               <!--<div id="chart" height="250" style="height: 250px;"></div>-->
             </div>
             <!-- /.chart-responsive -->
           </div>
           <!-- /.card-body -->
        </div>
      </div>

      <div class="col-sm-6 col-12">
        <div class="card bg-gradient-light">
           <div class="card-header border-0">
              <h3 class="card-title">
                 <i class="far fa-calendar-exclamation text-danger"></i>
                 Overdue Tasks
              </h3>
              <!-- tools card -->
              <div class="card-tools">
                 <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                 <i class="fas fa-minus"></i>
                 </button>
              </div>
              <!-- /. tools -->
           </div>
           <!-- /.card-header -->
           <div class="card-body pt-0">
             <table class="table table-hover table-striped table-bordered" id="dashboard-overdue-tasks-table">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Task</th>
                    <th scope="col">Due</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $due_sql = mysqli_query($dbc,"SELECT id,task_id FROM pm_activity_updates WHERE changed='no' && status='In Progress Behind Schedule'
                                                            && project_id IN
                                                            (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')");
                      $no = 1;
                      $count = 1;
                      while($due_row = mysqli_fetch_array($due_sql))
                      {

                        $task_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT activity_name,end_date FROM pm_activities WHERE task_id='".$due_row['task_id']."'"));

                        $resources = mysqli_query($dbc,"SELECT resource_name FROM pm_resources WHERE activity_id='".$due_row['task_id']."'");

                        ?>
                        <tr>
                          <th scope="row"><?php echo $no++;?></th>
                          <td>
                            <span  tabindex="0" data-html="true" data-toggle="popover" data-trigger="focus" class="text-primary"
                                  data-content="<div class='text-primary' style='cursor:pointer;'>
                                      <?php
                                          while($tied_resources = mysqli_fetch_array($resources))
                                          {
                                            echo $count++ ." . ". $tied_resources['resource_name'] . "<br/>";
                                          }

                                       ?>
                                  </div>"
                                  style="cursor:pointer;" data-original-title="<b>Tied Resources</b>">
                                   <small class="text-primary"><?php echo $task_name['activity_name'];?></small>
                                  <br/>
                            </span>
                          </td>
                          <td>
                            <?php
                            $todays_date = date('d-M-yy');

                            $date1 = new DateTime($task_name['end_date']); //inclusive
                           $date2 = new DateTime($todays_date); //exclusive
                           $diff = $date2->diff($date1);

                           $days = $diff->format("%R");
                           if($days == "-")
                           {
                             ?>
                              <span class="badge badge-danger" data-toggle="tooltip" title="This task was supposed to end in <?php echo $task_name['end_date'] ;?>"><?php echo $diff->format("%R%a") . " Days";?></span>
                             <?php
                           }
                           else
                           {
                             ?>
                             <span class="badge badge-warning" data-toggle="tooltip" title="This task is supposed to end in <?php echo $task_name['end_date'] ;?>"><?php echo $diff->format("%R%a") . " Days";?></span>
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
           </div>
           <!-- /.card-body -->
        </div>
      </div>
    </div>

    <!-- end row task status -->

    <!-- start row calendar -->
    <div class="row">
      <div class="col-sm-6 col-12">
        <div class="card bg-gradient-light">
           <div class="card-header border-0">
              <h3 class="card-title">
                 <i class="far fa-calendar-alt"></i>
                 Tasks Calendar
              </h3>
              <!-- tools card -->
              <div class="card-tools">
                 <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                 <i class="fas fa-minus"></i>
                 </button>
              </div>
              <!-- /. tools -->
           </div>
           <!-- /.card-header -->
           <div class="card-body pt-0" style="display: block;">
              <!--The calendar -->
              <div id="task-calendar"></div>
           </div>
           <!-- /.card-body -->
        </div>
      </div>
      <div class="col-sm-6 col-12">
        <div class="card bg-gradient-light">
           <div class="card-header border-0">
              <h3 class="card-title">
                 <i class="fa fa-bug text-danger"></i>
                 Open Issues
              </h3>
              <!-- tools card -->
              <div class="card-tools">
                 <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                 <i class="fas fa-minus"></i>
                 </button>
              </div>
              <!-- /. tools -->
           </div>
           <!-- /.card-header -->
           <div class="card-body pt-0" style="display: block;">
             <table class="table table-striped" id="dashboard-open-issues-table">
               <thead>
                 <tr>
                   <th scope="col">#</th>
                   <th scope="col">Issue</th>
                   <th scope="col">Priority</th>
                   <th scope="col">Severity</th>
                 </tr>
               </thead>
               <tbody>
                 <?php
                  $no = 1;
                    $open_issues_sql = mysqli_query($dbc,"SELECT * FROM pm_issue_logs_updates WHERE changed='no' &&
                                                                    issue_id IN
                                                                    (SELECT issue_id FROM pm_issue_logs WHERE status='open')
                                                                    ORDER BY id DESC");
                    while($open_issues = mysqli_fetch_array($open_issues_sql))
                    {
                      $project = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_name FROM pm_projects WHERE project_id='".$open_issues['project_id']."'"));
                      ?>
                      <tr>
                        <th scope="row"><?php echo $no++;?></th>
                        <td>
                          <span tabindex="0" data-html="true" data-toggle="popover" data-trigger="focus"
                                data-content="<div class='monitor-projects-link text-primary' style='cursor:pointer;'><?php echo $project['project_name'];?> </div>"
                                style="cursor:pointer;" data-original-title="<b>Affected Project</b>">
                            <small class="text-primary">
                              <?php echo $open_issues['issue_description'];?>
                            </small><br/>
                          </span>
                            <small class="text-muted">
                              (<?php
                              $issue_type = str_replace("_", " ", $open_issues['issue_type']);
                              echo $issue_type;
                              ?>)
                            </small>
                        </td>
                        <td>
                          <?php
                          if($open_issues['priority'] == 'High')
                          {
                            ?>
                              <i class="fas fa-arrow-up text-danger faa-flash animated"></i> <small class="text-danger"><?php echo $open_issues['priority'];?></small>
                            <?php
                          }
                          else if($open_issues['priority'] == 'Medium')
                          {
                            ?>
                            <i class="fas fa-arrows-h text-warning"></i> <small class="text-warning"><?php echo $open_issues['priority'];?></small>
                            <?php
                          }
                          else if($open_issues['priority'] == 'Low')
                          {
                            ?>
                            <i class="fas fa-arrow-down text-success"></i> <small class="text-success"><?php echo $open_issues['priority'];?></small>
                            <?php
                          }
                          ?>

                        </td>
                        <td>
                          <?php
                          if($open_issues['severity'] == 'Blocker')
                          {
                            ?>
                              <span class="badge five"><?php echo $open_issues['severity'];?></span>
                            <?php
                          }
                          else if($open_issues['severity'] == 'Critical')
                          {
                            ?>
                              <span class="badge four"><?php echo $open_issues['severity'];?></span>
                            <?php
                          }
                          else if($open_issues['severity'] == 'Major')
                          {
                            ?>
                              <span class="badge three"><?php echo $open_issues['severity'];?></span>
                            <?php
                          }
                          else if($open_issues['severity'] == 'Minor')
                          {
                            ?>
                              <span class="badge two"><?php echo $open_issues['severity'];?></span>
                            <?php
                          }
                          else if($open_issues['severity'] == 'Trival')
                          {
                            ?>
                              <span class="badge one"><?php echo $open_issues['severity'];?></span>
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
           </div>
           <!-- /.card-body -->
        </div>
      </div>
    </div>
    <!-- end row calendar -->
  </div>
   <!-- /.container fluid -->
</section>
   <!-- /.section -->


<script type="application/javascript">

  /*  Morris.Donut({
      element: 'chart',  // div id
      data: <?php echo $json_data; ?>,
      xkey: 'label',
      ykeys: ['value'],
      labels: ['Value'],
      resize: true
  });
  */

</script>

<script>
/*
var ctx = document.getElementById('myChart');
var pieChartCanvas = new Chart(ctx, {
    type: 'doughnut',
    data: <?php echo $json_data;?>,

});
*/


// Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#resource-distribution-chart').get(0).getContext('2d');
  var pieData        = {
    labels: <?php echo $ee;?>,
    datasets: [
      {
        fill: false,
        data: <?php echo $dd;?>,
        //backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
      }
    ]
  }
  var pieOptions     = {
    responsive: false,
    legend: {
      display: true,
      position: 'bottom',
      labels: {
          fontColor: '#333',
          usePointStyle:true
      }
    },
    plugins: {

      colorschemes: {

        scheme: 'tableau.HueCircle19'

      }

    }
  }


  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  })

//START TASK STATUS CHART
var pieChartCanvas = $('#task-status-chart').get(0).getContext('2d');
var pieData        = {
  labels: <?php echo $task_status_descriptions;?>,
  datasets: [
    {
      fill: false,
      data: <?php echo $counted_tasks;?>,
      //backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
    }
  ]
}
var pieOptions     = {
  legend: {
    display: true,
    position: 'bottom',
    labels: {
        fontColor: '#333',
        usePointStyle:true
    }
  },
  plugins: {

    colorschemes: {

      scheme: 'brewer.DarkTwo8'

    }

  }
}
//Create pie or douhnut chart
// You can switch between pie and douhnut using the method below.
var pieChart = new Chart(pieChartCanvas, {
  type: 'pie',
  data: pieData,
  options: pieOptions
})


//END TASK STATUS CHART

  //task calendar
  $("#task-calendar").simpleCalendar({
    fixedStartDay: false,
    disableEmptyDetails: true,
    events:  <?php echo $calendar;?>,


});

$(document).on("click",'.btn-next, .btn-prev', function(e){
  e.preventDefault();
})

$("[data-toggle=popover]").popover();

</script>
