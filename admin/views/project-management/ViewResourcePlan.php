<?php
session_start();
include("../../controllers/setup/connect.php");
$project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['project_id']."'"));
?>
<div class="row">
  <div class="col-lg-12">

  </div>
</div>
<div class="row">
<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Task List

    <!--  <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#resource-utilization-modal">
              <i class="fal fa-user-clock"></i> Resource Utilization
      </button>
    -->
    </div>
    <div class="card-body table-responsive">
     <table class="table table-striped table-bordered table-hover" id="project-task-list-table" style="width:100%">
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
           <td>Comments</td>
         </tr>
       </thead>
       <?php
       $sql_tasks = mysqli_query($dbc,"SELECT * FROM pm_activities
                                            WHERE milestone_id
                                            IN
                                            (SELECT id FROM pm_milestones WHERE project_id='".$project['project_id']."')
              ");
        $no = 1;
        while($row_tasks = mysqli_fetch_array($sql_tasks))
        {
          $milestone = mysqli_fetch_array(mysqli_query($dbc,"SELECT milestone_name FROM pm_milestones WHERE id='".$row_tasks['milestone_id']."'"));
          ?>
          <tr style="cursor: pointer;">
            <td> <?php echo $no++;?>.</td>
            <td>
              <?php echo $row_tasks['activity_name'];?> <br/>
              (<small class="text-primary"><?php echo $row_tasks['task_id'];?></small>)
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
              <a class="btn" href="#" title="Click to Add Resource/Monitor Activity" data-toggle="modal" data-target="#resource-plan-modal-<?php echo $row_tasks['task_id'];?>">
                <i class="fas fa-user-plus text-primary"></i>
              </a><br/>
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


                               <!-- start reosurce plan Modal -->
                               <div class="modal fade" id="resource-plan-modal-<?php echo $row_tasks['task_id'];?>" role="dialog">
                                 <div class="modal-dialog" role="document">
                                   <div class="modal-content">
                                     <div class="modal-header">
                                       <h5 class="modal-title">Monitor Task : <?php echo $row_tasks['activity_name'];?> </h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                         <span aria-hidden="true">&times;</span>
                                       </button>
                                     </div>
                                     <div class="modal-body">
                                       <div id="accordion-<?php echo $row_tasks['task_id'];?>">
                                         <div class="card">
                                           <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseOne-<?php echo $row_tasks['task_id'];?>">
                                             <h5 class="mb-0">
                                               <button class="btn btn-link" >
                                                 <i class="fal fa-users-medical fa-lg"></i> Add Resources
                                               </button>
                                             </h5>
                                           </div>

                                           <div id="collapseOne-<?php echo $row_tasks['task_id'];?>" class="collapse" data-parent="#accordion-<?php echo $row_tasks['task_id'];?>">
                                             <div class="card-body">
                                             <form id="add-resource-form-<?php echo $row_tasks['task_id'];?>" onsubmit="SubmitResource('<?php echo $row_tasks['task_id'];?>');">
                                               <input type="hidden" class="project-id-<?php echo $row_tasks['task_id'];?>" value="<?php echo $project['project_id'];?>">
                                               <div class="col-md-12 col-xs-12 form-group">
                                                 <label><span class="required">*</span> Resources</label><br/><br/>
                                                 <select name="resource_name[]" id="resource_name-<?php echo $row_tasks['task_id'];?>" class="select2 form-control" required multiple="multiple" data-placeholder="Select Resources">
                                                     <?php
                                                       $sql_query = mysqli_query($dbc,"SELECT * FROM staff_users WHERE designation!='TEST USER' && status = 'active'
                                                                                         && Name NOT IN
                                                                                         (SELECT resource_name FROM pm_resources WHERE
                                                                                           activity_id='".$row_tasks['task_id']."')
                                                                                       ORDER BY Name ASC");
                                                       while($row = mysqli_fetch_array($sql_query))
                                                       {
                                                         ?>
                                                           <option value="<?php echo $row['Name'];?>"><?php echo $row['Name'];?></option>

                                                         <?php
                                                       }
                                                      ?>
                                                 </select>
                                               </div>
                                               <div class="col-md-12 mt-5">
                                                   <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                                               </div>

                                             </form>
                                             </div><!--- end of card body -->
                                           </div>
                                         </div>
                                         <div class="card">
                                           <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseTwo-<?php echo $row_tasks['task_id'];?>">
                                             <h5 class="mb-0">
                                               <button class="btn btn-link collapsed">
                                                 <i class="fal fa-tasks fa-lg"></i> Update Task
                                               </button>
                                             </h5>
                                           </div>
                                           <div id="collapseTwo-<?php echo $row_tasks['task_id'];?>" class="collapse" data-parent="#accordion-<?php echo $row_tasks['task_id'];?>">
                                             <div class="card-body">
                                               <form id="add-task-status-form-<?php echo $row_tasks['task_id'];?>" onsubmit="SubmitTaskStatus('<?php echo $row_tasks['task_id'];?>');">
                                                 <div class="col-md-12 col-xs-12 form-group">
                                                   <label><span class="required">*</span> Status</label><br/><br/>
                                                   <select name="task_status" id="task_status-<?php echo $row_tasks['task_id'];?>" class="form-control" required>
                                                         <option selected disabled> --Select Activity Status--</option>
                                                         <option value="Not Started" class="five">Not Started</option>
                                                         <option value="In Progress Behind Schedule" class="four">In Progress Behind Schedule</option>
                                                         <option value="In Progress Within Schedule" class="three">In Progress Within Schedule</option>
                                                         <option value="Completed" class="two">Completed</option>
                                                         <option value="Continous" class="one text-white">Continous</option>
                                                         <option value="Repriotised" class="one text-white">Repriotised</option>
                                                   </select>
                                                 </div>

                                                 <div class="col-md-12 col-xs-12 form-group">
                                                   <label> Comments</label><br/><br/>
                                                   <textarea placeholder="Add Comments" class="form-control" id="add_task_status_comments-<?php echo $row_tasks['task_id'];?>"></textarea>
                                                 </div>
                                                 <div class="col-md-12 mt-6">
                                                     <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                                                 </div>

                                               </form>
                                             </div>
                                           </div>
                                         </div>
                                         <div class="card">
                                           <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseThree-<?php echo $row_tasks['task_id'];?>">
                                             <h5 class="mb-0">
                                               <button class="btn btn-link collapsed text-danger">
                                                 <i class="far fa-align-slash"></i> Remove Task
                                               </button>
                                             </h5>
                                           </div>
                                           <div id="collapseThree-<?php echo $row_tasks['task_id'];?>" class="collapse" data-parent="#accordion-<?php echo $row_tasks['task_id'];?>">
                                             <div class="card-body">
                                               <div class="col-md-12">
                                                 <button class="btn btn-block btn-danger" type="button" onclick="DeleteTask('<?php echo $row_tasks['task_id'];?>');">
                                                   <i class="fad fa-trash-alt"></i> DELETE TASK
                                                 </button>
                                               </div>
                                             </div>
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="modal-footer">
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                     </div>
                                   </div>
                                 </div>
                               </div>
                               <!-- end resource plan modal  -->
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
            <td><?php echo $sql_status['comments'];?></td>
          </tr>
          <?php
        }
        ?>
     </table>
    </div>
  </div>
</div>
</div>

<!-- start resource utilization Modal -->
<div class="modal fade" id="resource-utilization-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title"> Resource Utilization for : <?php echo $project['project_name'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-2">
            <table class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th colspan="2">Total Tasks</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <?php
                      $tasks = mysqli_query($dbc,"SELECT task_id FROM pm_activities WHERE milestone_id IN
                                                            (SELECT id FROM pm_milestones WHERE project_id='".$project['project_id']."')");

                      $total_tasks = mysqli_num_rows($tasks);
                      echo $total_tasks;
                   ?>

                </td>
              </tr>
            </tbody>
            </table>
          </div>
          <div class="col-md-10">
            <table class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th>Resources</th>
                <th>Tasks</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $resources_row = mysqli_query($dbc,"SELECT DISTINCT resource_name FROM pm_resources WHERE activity_id IN
                                                      (SELECT task_id FROM pm_activities WHERE milestone_id IN
                                                          (SELECT id FROM pm_milestones WHERE project_id='".$project['project_id']."'))
                                            ORDER BY resource_name");

              while($resources = mysqli_fetch_array($resources_row))
              {
                ?>
                <tr>
                  <td><?php echo $resources['resource_name'];?></td>
                  <td>
                      <?php
                          $resource_tasks = mysqli_query($dbc,"SELECT resource_name FROM pm_resources WHERE resource_name='".$resources['resource_name']."'");
                          $total_resource_tasks = mysqli_num_rows($resource_tasks);
                          echo $total_resource_tasks;


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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end resource utilization modal -->
