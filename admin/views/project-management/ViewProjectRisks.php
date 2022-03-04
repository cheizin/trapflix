<?php
session_start();
include("../../controllers/setup/connect.php");
$project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['project_id']."'"));
?>

<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Risk List
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-project-risk-modal">
              <i class="fa fa-plus-circle"></i> Add Project Risk
      </button>
    </div>
    <div class="card-body table-responsive">
     <table class="table table-striped table-bordered table-hover" id="project-risk-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Risk Description</td>
           <td>Project Impact</td>
           <td>Project Phase</td>
           <td>Proximity</td>
           <td>Risk Owner</td>
           <td>Overall Score</td>
           <td>Mitigation</td>
           <td>Action Applied</td>
           <td>Status</td>
           <td>Last Update</td>
           <td>Edit</td>
         </tr>
       </thead>
       <?php
            $sql_risks = mysqli_query($dbc,"SELECT * FROM pm_risks WHERE project_id='".$project['project_id']."' ORDER BY time_recorded DESC");
            $no = 1;
            while($risks = mysqli_fetch_array($sql_risks))
            {
              $recent_update = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_risks_updates WHERE risk_id='".$risks['risk_id']."' ORDER BY id DESC LIMIT 1"));
              ?>
              <tr>
                <td width="50px"> <?php echo $no++ ;?>.

                </td>
                <td><?php echo $recent_update['risk_description'] ;?></td>
                <td><?php echo $recent_update['impact'] ;?></td>
                <td><?php echo $recent_update['phase'] ;?></td>
                <td><?php echo $recent_update['proximity'] ;?></td>
                <td><?php echo $recent_update['risk_owner'] ;?></td>
                <td>

                  <?php echo $recent_update['overall_score'] ;?>
                  <p><small>(L<?php echo $recent_update['likelihood_score'] ;?>) * (I<?php echo $recent_update['impact_score'] ;?>)</small></p>

                </td>
                <td><?php echo $recent_update['mitigation_strategy'] ;?></td>
                <td><?php echo $recent_update['actions_applied'] ;?></td>
                <td>
                  <?php
                    if($risks['status'] == 'open')
                    {
                      ?>
                      <span class="badge badge-danger"  style="cursor: pointer;" title="Risk Opened. Click to Close" onclick="ChangeProjectRiskStatus('<?php echo $risks['id'];?>','closed');">Open</span>
                      <?php
                    }
                    else
                    {
                      ?>
                      <span class="badge badge-success"  style="cursor: pointer;"  title="Risk Closed. Click to Open" onclick="ChangeProjectRiskStatus('<?php echo $risks['id'];?>','open');">Closed</span>
                      <?php
                    }

                   ?>
                </td>
                <td><?php echo $recent_update['date_recorded'] ;?></td>
                <td>

                 <?php
                     if($risks['status'] == 'open')
                     {
                       ?>

                       <button type="button" class="btn btn-link" data-toggle="modal" data-target="#edit-project-risk-modal-<?php echo $recent_update['id'] ;?>">
                        <i class="fad fa-edit text-primary"></i>
                      </button>
                       <?php
                     }
                     else
                     {
                       ?>
                       <a class="disabled" disabled title="Risk Closed. Please activate it to monitor" style="cursor: not-allowed;">
                         <i class="fad fa-edit text-primary"></i>
                       </a>
                       <?php
                     }
                  ?>

                 <!-- start edit project risk modal -->
                 <div class="modal fade" id="edit-project-risk-modal-<?php echo $recent_update['id'] ;?>" role="dialog">
                   <div class="modal-dialog modal-lg" role="document">
                     <div class="modal-content">
                       <div class="modal-header">
                         <h5 class="modal-title">Monitoring Risk
                            <span class="font-weight-bold"><?php echo $recent_update['risk_id'];?></span> for <?php echo $project['project_name'];?></h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                         </button>
                       </div>
                       <div class="modal-body">
                         <form id="edit-project-risk-form-<?php echo $recent_update['id'] ;?>" onsubmit="MonitorProjectRisk('<?php echo $recent_update['id'] ;?>');">
                           <input type="hidden" id="project_risk_id-<?php echo $recent_update['id'] ;?>" value="<?php echo $recent_update['risk_id'];?>">
                           <input type="hidden" class="project_id-<?php echo $recent_update['id'] ;?> " value="<?php echo $project['project_id'];?>">
                           <input type="hidden" id="edit_project_risk-<?php echo $recent_update['id'] ;?>" value="edit_project_risk">
                           <!-- start of row -->
                           <div class="row">
                               <div class="col-lg-4 col-xs-12 form-group">
                                   <label><span class="required">*</span>Risk Description</label>
                                   <textarea id="project_risk_description-<?php echo $recent_update['id'] ;?>" class="form-control" required><?php echo $recent_update['risk_description'];?></textarea>
                               </div>
                               <div class="col-lg-4 col-xs-12 form-group">
                                   <label><span class="required">*</span>Impact</label>
                                   <textarea id="project_impact-<?php echo $recent_update['id'] ;?>" class="form-control" required><?php echo $recent_update['impact'];?></textarea>
                               </div>
                               <div class="col-lg-4 col-xs-12 form-group">
                                   <label><span class="required">*</span>Project Phase</label>
                                   <select id="project_phase-<?php echo $recent_update['id'] ;?>" class="select2 form-control" multiple="multiple" data-placeholder="Phase affected by risk">
                                     <option value="<?php echo $recent_update['phase'];?>" selected><?php echo $recent_update['phase'];?></option>
                                     <option value="all">All Phases</option>
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
                                 <label><span class="required">*</span>Proximity</label>
                                 <textarea class="form-control" name="proximity" id="proximity-<?php echo $recent_update['id'] ;?>" required><?php echo $recent_update['proximity'] ;?></textarea>
                             </div>
                           </div>
                           <!-- end of row --

                           <!-- start of row -->
                           <div class="row">
                              <div class="col-lg-4 col-xs-12 form-group">
                                   <label><span class="required">*</span>Person Responsible </label>
                                   <select class="form-control select2" id="person_responsible-<?php echo $recent_update['id'] ;?>" required>
                                       <option value="<?php echo $recent_update['risk_owner'];?>" selected><?php echo $recent_update['risk_owner'];?></option>
                                       <?php
                                           $sql_person = mysqli_query($dbc,"SELECT Name FROM staff_users WHERE status='active' && designation!='TEST USER'");
                                           while($person = mysqli_fetch_array($sql_person))
                                           {
                                             ?>
                                                 <option value="<?php echo $person['Name'];?>"><?php echo $person['Name'];?></option>
                                             <?php
                                           }
                                        ?>
                                   </select>
                               </div>
                               <div class="col-lg-4 col-xs-12 form-group">
                                   <label><span class="required">*</span>Mitigation</label>
                                   <textarea id="risk_mitigations_strategy-<?php echo $recent_update['id'] ;?>" class="form-control" required><?php echo $recent_update['mitigation_strategy'];?></textarea>
                               </div>
                               <div class="col-lg-4 col-xs-12 form-group">
                                   <label><span class="required">*</span>Action Applied</label>
                                   <textarea id="actions_applied-<?php echo $recent_update['id'] ;?>" class="form-control" required><?php echo $recent_update['actions_applied'];?></textarea>
                               </div>
                           </div>
                           <!-- end of row -->

                           <!-- start of row -->
                           <div class="row">
                              <div class="col-lg-4 col-xs-12 form-group">
                                   <label for="likelihood_score"><span class="required">*</span>Likelihood Score </label>
                                   <select class="form-control" id="project-risk-edit-likelihood-score-<?php echo $recent_update['id'] ;?>" onchange="ChangeProjectRiskLikelihoodScore('<?php echo $recent_update['id'];?>');" required>
                                       <option value="<?php echo $recent_update['likelihood_score'];?>" selected><?php echo $recent_update['likelihood_score'];?></option>
                                       <option value="5" class="five">(5) Almost Certain</option>
                                       <option value="4" class="four">(4) Highly Certain</option>
                                       <option value="3" class="three">(3) Likely</option>
                                       <option value="2" class="two">(2) Unlikely</option>
                                       <option value="1" class="one">(1) Rare</option>
                                   </select>
                               </div>
                               <div class="col-lg-4 col-xs-12 form-group">
                                    <label for="impact_score"><span class="required">*</span>Impact Score </label>
                                    <select class="form-control" id="project-risk-edit-impact-score-<?php echo $recent_update['id'] ;?>" onchange="ChangeProjectRiskImpactScore('<?php echo $recent_update['id'];?>');" required>
                                      <option value="<?php echo $recent_update['impact_score'];?>" selected><?php echo $recent_update['impact_score'];?></option>
                                        <option value="1" class="one">(1) Insignificant</option>
                                        <option value="2" class="two">(2) Minor</option>
                                        <option value="3" class="three">(3) Moderate</option>
                                        <option value="4" class="four">(4) Major</option>
                                        <option value="5" class="five">(5) Catastrophic</option>
                                    </select>
                                </div>
                               <div class="col-lg-4 col-xs-12 form-group">
                                 <label for="impact_score"><span class="required">*</span>Overall Score</label>
                                 <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text project-risk-edit-overall-score-prefix-<?php echo $recent_update['id'] ;?>"></span>
                                            <input type="hidden" name="color_rating" id="project-risk-color_rating-<?php echo $recent_update['id'] ;?>">
                                          </div>
                                          <input type="text" class="form-control" id="project-risk-edit-overall-score-<?php echo $recent_update['id'] ;?>"
                                          name="overall_score" value="<?php echo $recent_update['overall_score'];?>" readonly style="background: white;" required>
                                        </div>
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
                 <!-- end edit project risk modal -->




                </td>
              </tr>
              <?php
            }
        ?>
     </table>
    </div>
  </div>
</div>

<!-- start add project risk modal -->
<div class="modal fade" id="add-project-risk-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php
        //fetch last id
       $select_last_id_sql = mysqli_query($dbc,"SELECT risk_id FROM pm_risks ORDER BY
                                              id DESC LIMIT 1");
          $id_row = mysqli_fetch_array($select_last_id_sql);
          $id = $id_row['risk_id'];
          $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
          $int = $int+1;

          $risk_id = "RISK".$int;
         ?>
        <h5 class="modal-title">Adding Risk
           <span class="font-weight-bold"><?php echo $risk_id;?></span> for <?php echo $project['project_name'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-project-risk-form">
          <input type="hidden" name="project_risk_id" value="<?php echo $risk_id;?>">
          <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>">
          <input type="hidden" name="add_project_risk" value="add_project_risk">
          <!-- start of row -->
          <div class="row">
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Risk Description</label>
                  <textarea name="project_risk_description" class="form-control" required></textarea>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Project Impact</label>
                  <textarea name="project_impact" class="form-control" required></textarea>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Project Phase</label>
                  <select name="project_phase[]" data-tags="true" class="select2 form-control" multiple="multiple" data-placeholder="Phase affected by risk">
                    <option value="all">All Phases</option>
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
                <label><span class="required">*</span>Proximity</label>
                <textarea class="form-control" name="proximity" required></textarea>
            </div>
          </div>
          <!-- end of row -->

          <!-- start of row -->
          <div class="row">
             <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Risk Owner </label>
                  <select class="form-control select2" data-placeholder="Select Person Responsible" name="person_responsible" required>
                      <option></option>
                      <?php
                          $sql_person = mysqli_query($dbc,"SELECT Name FROM staff_users WHERE status='active' && designation!='TEST USER'");
                          while($person = mysqli_fetch_array($sql_person))
                          {
                            ?>
                                <option value="<?php echo $person['Name'];?>"><?php echo $person['Name'];?></option>
                            <?php
                          }
                       ?>
                  </select>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Mitigation</label>
                  <textarea name="risk_mitigations_strategy" class="form-control" required></textarea>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                  <label><span class="required">*</span>Action Applied</label>
                  <textarea name="actions_applied" class="form-control" required></textarea>
              </div>
          </div>
          <!-- end of row -->
          <!-- start of row -->
          <div class="row">
             <div class="col-lg-4 col-xs-12 form-group">
                  <label for="likelihood_score"><span class="required">*</span>Likelihood Score </label>
                  <select class="form-control" name="likelihood_score" id="add-likelihood-score" required>
                      <option value="">Select</option>
                      <option value="5" class="five">(5) Almost Certain</option>
                      <option value="4" class="four">(4) Highly Certain</option>
                      <option value="3" class="three">(3) Likely</option>
                      <option value="2" class="two">(2) Unlikely</option>
                      <option value="1" class="one">(1) Rare</option>
                  </select>
              </div>
              <div class="col-lg-4 col-xs-12 form-group">
                   <label for="impact_score"><span class="required">*</span>Impact Score </label>
                   <select class="form-control" name="impact_score" id="add-impact-score" required>
                       <option value="1" class="one">(1) Insignificant</option>
                       <option value="2" class="two">(2) Minor</option>
                       <option value="3" class="three">(3) Moderate</option>
                       <option value="4" class="four">(4) Major</option>
                       <option value="5" class="five">(5) Catastrophic</option>
                   </select>
               </div>
              <div class="col-lg-4 col-xs-12 form-group">
                <label for="impact_score"><span class="required">*</span>Overall Score</label>
                <div class="input-group mb-3">
                         <div class="input-group-prepend">
                           <span class="input-group-text add-overall-score-prefix"></span>
                           <input type="hidden" name="color_rating" id="color_rating">
                         </div>
                         <input type="text" class="form-control" id="add-overall-score" name="overall_score" placeholder="Select Scores" readonly="" style="background: white;" required="">
                       </div>
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
<!-- end add project risk modal -->
