<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/

?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Project Management : Monitor Projects</li>
     </ol>
</nav>

<div class="row">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        Project List
        <button class="btn btn-link" style="float:right;"
                data-toggle="modal" data-target="#add-project-modal">
                <i class="fa fa-plus-circle"></i> Create Project
        </button>
      </div>
      <div class="card-body table-responsive">
       <table class="table table-striped table-bordered table-hover" id="projects-list-table" style="width:100%">
         <thead>
           <tr>
             <td>#</td>
             <td>Project Name</td>
             <td>Project Description</td>
             <td>Project Owner</td>
             <td>Project Phase</td>
             <td>Project Status</td>
             <td>Start Date</td>
             <td>End Date</td>
             <td>Days Due</td>

             <td>Edit</td>
             <td>Delete</td>
           </tr>
         </thead>
         <?php
         $no = 1;
          $sql = mysqli_query($dbc,"SELECT * FROM pm_projects ORDER BY id DESC");
          while($row = mysqli_fetch_array($sql)){
          ?>
         <tr style="cursor: pointer;">
           <td> <?php echo $no++;?> </td>
           <td onclick="ViewProject('<?php echo $row['id'];?>');">
                <span class="text-primary" style="cursor:pointer;"><?php echo $row['project_name'] ;?></span>
           </td>
           <td><?php echo $row['project_description'] ;?></td>
           <td><?php echo $row['project_owner'] ;?></td>
           <td>
             <!-- start project phase modal -->
            <div class="modal fade" id="project-phase-modal-<?php echo $row['project_id'];?>" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Updating Project Status and Phase : <?php echo $row['project_name'];?> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div id="accordion-<?php echo $row['project_id'];?>">
                      <div class="card">
                        <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseOne-<?php echo $row['project_id'];?>">
                          <h5 class="mb-0">
                            <button class="btn btn-link" >
                              <i class="fal fa-users-medical fa-lg"></i> Update Project Phase
                            </button>
                          </h5>
                        </div>

                        <div id="collapseOne-<?php echo $row['project_id'];?>" class="collapse" data-parent="#accordion-<?php echo $row['project_id'];?>">
                          <div class="card-body">
                          <form id="add-project-phase-form-<?php echo $row['project_id'];?>" onsubmit="SubmitProjectPhase('<?php echo $row['project_id'];?>');">

                            <div class="col-md-12 col-xs-12 form-group">
                              <label><span class="required">*</span> Project Phase</label><br/><br/>
                              <select name="project_phase" id="project_phase-<?php echo $row['project_id'];?>" class="form-control">

                                <option selected disabled> --Select Project Phase -- </option>
                                <option value="pre_initiating">Pre initiating</option>
                                <option value="initiating">Initiating</option>
                                <option value="planning">Planning</option>
                                <option value="executing">Executing</option>
                                <option value="closure">Closure</option>
                                <option value="benefits_Tracking">Benefits Tracking</option>
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
                        <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseTwo-<?php echo $row['project_id'];?>">
                          <h5 class="mb-0">
                            <button class="btn btn-link collapsed">
                              <i class="fal fa-tasks fa-lg"></i> Update Project Status
                            </button>
                          </h5>
                        </div>

                        <div id="collapseTwo-<?php echo $row['project_id'];?>" class="collapse" data-parent="#accordion-<?php echo $row['project_id'];?>">
                          <div class="card-body">
                            <form id="add-project-status-form-<?php echo $row['project_id'];?>" onsubmit="SubmitProjectStatus('<?php echo $row['project_id'];?>');">
                              <div class="col-md-12 col-xs-12 form-group">
                                <label><span class="required">*</span> Project Status</label><br/><br/>
                                <select name="project_status" id="project_status-<?php echo $row['project_id'];?>" class="form-control" required>
                                      <option selected disabled> --Select Project Status--</option>
                                      <option value="Active">Active</option>
                                      <option value="Onhold">Onhold</option>
                                      <option value="Rejected">Rejected</option>
                                      <option value="Completed">Completed</option>

                                </select>
                              </div>


                              <div class="col-md-12 mt-6">
                                  <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                              </div>

                            </form>
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
            <!-- end project phase modal  -->
            <?php

            $Proj_phase = mysqli_query($dbc,"SELECT * FROM pm_projects_update WHERE project_id ='".$row['project_id']."' ORDER BY id");
            if(mysqli_num_rows($Proj_phase) > 0)
            {

                           $result = mysqli_query($dbc, "SELECT * FROM pm_projects_update WHERE project_id ='".$row['project_id']."' ORDER BY id DESC LIMIT 1"  );
                           if(mysqli_num_rows($result))
                           {
                             while($project_phase1 = mysqli_fetch_array($result))
                             {
                               ?>
                               <a class="" href="#" data-toggle="modal" data-target="#project-phase-modal-<?php echo $row['project_id'];?>"
                                 title="Click on <?php echo $project_phase1['project_phase'];?> to update project phase">
                               <span class="text-primary" style="cursor:pointer;"><?php echo $project_phase1['project_phase'];?></span>
                               </a>

                               <?php
                             }
                           }
                           ?>
                 <?php
               }
               else {
                 ?>
                 <a class="" href="#" data-toggle="modal" data-target="#project-phase-modal-<?php echo $row['project_id'];?>"
                   title="Click on <?php echo $row['project_phase'];?> to update project phase">
                 <span class="text-primary" style="cursor:pointer;"><?php echo $row['project_phase'];?></span>
                 </a>

                 <?php

               }
               ?>
           </td>
             <td>
              <?php

              $Proj_phase = mysqli_query($dbc,"SELECT * FROM pm_projects_update_status WHERE project_id ='".$row['project_id']."' ORDER BY id");
              if(mysqli_num_rows($Proj_phase) > 0)
              {

                   $result = mysqli_query($dbc, "SELECT * FROM pm_projects_update_status WHERE project_id ='".$row['project_id']."' ORDER BY id DESC LIMIT 1"  );
                   if(mysqli_num_rows($result))
                   {
                     while($project_phase1 = mysqli_fetch_array($result))
                     {
                       ?>
                       <a class="" href="#" data-toggle="modal" data-target="#project-phase-modal-<?php echo $row['project_id'];?>"
                         title="Click on <?php echo $project_phase1['project_status'];?> to update project status">
                       <span class="text-primary" style="cursor:pointer;"><?php echo $project_phase1['project_status'];?></span>
                       </a>

                       <?php
                     }
                   }
                   ?>
            <?php
            }
            else {

            ?>
            <a class="" href="#" data-toggle="modal" data-target="#project-phase-modal-<?php echo $row['project_id'];?>"
            title="Click to update project status">
            <span class="text-primary" style="cursor:pointer;">click to update</span>
            </a>

            <?php

            }
            ?>
          </td>
           <td><?php echo $row['start_date'] ;?></td>
           <td><?php echo $row['end_date'] ;?></td>
           <td>
             <?php
             $todays_date = date('d-M-yy');

             $date1 = new DateTime($row['end_date']); //inclusive
            $date2 = new DateTime($todays_date); //exclusive
            $diff = $date2->diff($date1);
            echo $diff->format("%a");


              ?>


           </td>
  
           <td>
             <button type="button" class="btn btn-link" data-toggle="modal" data-target="#edit-project-modal-<?php echo $row['project_id'];?>">
              <i class="fad fa-edit text-primary"></i>
            </button>


            <!-- edit project modal -->
            <div class="modal fade" id="edit-project-modal-<?php echo $row['project_id'];?>">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Modifying Project <strong> Reference No: <?php echo $row['project_id'];?></strong></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="edit-project-form-<?php echo $row['project_id'];?>" class="mt-4" enctype="multipart/form-data" onsubmit="ModifyProject('<?php echo $row['project_id'];?>');">
                    <input type="hidden" value="edit-project" name="edit-project">
                    <div class="row border-bottom mx-3">
                        <div class="col-lg-4 col-xs-12 form-group">
                          <label for="strategic_objective"><span class="required">*</span>Strategic Objectives</label>
                          <?php
                          $result = mysqli_query($dbc, "SELECT * FROM strategic_objectives ");
                          //mapping objecting with id
                          $mapped_result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM strategic_objectives WHERE
                                                          strategic_objective_id='".$row['strategic_objective_id']."'"));
                          ?>
                          <select name="strategic_objective" id="strategic_objective-<?php echo $row['project_id'];?>" class="select2 form-control">
                          <option value=<?php echo $row['strategic_objective_id'];?> selected><?php echo $mapped_result['strategic_objective_description'];?></option>
                          <?php
                          while($row_result = mysqli_fetch_array($result)) {
                              ?><option value="<?php echo $row_result['strategic_objective_id'];?>"><?php echo $row_result['strategic_objective_description'];?></option><?php
                          }
                          ?>
                          </select>
                        </div>
                        <div class="col-lg-4 col-xs-12 form-group">
                            <label for="project_name"><span class="required">*</span>Project Name</label>

                              <textarea name="project_name" id="project_name-<?php echo $row['project_id'];?>" class="form-control" required><?php echo $row['project_name'];?></textarea>
                        </div>
                        <div class="col-lg-4 col-xs-12 form-group">
                            <label for="project_name"><span class="required">*</span>Project Description</label>

                              <textarea name="project_description" id="project_description-<?php echo $row['project_id'];?>" class="form-control" required><?php echo $row['project_description'];?></textarea>
                        </div>
                    </div>
                    <!-- start row project timelines -->
                    <div class="row border-bottom mx-3">
                      <div class="col-lg-4 col-xs-12 form-group">
                        <label><span class="required">*</span>Project Start Date</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fal fa-calendar-day"></i></span>
                          </div>
                          <input type="text" class="form-control pull-right" id="project_start_date-<?php echo $row['project_id'];?>"
                                  value="<?php echo $row['start_date'];?>" name="project_start_date" required
                                  onchange="ChangeStartDate('<?php echo $row['project_id'] ;?>');"
                                  onmousedown="ChangeStartDate('<?php echo $row['project_id'] ;?>');">
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12 form-group">
                        <label><span class="required">*</span>Project End Date</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fad fa-calendar-day"></i></span>
                          </div>
                          <input type="text" class="form-control pull-right" id="project_end_date-<?php echo $row['project_id'];?>"
                                  value="<?php echo $row['end_date'];?>" name="project_end_date" required
                                  onchange="ChangeEndDate('<?php echo $row['project_id'] ;?>');"
                                  onmousedown="ChangeEndDate('<?php echo $row['project_id'] ;?>');">
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12 form-group">
                          <label><span class="required">*</span>Duration</label>
                          <input type="hidden" class="form-control " name="duration" id="duration-project-duration-in-days-<?php echo $row['project_id'];?>"
                                value="<?php echo $row['duration'];?>" readonly required>
                          <input type="text" class="form-control pull-right bg-grey" id="project-duration-<?php echo $row['project_id'];?>" value="<?php echo $row['duration'];?>" readonly required>
                      </div>
                    </div>
                    <!-- end row project timelines -->

                    <!-- start project budget -->
                    <?php
                    //select from the m_budget table
                    $sql_pm_budget = mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$row['project_id']."'");
                  //  while($budget = mysqli_fetch_array($sql_pm_budget))
                  //  {
                   $budget = mysqli_fetch_array($sql_pm_budget);
                   $budget_id = $budget['id'];
                   $fa = strtoupper($budget['funding_agency']);

                      ?>
                      <div class="row">
                        <small class="status text-success"></small><br/>
                      </div>
                      <div class="row border-bottom mx-3">

                        <div class="col-lg-4 col-xs-12 form-group">
                            <label><span class="required">*</span>Funding Agency</label>
                            <select readonly data-column-name="funding_agency:<?php echo $budget_id ;?>" class="form-control editable" id="funding-agency-<?php echo $row['project_id'];?>" onchange="ChangeFundingAgency('<?php echo $row['id'];?>');" data-placeholder="Select Funding Agency" name="funding_agency">
                              <option value="<?php echo $budget['funding_agency'];?>" selected><?php echo $budget['funding_agency'];?></option>
                              <option value="CMA">CMA</option>
                              <option value="FSSP">FSSP</option>
                              <option value="CMA & FSSP">CMA & FSSP</option>
                            </select>
                        </div>
                        <?php
                        //populate the budget lines according to the funding agency,
                        if($fa == 'CMA')
                        {
                          //display internal budget line
                          ?>
                          <div class="col-lg-4 col-xs-12 form-group" id="internal-budget-<?php echo $row['project_id'];?>">
                              <label>Internal Budget Line</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <select data-column-name="currency_type:<?php echo $budget_id ;?>" class="bg-light text-small fa fa-sm form-control editable" id="internal-currency-<?php echo $row['project_id'];?>" name="internal_currency" style="width:80px;" title="Select Currency">
                                    <option value="<?php echo $budget['currency_type'];?>" selected>-<?php echo $budget['currency_type'];?>-</option>
                                    <option value="KES">(KES) Kshs</option>
                                    <option value="USD">(&#xf155;)Usd</option>
                                    <option value="EURO">(&#xf153;)Euro</option>
                                    <option value="POUND">(&#xf154;)Pound</option>
                                  </select>
                                  <!--<span class="input-group-text">KES:</span>-->
                                </div>
                                <input type="number" data-column-name="amount:<?php echo $budget_id ;?>" min="0" class="form-control editable" id="internal-budget-value-<?php echo $row['project_id'];?>" value="<?php echo $budget['amount'];?>" name="internal_budget">
                                <input type="hidden" value="internal" id="internal-<?php echo $row['project_id'];?>"  name="line[]">
                              </div>
                          </div>
                          <div class="col-lg-4 col-xs-12 form-group d-none" id="external-budget-<?php echo $row['id'];;?>">
                              <label>External Budget Line</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <select data-column-name="currency_type:<?php echo $budget_id ;?>" class="bg-light text-small fa fa-sm form-control editable" id="external-currency-<?php echo $row['project_id'];?>" name="external_currency" style="width:80px;" title="Select Currency">
                                    <option value="">-&#xf3d1;-</option>
                                    <option value="KES">(KES) Kshs</option>
                                    <option value="USD">(&#xf155;)Usd</option>
                                    <option value="EURO">(&#xf153;)Euro</option>
                                    <option value="POUND">(&#xf154;)Pound</option>
                                  </select>
                                </div>
                                <input type="number" data-column-name="amount:<?php echo $budget_id ;?>" min="0" class="form-control editable" id="external-budget-value-<?php echo $row['project_id'];?>" value="0" name="external_budget">
                                <input type="hidden" value="external" id="external-<?php echo $row['project_id'];?>"  name="line[]">
                              </div>
                          </div>
                          <?php
                        }
                        else if(strpos($fa,"CMA") === false)
                        {
                          //display external budget line
                          ?>
                          <div class="col-lg-4 col-xs-12 form-group d-none"  id="internal-budget-<?php echo $row['project_id'];?>">
                              <label>Internal Budget Line</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <select data-column-name="currency_type:<?php echo $budget_id ;?>" class="bg-light text-small fa fa-sm form-control editable" id="internal-currency-<?php echo $row['project_id'];?>" name="internal_currency" style="width:80px;" title="Select Currency">
                                    <option value="">-&#xf3d1;-</option>
                                    <option value="KES">(KES) Kshs</option>
                                    <option value="USD">(&#xf155;)Usd</option>
                                    <option value="EURO">(&#xf153;)Euro</option>
                                    <option value="POUND">(&#xf154;)Pound</option>
                                  </select>
                                  <!--<span class="input-group-text">KES:</span>-->
                                </div>
                                <input data-column-name="amount:<?php echo $budget_id ;?>" type="number" min="0" class="form-control editable" id="internal-budget-value-<?php echo $row['project_id'];?>"  value="0" name="internal_budget">
                                <input type="hidden" value="internal" id="internal-<?php echo $row['project_id'];?>"  name="line[]">
                              </div>
                          </div>
                          <div class="col-lg-4 col-xs-12 form-group" id="external-budget-<?php echo $row['id'];;?>">
                              <label>External Budget Line</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <select data-column-name="currency_type:<?php echo $budget_id ;?>" class="bg-light text-small fa fa-sm form-control editable" id="external-currency-<?php echo $row['project_id'];?>" name="external_currency" style="width:80px;" title="Select Currency">
                                    <option value="<?php echo $row['currency_external'];?>">-<?php echo $budget['currency_type'];?>-</option>
                                    <option value="KES">(KES) Kshs</option>
                                    <option value="USD">(&#xf155;)Usd</option>
                                    <option value="EURO">(&#xf153;)Euro</option>
                                    <option value="POUND">(&#xf154;)Pound</option>
                                  </select>
                                </div>
                                <input data-column-name="amount:<?php echo $budget_id ;?>" type="number" min="0" class="form-control editable" id="external-budget-value-<?php echo $row['project_id'];?>" value="<?php echo $budget['amount'];?>" name="external_budget">
                                <input type="hidden" value="external" id="external-<?php echo $row['project_id'];?>"  name="line[]">
                              </div>
                          </div>
                          <?php
                        }
                        else
                        {
                          //display internal and external budget line
                          $internal = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$row['project_id']."' && budget_line='internal'"));
                          $external = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$row['project_id']."' && budget_line='external'"));
                          ?>
                          <div class="col-lg-4 col-xs-12 form-group" id="internal-budget-<?php echo $row['project_id'];?>">
                              <label>Internal Budget Line</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <select data-column-name="currency_type:<?php echo $internal['id'] ;?>" class="bg-light text-small fa fa-sm form-control editable" id="internal-currency-<?php echo $row['project_id'];?>" name="internal_currency" style="width:80px;" title="Select Currency">
                                    <option value="<?php echo $internal['currency_type'];?>">-<?php echo $internal['currency_type'];?>-</option>
                                    <option value="KES">(KES) Kshs</option>
                                    <option value="USD">(&#xf155;)Usd</option>
                                    <option value="EURO">(&#xf153;)Euro</option>
                                    <option value="POUND">(&#xf154;)Pound</option>
                                  </select>
                                  <!--<span class="input-group-text">KES:</span>-->
                                </div>
                                <input data-column-name="amount:<?php echo $internal['id'] ;?>" type="number" min="0" class="form-control editable" id="internal-budget-value-<?php echo $row['project_id'];?>" value="<?php echo $internal['amount'];?>" name="internal_budget">
                                <input type="hidden" value="internal" id="internal-<?php echo $row['project_id'];?>"  name="line[]">
                              </div>
                          </div>
                          <div class="col-lg-4 col-xs-12 form-group" id="external-budget-<?php echo $row['project_id'];?>">
                              <label>External Budget Line</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <select data-column-name="currency_type:<?php echo $external['id'] ;?>" class="bg-light text-small fa fa-sm form-control editable" id="external-currency-<?php echo $row['project_id'];?>" name="external_currency" style="width:80px;" title="Select Currency">
                                    <option value="<?php echo $external['currency_type'];?>">-<?php echo $external['currency_type'];?>-</option>
                                    <option value="KES">(KES) Kshs</option>
                                    <option value="USD">(&#xf155;)Usd</option>
                                    <option value="EURO">(&#xf153;)Euro</option>
                                    <option value="POUND">(&#xf154;)Pound</option>
                                  </select>
                                </div>
                                <input data-column-name="amount:<?php echo $external['id'] ;?>" type="number" min="0" class="form-control editable" id="external-budget-value-<?php echo $row['project_id'];?>" value="<?php echo $external['amount'];?>" name="external_budget">
                                <input type="hidden" value="external" id="external-<?php echo $row['project_id'];?>"  name="line[]">
                              </div>
                          </div>
                          <?php
                        }
                         ?>
                      </div>
                      <?php
                  //  }

                     ?>

                    <!-- end project budget -->

                    <!--start row project ownerships -->
                    <div class="row border-bottom mx-3">
                      <div class="col-lg-4 col-xs-12 form-group">
                          <label><span class="required">*</span>Project Owner</label>
                          <?php
                          $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                          //mapping objecting with id
                          $mapped_result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM pm_projects WHERE
                                                          project_owner='".$row['project_owner']."'"));
                          ?>
                          <select name="project_owner" id="project_owner-<?php echo $row['project_id'];?>" class="select2 form-control">
                          <option value=<?php echo $row['project_owner'];?> selected><?php echo $mapped_result['project_owner'];?></option>
                          <?php
                          while($row_result = mysqli_fetch_array($result)) {
                              ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                          }
                          ?>
                          </select>
                      </div>
                      <div class="col-lg-4 col-xs-12 form-group">
                          <label><span class="required">*</span>Senior User</label>
                          <?php
                          $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                          //mapping objecting with id
                          $mapped_result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM pm_projects WHERE
                                                          senior_user='".$row['senior_user']."'"));
                          ?>
                          <select data-column-name-projects-user="senior_user:<?php echo $mapped_result['id'];?>" name="senior_user[]" id="senior_user-<?php echo $row['project_id'];?>"
                                  class="select2 form-control editable-project-user" data-tags="true" multiple="multiple">
                          <option value="<?php echo $row['senior_user'];?>" selected><?php echo $row['senior_user'];?></option>
                          <?php
                          while($row_result = mysqli_fetch_array($result)) {
                              ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                          }
                          ?>
                          </select>
                      </div>
                      <div class="col-lg-4 col-xs-12 form-group">
                          <label><span class="required">*</span>Senior Contractor</label>
                          <?php
                          $result = mysqli_query($dbc, "SELECT * FROM pm_contractors  WHERE status='active' ORDER BY contractor_name ASC");
                          //mapping objecting with id
                          $mapped_result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM pm_projects WHERE
                                                          senior_contractor='".$row['senior_contractor']."'"));
                          ?>
                          <select name="senior_contractor" id="senior_contractor-<?php echo $row['project_id'];?>" class="select2 form-control">
                          <option value=<?php echo $row['senior_contractor'];?> selected><?php echo $mapped_result['senior_contractor'];?></option>
                          <?php
                          while($row_result = mysqli_fetch_array($result)) {
                              ?><option value="<?php echo $row_result['contractor_name'];?>"><?php echo $row_result['contractor_name'];?></option><?php
                          }
                          ?>
                          </select>

                      </div>
                      <div class="col-lg-4 col-xs-12 form-group">
                          <label><span class="required">*</span>Project Advisor</label>
                          <?php
                          $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                          //mapping objecting with id
                          $mapped_result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM pm_projects WHERE
                                                          project_advisor='".$row['project_advisor']."'"));
                          ?>
                          <select data-column-name-projects_advisor="project_advisor:<?php echo $mapped_result['id'];?>" name="project_advisor[]" id="project_advisor-<?php echo $row['project_id'];?>"
                                  class="select2 form-control editable-project-advisor" data-tags="true" multiple="multiple">
                          <option value="<?php echo $mapped_result['project_advisor'];?>" selected><?php echo $mapped_result['project_advisor'];?></option>
                          <?php
                          while($row_result = mysqli_fetch_array($result)) {
                              ?><option value="<?php echo $row_result['Name'];?>"><?php echo $row_result['Name'];?></option><?php
                          }
                          ?>
                          </select>
                      </div>
                    </div>
                    <!-- end row project owenerships -->
                    <div class="row">
                      <small class="status-project-user text-success"></small><br/>
                    </div>

                    <!-- start row related activity -->
                    <div class="row border-bottom mx-3 mb-4">
                      <div class="col-lg-12 col-xs-12">
                          <label>Related Workplan Activity</label>
                          <?php
                          $result = mysqli_query($dbc, "SELECT * FROM perfomance_management WHERE activity_status='open' ORDER BY department_id ASC");
                          //mapping objecting with id
                          $mapped_result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM perfomance_management WHERE
                                                          activity_id='".$row['activity_id']."'"));
                          ?>
                          <select name="related_workplan_activity" id="related_workplan_activity-<?php echo $row['project_id'];?>" class="select2 form-control">
                          <option value=<?php echo $row['activity_id'];?> selected><?php echo $mapped_result['activity_description'];?>
                              (<?php echo $mapped_result['department_id'];?>)
                          </option>
                          <?php
                          while($row_result = mysqli_fetch_array($result)) {
                              ?><option value="<?php echo $row_result['activity_id'];?>"><?php echo $row_result['activity_description'];?>
                                (<?php echo $row_result['department_id'];?>)
                              </option><?php
                          }
                          ?>
                          </select>

                      </div>

                    </div>
                    <!-- end row related activity -->

                    <!-- start row project files -->
                  <!--  <div class="row border-bottom mx-3">
                      <div class="col-lg-12 col-xs-12">
                          <label></span>Contract Document</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file project-file">
                                    <i class="fal fa-file-alt"></i>  Browse &hellip;
                                    <input type="file" name="file" class="form-control contract-document" id="contract-document-<?php echo $row['project_id'];?>" single>
                                </span>
                            </span>
                          </div>
                          <input type="text" class="form-control bg-white contract-document-label" id="contract-document-label-<?php echo $row['project_id'];?>" readonly>
                        </div>
                        <div class="row contract-document-error" id="contract-document-error-<?php echo $row['project_id'];?>"></div>

                      </div>

                      <div class="col-lg-12 col-xs-12">
                      <label>Additional Document</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-btn">
                              <span class="btn btn-primary btn-file project-file">
                                  <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" id="additional-file-<?php echo $row['project_id'];?>" class="form-control contract-document" required single>
                              </span>
                          </span>
                        </div>
                        <input type="text" class="form-control bg-white contract-document-label" id="additional-file-label-<?php echo $row['project_id'];?>" readonly>
                      </div>
                      <div class="row contract-document-error" id="additional-file-error-<?php echo $row['project_id'];?>"></div>

                    </div>

                  </div> -->
                    <!-- end row project files -->

                    <div class="pull-left mt-4">
                      <small class="text-muted">Modified by:- <?php echo $_SESSION['name'];?></small>
                    </div>

                          <!-- start row button -->
                    <div class="row">
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-primary btn-block font-weight-bold">SUBMIT
                          </button>
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
            <!-- end of edit project modal -->

           </td>
           <td>
             <a href="#" class="btn btn-link" onclick="CloseProject('<?php echo $row['id'];?>');">
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
</div>



<!-- add project modal -->
<div class="modal fade" id="add-project-modal">
<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <?php
      //fetch last id
     $select_last_id_sql = mysqli_query($dbc,"SELECT project_id,time_recorded FROM pm_projects ORDER BY
                                            time_recorded DESC LIMIT 1");
        $id_row = mysqli_fetch_array($select_last_id_sql);
        $id = $id_row['project_id'];
        $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $int = $int+1;

        $project_id = "PROJ".$int;
       ?>
      <h5 class="modal-title">Creating Project <strong> Reference No: <?php echo $project_id;?></strong></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form id="add-project-form" class="mt-4" enctype="multipart/form-data">
        <input type="hidden" value="add-project" name="add-project">
        <div class="row border-bottom mx-3">
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="strategic_objective"><span class="required">*</span>Strategic Objectives</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM strategic_objectives");
              echo '
              <select name="strategic_objective" class="select2 form-control" data-placeholder="Select Strategic Objective" required>
              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                // we're sending the strategic objective id to the db
                  echo '<option value="'.$row['strategic_objective_id'].'">'.$row['strategic_objective_description']."</option>";
              }
              echo '</select>';
              ?>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label for="project_name"><span class="required">*</span>Project Name</label>

                  <textarea name="project_name" class="form-control" required></textarea>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label for="project_name"><span class="required">*</span>Project Description</label>

                  <textarea name="project_description" class="form-control" required></textarea>
            </div>
        </div>
        <!-- start row project timelines -->
        <div class="row border-bottom mx-3">
          <div class="col-lg-4 col-xs-12 form-group">
            <label><span class="required">*</span>Project Start Date</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fal fa-calendar-day"></i></span>
              </div>
              <input type="text" class="form-control pull-right project_start_date" name="project_start_date" required>
            </div>
          </div>
          <div class="col-lg-4 col-xs-12 form-group">
            <label><span class="required">*</span>Project End Date</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fad fa-calendar-day"></i></span>
              </div>
              <input type="text" class="form-control pull-right project_end_date" name="project_end_date" required>
            </div>
          </div>
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Duration</label>
              <input type="hidden" class="form-control project-duration-in-days" name="duration" readonly required>
              <input type="text" class="form-control pull-right project-duration bg-grey" readonly required>
          </div>
        </div>
        <!-- end row project timelines -->

        <!-- start project budget -->
        <div class="row border-bottom mx-3">
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Funding Agency</label>
              <select class="form-control select2 funding-agency" data-tags="true" data-placeholder="Select Funding Agency" name="funding_agency">
                <option></option>
                <option value="CMA">CMA</option>
                <option value="FSSP">FSSP</option>
                <option value="CMA & FSSP">CMA & FSSP</option>
              </select>
          </div>
          <div class="col-lg-4 col-xs-12 form-group d-none internal-budget">
              <label>Internal Budget Line</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <select class="bg-light text-small fa fa-sm form-control internal-currency" name="currency[]" style="width:80px;" title="Select Currency">
                    <option value="">-&#xf3d1;-</option>
                    <option value="KES">(KES) Kshs</option>
                    <option value="USD">(&#xf155;)Usd</option>
                    <option value="EURO">(&#xf153;)Euro</option>
                    <option value="POUND">(&#xf154;)Pound</option>
                  </select>
                  <!--<span class="input-group-text">KES:</span>-->
                </div>
                <input type="number" min="0" class="form-control internal-budget-value" value="0" name="budget[]">
                <input type="hidden" value="internal" class="internal" name="line[]">
              </div>
          </div>
          <div class="col-lg-4 col-xs-12 form-group d-none external-budget">
              <label>External Budget Line</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <select class="bg-light text-small fa fa-sm form-control external-currency" name="currency[]" style="width:80px;" title="Select Currency">
                    <option value="">-&#xf3d1;-</option>
                    <option value="KES">(KES) Kshs</option>
                    <option value="USD">(&#xf155;)Usd</option>
                    <option value="EURO">(&#xf153;)Euro</option>
                    <option value="POUND">(&#xf154;)Pound</option>
                  </select>
                </div>
                <input type="number" min="0" class="form-control external-budget-value" value="0" name="budget[]">
                <input type="hidden" class="external" value="external" name="line[]">
              </div>
          </div>
        </div>
        <!-- end project budget -->

        <!--start row project ownerships -->
        <div class="row border-bottom mx-3">
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Project Owner</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
              echo '
              <select name="project_owner" data-tags="false" class="select2 form-control" data-placeholder="Select Project Owner" required>
              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'.$row['Name'].'">'.$row['Name']."</option>";
              }
              echo '</select>';
              ?>
          </div>
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Senior User</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
              echo '
              <select name="senior_user[]" data-tags="true" class="select2 form-control" required multiple="multiple"
              data-placeholder="Select user" required>

              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'.$row['Name'].'">'.$row['Name']."</option>";
              }
              echo '</select>';
              ?>
          </div>
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Senior Contractor</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM pm_contractors  WHERE status='active' ORDER BY contractor_name ASC");
              echo '
              <select name="senior_contractor" data-tags="true" class="select2 form-control" data-placeholder="Select Senior Contractor" required>
              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'.$row['contractor_name'].'">'.$row['contractor_name']."</option>";
              }
              echo '</select>';
              ?>
          </div>
          <div class="col-lg-4 col-xs-12 form-group">
              <label><span class="required">*</span>Project Advisor</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM staff_users  WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
              echo '
              <select name="project_advisor[]" data-tags="true" class="select2 form-control" required multiple="multiple"
              data-placeholder="Select user" required>

              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'.$row['Name'].'">'.$row['Name']."</option>";
              }
              echo '</select>';
              ?>
          </div>
          <div class="col-md-4 col-xs-12 form-group">
            <label><span class="required">*</span> Project Phase</label>
            <select name="project_phase" class="form-control">

              <option selected disabled> --Select Project Phase -- </option>
              <option value="pre_initiating">Pre initiating</option>
              <option value="initiating">Initiating</option>
              <option value="planning">Planning</option>
              <option value="executing">Executing</option>
              <option value="closure">Closure</option>
              <option value="benefits_Tracking">Benefits Tracking</option>
            </select>
          </div>
        </div>
        <!-- end row project owenerships -->

        <!-- start row related activity -->
        <div class="row border-bottom mx-3 mb-4">
          <div class="col-lg-12 col-xs-12">
              <label>Related Workplan Activity</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM perfomance_management WHERE activity_status='open' ORDER BY department_id ASC");
              ?>
                <select name="related_workplan_activity" data-tags="false" class="select2 form-control" data-placeholder="Select Related Workplan Activity">
              <?php
              while($row = mysqli_fetch_array($result)) {
                ?>
                  <option></option>
                  <option value="<?php echo $row['activity_id'];?>"> - <?php echo $row['activity_description'];?> (<?php echo $row['department_id'];?>)</option>
                <?php
              }
              ?>
              </select>

          </div>

        </div>
        <!-- end row related activity -->

        <!-- start row project files -->
      <!--  <div class="row border-bottom mx-3">
          <div class="col-lg-12 col-xs-12">
              <label><span class="required">*</span>Contract Document</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file project-file">
                        <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" name="file" class="form-control contract-document" required single>
                    </span>
                </span>
              </div>
              <input type="text" class="form-control bg-white contract-document-label" readonly>
            </div>
            <div class="row contract-document-error"></div>

          </div>
          <div class="col-lg-12 col-xs-12">
          <label>Additional Document</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-btn">
                  <span class="btn btn-primary btn-file project-file">
                      <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" name="additional_file" class="form-control contract-document" required single>
                  </span>
              </span>
            </div>
            <input type="text" class="form-control bg-white contract-document-label" readonly>
          </div>
          <div class="row contract-document-error"></div>

        </div>

      </div> -->
        <!-- end row project files -->

        <div class="pull-left mt-4">
          <small class="text-muted">Recorded by:- <?php echo $_SESSION['name'];?></small>
        </div>

              <!-- start row button -->
        <div class="row">
          <div class="col-md-12 text-center">
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
<!-- end of add project modal -->
