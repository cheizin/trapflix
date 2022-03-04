
<?php
session_start();
include("../../controllers/setup/connect.php");
if(!isset($_POST['id']))
{
  exit("Please select project");
}

$project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['id']."'"));
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">

      <li class="breadcrumb-item active" aria-current="page">
      <button class="btn btn-link"
            data-toggle="modal" data-target="#add-project-file-modal" title="Click to Upload Project document">
            <i class="fal fa-file-plus fa-lg text-success"></i> <strong><?php echo $project['project_name'];?></strong>
    </button>
    </li>
      <li>
    <?php
        $sql_project_files = mysqli_query($dbc,"SELECT id,file FROM pm_project_documents WHERE project_id='".$project['project_id']."'");
        while ($row_files = mysqli_fetch_array($sql_project_files))
        {
          ?>

          <a class="border-bottom" href="views/project-management/documents/<?php echo $row_files['file'] ;?>" target="_blank" title="Click to view <?php echo $row_files['file'] ;?>"><?php echo $row_files['file'] ;?></a>
          <a class="btn" href="#" onclick="RemoveProjectFile('<?php echo $row_files['id'] ;?>','<?php echo $row_files['file'] ;?>');" title="Click to remove <?php echo $row_files['file'] ;?> "><i class="fas fa-file-times text-danger"></i></a>

          <?php
        }


     ?>
       </li>
      <!-- Modal -->
      <div class="modal fade" id="add-project-file-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Project Documents For <?php echo $project['project_name'];?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form onsubmit="SubmitProjectFiles('<?php echo $project['project_id'];?>');" enctype='multipart/form-data' id="submit-project-files-form-<?php echo $project['project_id'];?>">
                <!-- start row project files -->
              <div class="row border-bottom mx-3">
                <small class="text-muted">(Attach Project Files i.e Contract Documents and Other Relevant Documents)</small><br/><br/>
                <div class="col-md-12">
                  <div data-role="dynamic-fields">
                        <div class="form-inline form-row">
                          <!-- file upload start-->
                          <div class="mb-2 mr-sm-2 col-sm-10 wrap-input-container">
                            <label class="custom-file-upload form-control">
                              <i class="fal fa-file-upload"></i> Upload Document
                            </label>
                            <input class="file-upload file-name-<?php echo $project['project_id'];?>" name="file_name[]" type="file">
                          </div>
                     <!-- file upload ends-->
                          <button class="btn btn-sm btn-danger mr-4 mb-2" data-role="remove" title="Click to remove this file">
                           <i class="fa fa-minus"></i>
                          </button>
                          <button class="btn btn-sm btn-primary  mb-2" data-role="add" title="Click to Add another file">
                              <i class="fa fa-plus"></i>
                          </button>

                        </div>  <!-- /div.form-inline -->
                    </div>  <!-- /div[data-role="dynamic-fields"] -->
                </div>
              </div>
              <!-- end row project files -->
              <!-- start row button -->
              <div class="row">
              <div class="col-md-12 text-center"><br/><br/>
              <button type="submit" class="btn btn-primary btn-block font-weight-bold">SUBMIT</button>
              </div>
              </div>

              <!-- end row button -->

              </form>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

     </ol>
</nav>
<br/>

<input type="hidden" name="project-id" class="project-id" value="<?php echo $project['id'] ;?>">


<div class="row">
  <div class="col-lg-12">
    <ul class="nav nav-tabs nav-fill" role="tablist">
      <li class="nav-item">
        <a class="nav-link milestones-tab" data-toggle="tab" href="#milestones-tab"
          role="tab" aria-selected="false">
           <i class="fad fa-map-signs fa-lg"></i> Milestones
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link project-resource-plan-tab" data-toggle="tab" href="#project-resource-plan-tab" role="tab"
           aria-selected="false">
           <i class="fad fa-people-carry fa-lg"></i> Resource Plan
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link project-risks-tab" data-toggle="tab" href="#project-risks-tab" role="tab"
           aria-selected="false">
           <i class="fad fa-exclamation-triangle fa-lg"></i> Risks
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link project-lessons-learnt-tab" data-toggle="tab" href="#project-lessons-learnt-tab" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="nav-icon fal fa-chalkboard-teacher fa-lg"></i> Lessons learnt
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link project-issue-logs-tab" data-toggle="tab" href="#project-issue-logs-tab" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="fad fa-pennant fa-lg"></i> Issue Logs
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link project-payments-tab" data-toggle="tab" href="#project-payments-tab" role="tab"
            aria-controls="contact" aria-selected="false">
           <i class="fad fa-money-check-alt fa-lg"></i>  Payments
        </a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade" id="milestones-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="project-resource-plan-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="project-risks-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="project-lessons-learnt-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="project-issue-logs-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="project-payments-tab" role="tabpanel"></div>

    </div>

  </div>
</div>




<!--PROJECT MODALS -->
