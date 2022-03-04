<?php
session_start();
include("../../controllers/setup/connect.php");
$project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['project_id']."'"));
?>

<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Issue Logs
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-project-issue-logs-modal">
              <i class="fa fa-plus-circle"></i> Add Project Issue Logs
      </button>
    </div>
    <div class="card-body table-responsive">
     <table class="table table-striped table-bordered table-hover" id="project-issue-logs-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Issue Description</td>
           <td>Issue Type</td>
           <td>Date Raised</td>
           <td>Due Date</td>
           <td>Raised by</td>
           <td>Issue Author</td>
           <td>Priority</td>
           <td>Severity</td>
           <td>Next Action</td>
           <td>Person Responsible for next action</td>
           <td>Update</td>
           <td>Status</td>
         </tr>
       </thead>

       <?php
       $no = 1;
        $sql_issue = mysqli_query($dbc,"SELECT * FROM pm_issue_logs WHERE project_id='".$project['project_id']."'");
        while($issue = mysqli_fetch_array($sql_issue))
        {
          $recent_issue = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_issue_logs_updates WHERE issue_id='".$issue['issue_id']."'
                                                                  ORDER BY id DESC LIMIT 1"));
          ?>
          <tr style="cursor: pointer;">
            <td width="50px"><?php echo $no++;?>.</td>
            <td><?php echo $recent_issue['issue_description'];?></td>
            <td><?php echo $recent_issue['issue_type'];?></td>
            <td><?php echo $recent_issue['date_raised'];?></td>
            <td><?php echo $recent_issue['due_date'];?></td>
            <td><?php echo $recent_issue['raised_by'];?></td>
            <td><?php echo $recent_issue['issue_author'];?></td>
            <td><?php echo $recent_issue['priority'];?></td>
            <td><?php echo $recent_issue['severity'];?></td>
            <td><?php echo $recent_issue['next_action'];?></td>
            <td><?php echo $recent_issue['person_responsible'];?></td>
            <td>
              <?php
                  if($issue['status'] == 'open')
                  {
                    ?>
                    <a class="" href="#" data-toggle="modal" data-target="#edit-project-issue-modal-<?php echo $recent_issue['id'];?>">
                      <i class="fad fa-edit text-primary"></i>
                    </a>

                    <!-- start edit project issue log form -->
                    <div class="modal fade" id="edit-project-issue-modal-<?php echo $recent_issue['id'];?>" role="dialog">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Monitoring Issue Log
                               <span class="font-weight-bold"><?php echo $issue['issue_id'];?></span> for <?php echo $project['project_name'];?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form id="edit-project-issue-log-form-<?php echo $recent_issue['id'];?>" onsubmit="MonitorIssueLog('<?php echo $recent_issue['id'];?>');">
                              <input type="hidden" id="issue_id-<?php echo $recent_issue['id'];?>" value="<?php echo $recent_issue['issue_id'];?>">
                              <input type="hidden" class="project_id-<?php echo $recent_issue['id'];?>" value="<?php echo $project['project_id'];?>">
                              <input type="hidden" class="edit_project_issue_log" value="add_project_issue_log">
                              <!-- start of row -->
                              <div class="row">
                                  <div class="col-lg-4 col-xs-12 form-group">
                                      <label><span class="required">*</span>Issue Description</label>
                                      <textarea id="project_issue_description-<?php echo $recent_issue['id'];?>" class="form-control" required><?php echo $recent_issue['issue_description'];?></textarea>
                                  </div>
                                  <div class="col-lg-4 col-xs-12 form-group">
                                      <label><span class="required">*</span>Issue Type</label>
                                      <select id="issue_type-<?php echo $recent_issue['id'];?>" class="form-control">
                                        <option value="<?php echo $recent_issue['issue_type'];?>" selected> <?php echo $recent_issue['issue_type'];?></option>
                                        <option value="Request_for_Change">Request for Change</option>
                                        <option value="Off_Specification">Off-Specification</option>
                                        <option value="Problem">Problem</option>
                                        <option value="Concern">Concern</option>
                                        </select>
                                  </div>
                                  <div class="col-lg-4 col-xs-12 form-group">
                                       <label><span class="required">*</span>Raised by </label>
                                       <?php
                                       $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active'
                                                                      && Name!='".$recent_issue['raised_by']."' ORDER BY Name ASC");
                                       ?>
                                       <select id="raised_by-<?php echo $recent_issue['id'];?>"  class="select2 form-control">
                                         <option value="<?php echo $recent_issue['raised_by'];?>" selected><?php echo $recent_issue['raised_by'];?></option>
                                       <?php
                                       while($row_result = mysqli_fetch_array($result)) {
                                           ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                                       }
                                       ?>
                                       </select>
                                   </div>
                              </div>
                              <!-- end of row -->

                              <!-- start of row -->
                              <div class="row">
                                <div class="col-lg-4 col-xs-12 form-group">
                                  <label> <span class="required">*</span> Date Raised</label>
                                  <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="issue-date-raised-<?php echo $recent_issue['id'];?>"
                                            value="<?php echo $recent_issue['date_raised'];?>"
                                            onmousedown="ChangeIssueDate('<?php echo $recent_issue['id'];?>');"
                                            onchange="ChangeIssueDate('<?php echo $recent_issue['id'];?>');" required>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-xs-12 form-group">
                                  <label> <span class="required">*</span> Due Date</label>
                                  <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="issue-due-date-<?php echo $recent_issue['id'];?>"
                                            value="<?php echo $recent_issue['due_date'];?>"
                                            onmousedown="ChangeIssueDate('<?php echo $recent_issue['id'];?>');"
                                            onchange="ChangeIssueDate('<?php echo $recent_issue['id'];?>');" required>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-xs-12 form-group">
                                    <label><span class="required">*</span>Priority</label>
                                    <select id="priority-<?php echo $recent_issue['id'];?>" class="form-control">
                                      <option value="<?php echo $recent_issue['priority'];?>" selected> <?php echo $recent_issue['priority'];?> </option>
                                      <option value="High">High</option>
                                      <option value="Medium">Medium</option>
                                      <option value="Low">Low</option>

                                    </select>
                                </div>
                                <div class="col-lg-4 col-xs-12 form-group">
                                    <label><span class="required">*</span>Severity</label>
                                    <select id="severity-<?php echo $recent_issue['id'];?>" class="form-control">
                                      <option value="<?php echo $recent_issue['severity'];?>" selected> <?php echo $recent_issue['severity'];?> </option>
                                      <option value="Blocker">Blocker</option>
                                      <option value="Critical">Critical</option>
                                      <option value="Major">Major</option>
                                      <option value="Minor">Minor</option>
                                      <option value="Trivial">Trivial</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-xs-12 form-group">
                                    <label><span class="required">*</span>Next Action</label>
                                    <textarea id="next_action-<?php echo $recent_issue['id'];?>" class="form-control" required><?php echo $recent_issue['next_action'];?></textarea>
                                </div>
                                <div class="col-lg-4 col-xs-12 form-group">
                                     <label><span class="required">*</span>Person Responsible for Next Action </label>
                                     <?php
                                     $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active'
                                                                    && Name!='".$recent_issue['raised_by']."' ORDER BY Name ASC");
                                     ?>
                                     <select id="person_responsible_for_next_action-<?php echo $recent_issue['id'];?>"  class="select2 form-control">
                                       <option value="<?php echo $recent_issue['raised_by'];?>" selected><?php echo $recent_issue['raised_by'];?></option>
                                     <?php
                                     while($row_result = mysqli_fetch_array($result)) {
                                         ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                                     }
                                     ?>
                                     </select>
                                 </div>
                              </div>
                              <!-- end of row -->
                              <div class="row text-center mt-5">
                                  <button type="submit" class="btn btn-primary btn-block mt-5">SUBMIT</button>
                              </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- end edit project issue log form -->
                    <?php
                  }
                  else
                  {
                    ?>
                    <a class="disabled" disabled title="Issue Closed. Please activate it to monitor" style="cursor: not-allowed;">
                      <i class="fad fa-caret-circle-down text-default"></i>
                    </a>
                    <?php
                  }
               ?>
            </td>
            <td>
              <?php
                if($issue['status'] == 'open')
                {
                  ?>
                  <span class="badge badge-danger"  style="cursor: pointer;" title="Issue Opened. Click to Close"
                        onclick="ChangeIssueLogStatus('<?php echo $issue['id'];?>','closed');">Open</span>
                  <?php
                }
                else
                {
                  ?>
                  <span class="badge badge-success"  style="cursor: pointer;"  title="Issue Closed. Click to Open"
                        onclick="ChangeIssueLogStatus('<?php echo $issue['id'];?>','open');">Closed</span>
                  <?php
                }

               ?>
            </td>
          </tr>
          <?php
        }
        ?>
     </table>
    </div>
  </div>
</div>

<!-- start add project issue log form -->
<div class="modal fade" id="add-project-issue-logs-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php
        //fetch last id
         $select_last_id_sql = mysqli_query($dbc,"SELECT issue_id FROM pm_issue_logs ORDER BY
                                              id DESC LIMIT 1");
          $id_row = mysqli_fetch_array($select_last_id_sql);
          $id = $id_row['issue_id'];
          $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
          $int = $int+1;

          $issue_id = "ISSUE".$int;
         ?>
        <h5 class="modal-title">Adding Issue Log
           <span class="font-weight-bold"><?php echo $issue_id;?></span> for <?php echo $project['project_name'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="add-project-issue-log-form">
          <input type="hidden" name="issue_id" value="<?php echo $issue_id;?>">
          <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>">
          <input type="hidden" name="add_project_issue_log" value="add_project_issue_log">
          <!-- start of row -->
          <div class="row">
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Issue Description</label>
                  <textarea name="project_issue_description" class="form-control" required></textarea>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Issue Type</label>
                  <select name="issue_type" class="form-control">
                    <option disabled selected> --Select Issue Type -- </option>
                    <option value="Request_for_Change">Request for Change</option>
                    <option value="Off_Specification">Off-Specification</option>
                    <option value="Problem">Problem</option>
                  </select>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                   <label><span class="required">*</span>Raised by </label>
                   <?php
                   $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                   ?>
                   <select name="raised_by"  class="select2 form-control">
                   <?php
                   while($row_result = mysqli_fetch_array($result)) {
                       ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                   }
                   ?>
                   </select>
               </div>
          </div>
          <!-- end of row -->

          <!-- start of row -->
          <div class="row">
            <div class="col-lg-4 col-xs-12 form-group">
              <label> <span class="required">*</span> Date Raised</label>
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                </div>
                <input type="text" class="form-control issue-date-raised" name="issue_date_raised" required>
              </div>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label> <span class="required">*</span> Due Date</label>
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                </div>
                <input type="text" class="form-control issue-due-date" name="issue_due_date" required>
              </div>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Priority</label>
                <select name="priority" class="form-control">
                  <option disabled selected> --Select Issue Priority -- </option>
                  <option value="High">High</option>
                  <option value="Medium">Medium</option>
                  <option value="Low">Low</option>
                </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Severity</label>
                <select name="severity" class="form-control">
                  <option disabled selected> --Select Issue Severity -- </option>
                  <option value="Blocker">Blocker</option>
                  <option value="Critical">Critical</option>
                  <option value="Major">Major</option>
                  <option value="Minor">Minor</option>
                  <option value="Trivial">Trivial</option>
                </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Next Action</label>
                <textarea name="next_action" class="form-control" required></textarea>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                 <label><span class="required">*</span>Person Responsible for Next Action </label>
                 <?php
                 $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                 ?>
                 <select name="person_responsible_for_next_action"  class="select2 form-control">
                 <?php
                 while($row_result = mysqli_fetch_array($result)) {
                     ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                 }
                 ?>
                 </select>
             </div>
          </div>
          <!-- end of row -->
          <div class="row text-center mt-5">
              <button type="submit" class="btn btn-primary btn-block mt-5">SUBMIT</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end add project issue log form -->
