<?php
session_start();
include("../../controllers/setup/connect.php");
$project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['project_id']."'"));
?>

<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Lessons Learnt
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-project-lessons-learnt-modal">
              <i class="fa fa-plus-circle"></i> Add Project Lessons Learnt
      </button>
    </div>
    <div class="card-body table-responsive">
     <table class="table table-striped table-bordered table-hover" id="project-lessons-learnt-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Lesson Description</td>
           <td>Date</td>
           <td>Project Phase</td>
           <td>Lesson Type</td>
           <td>Impact to Project</td>
           <td>Related Risk</td>
           <td>Response</td>
           <td>Actions Applied</td>
           <td>Edit</td>
           <td>Delete</td>
         </tr>
       </thead>
       <?php
          $no = 1;
          $sql_lessons = mysqli_query($dbc,"SELECT * FROM pm_lessons_learnt WHERE project_id='".$project['project_id']."' ORDER BY id DESC");
          while($lesson = mysqli_fetch_array($sql_lessons))
          {
            ?>
            <tr style="cursor: pointer;">
              <td width="40px"><?php echo $no++ ;?>.</td>
              <td><?php echo $lesson['lesson_description'];?></td>
              <td><?php echo $lesson['lesson_date'];?></td>
              <td><?php echo $lesson['phase'];?></td>
              <td><?php echo $lesson['lesson_type'];?></td>
              <td><?php echo $lesson['impact_to_project'];?></td>
              <td>
                <?php
                $risk_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT risk_id,risk_description FROM pm_risks_updates
                                                                      WHERE risk_id='".$lesson['related_risk']."' ORDER BY id DESC LIMIT 1"));
                 echo $risk_name['risk_description'];

                 ?>

              </td>
              <td><?php echo $lesson['response'];?></td>
              <td><?php echo $lesson['actions_applied'];?></td>
              <td>
                <a class="" href="#" data-toggle="modal" data-target="#edit-project-lesson-modal-<?php echo $lesson['id'] ;?>">
                  <i class="fad fa-edit text-primary"></i>
                </a>
                <!-- start edit project lesson learnt modal -->
                <div class="modal fade" id="edit-project-lesson-modal-<?php echo $lesson['id'] ;?>" role="dialog">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Modifying Lesson Learnt
                           <span class="font-weight-bold"><?php echo $lesson['lesson_learnt_id'];?></span> for <?php echo $project['project_name'];?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="edit-project-lesson-learnt-form-<?php echo $lesson['id'];?>"
                          onsubmit="ModifyProjectLesson('<?php echo $lesson['id'];?>');">
                          <!-- start of row -->
                          <div class="row">
                              <div class="col-lg-4 col-xs-12 form-group">
                                  <label><span class="required">*</span>Lesson Description</label>
                                  <textarea id="project_lesson_description-<?php echo $lesson['id'];?>" class="form-control" required><?php echo $lesson['lesson_description'];?></textarea>
                              </div>
                              <div class="col-lg-4 col-xs-12 form-group">
                                <label> <span class="required">*</span> Lesson Date</label>
                                <div class="input-group mb-2 mr-sm-2">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                                  </div>
                                  <input type="text" class="form-control" id="project_lesson_date-<?php echo $lesson['id'];?>"
                                        value="<?php echo $lesson['lesson_date'];?>"
                                        onmousedown="ChangeProjectLessonDate('<?php echo $lesson['id'];?>');"
                                        onchange="ChangeProjectLessonDate('<?php echo $lesson['id'];?>');" required>
                                </div>
                              </div>
                              <div class="col-lg-4 col-xs-12 form-group">
                                  <label><span class="required">*</span>Project Phase</label>
                                  <select id="project_phase-<?php echo $lesson['id'];?>" class="form-control">
                                    <option value="<?php echo $lesson['phase'];?>" selected> <?php echo $lesson['phase'];?></option>
                                    <option value="pre_initiating">Pre initiating</option>
                                    <option value="initiating">Initiating</option>
                                    <option value="planning">Planning</option>
                                    <option value="executing">Executing</option>
                                    <option value="closure">Closure</option>
                                    <option value="benefits_Tracking">Benefits Tracking</option>
                                  </select>
                              </div>
                          </div>
                          <!-- end of row -->

                          <!-- start of row -->
                          <div class="row">
                            <div class="col-lg-4 col-xs-12 form-group">
                                <label><span class="required">*</span>Lesson Type</label>
                                <select id="lesson_type-<?php echo $lesson['id'];?>" class="form-control">
                                  <option value="<?php echo $lesson['lesson_type'];?>" selected> <?php echo $lesson['lesson_type'];?></option>
                                  <option value="Type 1">Type 1</option>
                                  <option value="Type 2">Type 2</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-xs-12 form-group">
                                <label><span class="required">*</span>Lesson Impact to Project</label>
                                <textarea id="lesson_impact-<?php echo $lesson['id'];?>" class="form-control" required><?php echo $lesson['impact_to_project'];?></textarea>
                            </div>
                             <div class="col-lg-4 col-xs-12 form-group">
                                  <label><span class="required">*</span>Related Risk </label>
                                  <select class="form-control select2"  id="related_risk-<?php echo $lesson['id'];?>" required>
                                    <?php
                                        $mapped_result = mysqli_fetch_array(mysqli_query($dbc,"SELECT risk_id,risk_description FROM pm_risks_updates
                                                                                              WHERE risk_id='".$lesson['related_risk']."' ORDER BY id DESC LIMIT 1"));
                                    ?>
                                      <option value="<?php echo $mapped_result['risk_id'];?>" selected><?php echo $mapped_result['risk_description'];?></option>
                                      <?php
                                          $sql_risks = mysqli_query($dbc,"SELECT risk_id FROM pm_risks WHERE status='open' ORDER BY id DESC");
                                          while($risk = mysqli_fetch_array($sql_risks))
                                          {
                                            $risk_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT risk_id,risk_description FROM pm_risks_updates
                                                                                                  WHERE risk_id='".$risk['risk_id']."'
                                                                                                  && risk_id!='".$lesson['related_risk']."' ORDER BY id DESC LIMIT 1"));
                                            ?>
                                                <option value="<?php echo $risk['risk_id'];?>"><?php echo $risk_name['risk_description'];?></option>
                                            <?php
                                          }
                                       ?>
                                  </select>
                              </div>

                              <div class="col-lg-4 col-xs-12 form-group">
                                  <label><span class="required">*</span>Response</label>
                                  <textarea id="response-<?php echo $lesson['id'];?>" class="form-control" required><?php echo $lesson['response'];?></textarea>
                              </div>

                              <div class="col-lg-4 col-xs-12 form-group">
                                  <label><span class="required">*</span>Actions Applied</label>
                                  <textarea id="actions_applied-<?php echo $lesson['id'];?>" class="form-control" required><?php echo $lesson['actions_applied'];?></textarea>
                              </div>
                          </div>
                          <!-- end of row -->
                          <div class="row text-center">
                              <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end edit project lesson learnt modal -->
              </td>
              <td>
                <a href="#" class="btn btn-link" onclick="DeleteProjectLesson('<?php echo $lesson['id'];?>');">
                   <i class="fad fa-trash-alt text-danger"></i>
                </a>
              </td>
            </tr>
            <?php
          }
        ?>
     </table>
    </div>
  </div>
</div>


<!-- start add project lesson learnt modal -->
<div class="modal fade" id="add-project-lessons-learnt-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php
        //fetch last id
         $select_last_id_sql = mysqli_query($dbc,"SELECT lesson_learnt_id FROM pm_lessons_learnt ORDER BY
                                              id DESC LIMIT 1");
          $id_row = mysqli_fetch_array($select_last_id_sql);
          $id = $id_row['lesson_learnt_id'];
          $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
          $int = $int+1;

          $lesson_learnt_id = "LESS".$int;
         ?>
        <h5 class="modal-title">Adding Lesson Learnt
           <span class="font-weight-bold"><?php echo $lesson_learnt_id;?></span> for <?php echo $project['project_name'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-project-lesson-learnt-form">
          <input type="hidden" name="project_lesson_learnt_id" value="<?php echo $lesson_learnt_id;?>">
          <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>">
          <input type="hidden" name="add_project_lesson_learnt" value="add_project_lesson_learnt">
          <!-- start of row -->
          <div class="row">
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Lesson Description</label>
                  <textarea name="project_lesson_description" class="form-control" required></textarea>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                <label> <span class="required">*</span> Lesson Date</label>
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                  </div>
                  <input type="text" class="form-control project-lesson-date" name="project_lesson_date" required>
                </div>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Project Phase</label>
                  <select name="project_phase" class="form-control">
                    <option disabled selected> --Select Project Phase -- </option>
                    <option value="pre_initiating">Pre initiating</option>
                    <option value="initiating">Initiating</option>
                    <option value="planning">Planning</option>
                    <option value="executing">Executing</option>
                    <option value="closure">Closure</option>
                    <option value="benefits_Tracking">Benefits Tracking</option>
                  </select>
              </div>
          </div>
          <!-- end of row -->

          <!-- start of row -->
          <div class="row">
            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Lesson Type</label>
                <select name="lesson_type" class="form-control">
                  <option disabled selected> --Select Lesson Type -- </option>
                  <option value="Threat">Threat</option>
                  <option value="Opportunity">Opportunity</option>
                </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Lesson Impact to Project</label>
                <textarea name="lesson_impact" class="form-control" required></textarea>
            </div>
             <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Related Risk </label>
                  <select class="form-control select2" data-placeholder="Select Lesson Related Risk" name="related_risk" required>
                      <option></option>
                      <?php
                          $sql_risks = mysqli_query($dbc,"SELECT risk_id FROM pm_risks WHERE status='open' ORDER BY id DESC");
                          while($risk = mysqli_fetch_array($sql_risks))
                          {
                            $risk_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT risk_description FROM pm_risks_updates
                                                                                  WHERE risk_id='".$risk['risk_id']."' ORDER BY id DESC LIMIT 1"));
                            ?>
                                <option value="<?php echo $risk['risk_id'];?>"><?php echo $risk_name['risk_description'];?></option>
                            <?php
                          }
                       ?>
                  </select>
              </div>

              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Response</label>
                  <textarea name="response" class="form-control" required></textarea>
              </div>

              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Actions Applied</label>
                  <textarea name="actions_applied" class="form-control" required></textarea>
              </div>
          </div>
          <!-- end of row -->
          <div class="row text-center">
              <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end add project lesson learnt modal -->
