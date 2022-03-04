 <?php
 session_start();
 include("../../controllers/setup/connect.php");
 $project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['project_id']."'"));
 ?>

<div class="col-lg-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Milestone List

      <button class="btn btn-link" style="float:right;" data-toggle="modal" data-target="#add-project-milestone-modal">
        <i class="fa fa-plus-circle"></i> Add Milestone</button>
    </div>
    <div class="card-body table-responsive">

      <table class="table table-bordered table-sm" id="milestone-management-table">
          <thead class="thead-light">
              <tr>
                  <th>#</th>
                  <th>Milestone</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Days Due</th>
                  <th>Status</th>
                  <th>Resource Name</th>
                  <th>Edit</th>
                  <th>Delete</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $milestone_sql = mysqli_query($dbc,"SELECT * FROM pm_milestones WHERE project_id='".$project['project_id']."'");
            while($milestone_row = mysqli_fetch_array($milestone_sql))
            {
              ?>
              <tr class="clickable font-weight-bold milestone-table" style="cursor:pointer;" data-toggle="collapse"
              data-target="#group-of-rows-<?php echo $milestone_row['id'];?>" aria-expanded="false" aria-controls="group-of-rows-<?php echo $milestone_row['id'];?>">
                  <td><?php echo $no++ ;?></td>
                  <td><?php echo $milestone_row['milestone_name'];?></td>
                  <td><?php echo $milestone_row['start_date'];?></td>
                  <td><?php echo $milestone_row['end_date'];?></td>
                  <td>
                    <?php
                    $todays_date = date('d-M-yy');
                    $date1 = new DateTime($milestone_row['end_date']); //inclusive
                    $date2 = new DateTime($todays_date); //exclusive
                    $diff = $date2->diff($date1);
                    echo $diff->format("%a");

                     ?>

                  </td>
                  <td> - </td>
                  <td> - </td>
                  <td>
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#edit-project-milestone-modal-<?php echo $milestone_row['id'];?>">
                     <i class="fad fa-edit text-primary"></i>
                   </button>


                   <!-- start edit project milestone modal -->
                   <div class="modal fade" id="edit-project-milestone-modal-<?php echo $milestone_row['id'];?>">
                   <div class="modal-dialog modal-lg" role="document">
                     <div class="modal-content">
                       <div class="modal-header">
                         <h5 class="modal-title">Modifying Milestone: <span class="font-weight-bold"><?php echo $milestone_row['milestone_name'];?></span></h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                         </button>
                       </div>

                       <div class="modal-body">
                         <form id="edit-project-milestone-form-<?php echo $milestone_row['id'];?>" class="mt-4" onsubmit="ModifyMilestone('<?php echo $milestone_row['id'] ;?>')">
                           <div class="row">
                             <div class="form-group col-md-12 col-xs-12">
                               <label><span class="required">*</span>Milestone Name</label>
                               <textarea class="form-control" id="milestone_name-<?php echo $milestone_row['id'] ;?>" name="milestone_name" placeholder="Milestone Name" required><?php echo $milestone_row['milestone_name'];?></textarea>
                               <input type="hidden" class="id" value="<?php echo $project['id'];?>">

                             </div>
                             <div class="form-group col-md-4 col-xs-12">
                               <label> <span class="required">*</span> Start Date</label>
                               <div class="input-group mb-2 mr-sm-2">
                                 <div class="input-group-prepend">
                                   <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                                 </div>
                                 <input type="text" id="milestone-start-date-<?php echo $milestone_row['id'];?>" class="form-control bg-white msd"
                                        name="start_date" value="<?php echo $milestone_row['start_date'];?>"
                                        onchange="ChangeMilestoneStartDate('<?php echo $milestone_row['id'] ;?>');"
                                        onmousedown="ChangeMilestoneStartDate('<?php echo $milestone_row['id'] ;?>');" readonly required>

                                 <input type="hidden" id="project_milestone_start_date-<?php echo $milestone_row['id'] ;?>" value="<?php echo $project['start_date'];?>" required>
                               </div>

                             </div>
                             <div class="form-group col-md-4 col-xs-12">
                               <label><span class="required">*</span>End Date</label>
                               <div class="input-group mb-2 mr-sm-2">
                                 <div class="input-group-prepend">
                                   <div class="input-group-text"><i class="fad fa-calendar-day"></i></div>
                                 </div>
                                 <input type="text" id="milestone-end-date-<?php echo $milestone_row['id'];?>" class="form-control bg-white med"
                                        name="end_date" value="<?php echo $milestone_row['end_date'];?>"
                                        onchange="ChangeMilestoneEndDate('<?php echo $milestone_row['id'] ;?>');"
                                        onmousedown="ChangeMilestoneEndDate('<?php echo $milestone_row['id'] ;?>');" readonly required>

                                 <input type="hidden" id="project_milestone_end_date-<?php echo $milestone_row['id'] ;?>" value="<?php echo $project['end_date'];?>" required>
                               </div>
                             </div>
                             <div class="col-lg-4 col-xs-12 form-group">
                                 <label><span class="required">*</span>Duration</label>
                                 <input type="hidden" id="milestone-duration-in-days-<?php echo $milestone_row['id'] ;?>" class="form-control" name="duration" value="<?php echo $milestone_row['duration'];?>" readonly required>
                                 <input type="text" id="milestone-duration-<?php echo $milestone_row['id'] ;?>" class="form-control pull-right bg-white" value="<?php echo $milestone_row['duration'];?>" readonly required>
                             </div>
                           </div>
                            <!--<a href="#" id="edit-milestone-activity"><i class="fa fa-plus" style="float: right;"></i></a><br/>-->
                            <div class="row">
                              <small class="status-task text-success"></small><br/>
                            </div>
                           <?php
                                $sql_milestone_tasks = mysqli_query($dbc,"SELECT * FROM pm_activities WHERE milestone_id='".$milestone_row['id']."'");
                                while($tasks = mysqli_fetch_array($sql_milestone_tasks))
                                {
                                  ?>

                                  <div class="row">
                                    <div class="form-group col-md-4 col-xs-12">
                                      <label><span class="required">*</span>Activity Name</label>
                                      <textarea data-column-name-task="activity_name:<?php echo $tasks['task_id'];?>" class="form-control editable-task" name="activity_name[]" id="activity_name" required><?php echo $tasks['activity_name'];?></textarea>
                                    </div>
                                    <div class="form-group col-md-4 col-xs-12">
                                      <label> <span class="required">*</span> Start Date</label>
                                      <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                                        </div>
                                        <input type="text" data-column-name-task="start_date:<?php echo $tasks['task_id'];?>"
                                                class="form-control editable-task bg-white" id="activity-start-date-<?php echo $tasks['task_id'];?>"
                                                name="activity_start_date[]" value="<?php echo $tasks['start_date'];?>"
                                                onchange="ChangeActivityStartDate('<?php echo $tasks['task_id'];?>');"
                                                onmousedown="ChangeActivityStartDate('<?php echo $tasks['task_id'];?>');" readonly required>
                                      </div>
                                    </div>
                                    <div class="form-group col-md-4 col-xs-12">
                                      <label><span class="required">*</span>End Date</label>
                                      <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text"><i class="fad fa-calendar-day"></i></div>
                                        </div>
                                        <input type="text" data-column-name-task="end_date:<?php echo $tasks['task_id'];?>"
                                                class="form-control editable-task bg-white" id="activity-end-date-<?php echo $tasks['task_id'];?>"
                                                name="activity_end_date[]" value="<?php echo $tasks['end_date'];?>"
                                                onchange="ChangeActivityEndDate('<?php echo $tasks['task_id'];?>');"
                                                onmousedown="ChangeActivityEndDate('<?php echo $tasks['task_id'];?>');" readonly required>
                                      </div>
                                      <input type="hidden" data-column-name-task="duration:<?php echo $tasks['task_id'];?>"
                                              onclick="ChangeActivityDuration('<?php echo $tasks['task_id'];?>');"
                                              id="activity-duration-in-days-<?php echo $tasks['task_id'];?>" class="form-control editable-task"
                                              name="duration" value="<?php echo $tasks['duration'];?>" required>
                                        <span id="status-task-<?php echo $tasks['task_id'];?>" class="text-success"></span>
                                    </div>
                                  </div>
                                  <?php
                                }

                            ?>



                                <div class="row mt-5">
                                 <div class="col-sm-12 text-center">
                                     <button type="submit" class="btn btn-primary btn-block" onclick="ModifyMilestone('<?php echo $milestone_row['id'] ;?>');">SUBMIT</button>
                                 </div>
                           </div>
                         </form>
                       </div>
                       <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                       </div>
                     </div>
                   </div>
                   </div>
                   <!-- end edit project milestone  modal -->

                  </td>
                  <td>
                      <a href="#" class="btn btn-link" onclick="DeleteMilestone('<?php echo $milestone_row['id'];?>','<?php echo $project['id'];?>');">
                         <i class="fad fa-trash-alt text-danger"></i>
                      </a>
                  </td>
              </tr>
          </tbody>
          <tbody id="group-of-rows-<?php echo $milestone_row['id'];?>" class="collapse">
            <?php
            $sql_tasks = mysqli_query($dbc,"SELECT * FROM pm_activities WHERE milestone_id='".$milestone_row['id']."'");
            while($tasks_row = mysqli_fetch_array($sql_tasks))
            {
              ?>
              <tr class="table-warning">
                <td><?php echo $tasks_row['task_id'] ;?></td>
                <td>  <?php echo $tasks_row['activity_name'] ;?></td>
                <td><?php echo $tasks_row['start_date'] ;?></td>
                <td><?php echo $tasks_row['end_date'] ;?></td>
                <td>
                  <?php
                  $todays_date = date('d-M-yy');
                  $date1 = new DateTime($tasks_row['end_date']); //inclusive
                  $date2 = new DateTime($todays_date); //exclusive
                  $diff = $date2->diff($date1);
                  echo $diff->format("%a");
                  ?>
                </td>
                <td>
                    <?php
                        $activity_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT status FROM pm_activity_updates
                                                                                            WHERE task_id='".$tasks_row['task_id']."'
                                                                                            ORDER BY id DESC LIMIT 1"));

                      echo $activity_row['status'];

                     ?>
                </td>
                <td>
                    <?php
                        $resource_count = 1;
                        $resource_name = mysqli_query($dbc,"SELECT resource_name FROM pm_resources WHERE activity_id='".$tasks_row['task_id']."' GROUP BY resource_name");
                        while($resource_row = mysqli_fetch_array($resource_name))
                        {
                          echo $resource_count ++  ." ." ;
                          echo $resource_row['resource_name'] . "<br/>";
                        }
                     ?>
                </td>
                <td>-</td>
                <td>-</td>
              </tr>
              <?php
            }

             ?>
          </tbody>
          <?php
        }
        ?>
      </table>
    </div>
  </div>

</div>


<!-- add project milestone modal -->
<div class="modal fade" id="add-project-milestone-modal">
<div class="modal-dialog modal-lg modal-dialog-scrollable " role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Add Milestone and Activities</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <form id="add-project-milestone-form" class="mt-4">
        <div class="row">
          <div class="form-group col-md-12 col-xs-12">
            <label><span class="required">*</span>Milestone Name</label>
            <textarea class="form-control" name="milestone_name" placeholder="Milestone Name" required></textarea>
            <input type="hidden" id="id" value="<?php echo $project['id'];?>">
            <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>" required>
            <input type="hidden" name="add-milestone" value="add-milestone">
          </div>
        </div>
          <div class="row">

          <div class="form-group col-md-4 col-xs-12">
            <label> <span class="required">*</span> Milestone Start Date</label>
            <div class="input-group mb-2 mr-sm-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
              </div>
              <input type="text" class="form-control milestone-start-date" name="start_date">
              <input type="hidden" id="project_start_date" value="<?php echo $project['start_date'];?>" required>
            </div>

          </div>
          <div class="form-group col-md-4 col-xs-12">
            <label><span class="required">*</span>Milestone End Date</label>
            <div class="input-group mb-2 mr-sm-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fad fa-calendar-day"></i></div>
              </div>
              <input type="text" class="form-control milestone-end-date" name="end_date">
              <input type="hidden" id="project_end_date" value="<?php echo $project['end_date'];?>" required>
            </div>
          </div>
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Duration</label>
              <input type="hidden" class="form-control milestone-duration-in-days" name="duration" readonly required>
              <input type="text" class="form-control pull-right milestone-duration bg-white" readonly required>
          </div>
        </div>
        <div class="row">
          <!--<a href="#" id="add-milestone-tasks">Add Tasks</a>-->
          <!--<a href="#" id="add-milestone-activity"><i class="fa fa-plus" style="float: right;"></i>Add Tasks </a>-->
        </div>

        <div id="accordion-tasks">
          <div class="card">
            <button class="btn btn-link" data-toggle="collapse" data-target="#add-tasks" type="button">
              Add Tasks
            </button>
            <div id="add-tasks" class="collapse" data-parent="#accordion-tasks">
              <div class="card-body">
                <div class="row">
                  <div class="form-group col-md-4 col-xs-12">
                    <label><span class="required">*</span>Activity Name</label>
                    <textarea class="form-control" name="activity_name[]" placeholder="Activity Name" required></textarea>
                  </div>
                  <div class="form-group col-md-4 col-xs-12">
                    <label> <span class="required">*</span> Start Date</label>
                    <div class="input-group mb-2 mr-sm-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                      </div>
                      <input type="text" class="form-control activity-start-date" name="activity_start_date[]" required>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-xs-12">
                    <label><span class="required">*</span>End Date</label>
                    <div class="input-group mb-2 mr-sm-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fad fa-calendar-day"></i></div>
                      </div>
                      <input type="text" class="form-control activity-end-date" name="activity_end_date[]" required>
                    </div>
                    <a href="#" id="add-milestone-activity"><i class="fa fa-plus" style="float: right;"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="dynamic-form-activities">

        </div>


        <div class="row mt-5">
              <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-primary btn-block" id="submit-milestone-button">SUBMIT</button>
              </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
<!-- end add project milestone modal-->


<script>
var html_activities = `
      <div style="border-top: 1px dashed #8c8b8b;">
        <div class="row">
          <div class="form-group col-md-4 col-xs-12">
            <label><span class="required">*</span>Activity Name</label>
            <textarea class="form-control" name="activity_name[]" placeholder="Activity Name" required></textarea>
          </div>
          <div class="form-group col-md-4 col-xs-12">
            <label> <span class="required">*</span> Start Date</label>
            <div class="input-group mb-2 mr-sm-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
              </div>
              <input type="text" class="form-control activity-start-date" name="activity_start_date[]" required>
            </div>
          </div>
          <div class="form-group col-md-4 col-xs-12">
            <label><span class="required">*</span>End Date</label>
            <div class="input-group mb-2 mr-sm-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fad fa-calendar-day"></i></div>
              </div>
              <input type="text" class="form-control activity-end-date" name="activity_end_date[]" required>
            </div>
          </div>
        </div>
          <a href="#" id="remove-activity" class="btn btn-link text-danger" title="Remove">
              <i class="fa fa-minus" style="float: right;"></i>
          </a>
        </div>`;

</script>
