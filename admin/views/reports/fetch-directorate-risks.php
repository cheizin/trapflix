<?php
session_start();
include("../../controllers/setup/connect.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  $select_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['directorate_id']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['year_id']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['quarter_id']));

  $sql = mysqli_query($dbc,"SELECT * FROM directors_risk_table WHERE directors_cumulative_id IS NULL
                                          && directorate_id='".$select_directorate."'
                                          && year_id='".$year_id."'
                                          && quarter_id='".$quarter_id."'");
  if($sql)
  {
    $total_rows = mysqli_num_rows($sql);
    if($total_rows > 0)
    {

    ?>

    <!-- start insert a page break -->
    <!-- end insert a page break -->
    <div class="row">
      <div class="col-md-6">
    <div class="card">
     <div class="card-header with-border">
       <h3 class="card-title">Directorate Risks With Activities:<br/>
         <?php echo $select_directorate;?>
       </h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="directorate_risks_with_activities_table" style="overflow:hidden" >
            <thead>
              <tr>
                <td>NO</td>
                <td>Remove</td>
                <td>Strategic Objective</td>
                <td>Related Risk</td>
                <td>Activity Description</td>
              </tr>
            </thead>
            <?php
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
                <tr id="row-<?php echo $row['directors_risk_id'];?>">
                  <td><?php echo $number++ ;?></td>
                  <td>
                        <?php
                        $sql_directors_directorate = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directorates WHERE
                                                      director_id = '".$_SESSION['staff_id']."' "));

                        $directors_directorate = $sql_directors_directorate['directorate_id'];
                        $directors_name_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directorates WHERE
                                                                    directorate_id='".$select_directorate."'"));
                        $directors_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users
                                                                 WHERE EmpNo='".$directors_name_row['director_id']."'"));

                        $directors_delegation_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM delegations WHERE
                                                              delegated_from_name='".$directors_id['Name']."'
                                                              && delegated_to_name='".$_SESSION['name']."'
                                                              && status='active'"));
                        $directors_delegation = $directors_delegation_row['delegated_to_name'];

                        if( $directors_directorate == $select_directorate || $_SESSION['access_level'] == 'superuser'  || $_SESSION['access_level'] == 'admin' || $directors_delegation == $_SESSION['name'])
                            {
                              ?>
                              <form id="remove-from-directorate-form-<?php echo $row['directors_risk_id'];?>">
                                  <input type="hidden" id="directors_risk_id-<?php echo $row['directors_risk_id'];?>" value="<?php echo $row['directors_risk_id'];?>">
                                  <input type="hidden" id="activity_id-<?php echo $row['directors_risk_id'];?>" value="<?php echo $row['activity_id'];?>">
                                  <input type="hidden" id="risk_ref-<?php echo $row['directors_risk_id'];?>" value="<?php echo $row['risk_reference'];?>">
                                  <input type="hidden" id="directorate_id-<?php echo $row['directors_risk_id'];?>" value="<?php echo $select_directorate;?>">
                                  <input type="hidden" id="year_id-<?php echo $row['directors_risk_id'];?>" value="<?php echo $year_id;?>">
                                  <input type="hidden" id="quarter_id-<?php echo $row['directors_risk_id'];?>" value="<?php echo $quarter_id;?>">
                                  <button type="button" id="remove-from-directorate-button-<?php echo $row['directors_risk_id'];?>" class="btn btn-danger" title="REMOVE FROM DIRECTOR'S REPORT" onclick="removeFromDirectorsreport('<?php echo $row['directors_risk_id'];?>')"><i class="fa fa-times"></i></button>
                              </form>
                              <?php
                            }
                            else
                            {
                              ?>
                                  <button disabled type="button" class="btn btn-danger" title="YOU HAVE TO BE THE DIRECTOR FOR <?php echo $select_directorate;?> TO REMOVE"><i class="fa fa-remove"></i></button>
                              <?php
                            }


                         ?>
                  </td>
                  <td>
                    <?php
                          $sql_strategic_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM perfomance_management WHERE activity_id
                                                                        ='".$row['activity_id']."'"));
                          $strategic_objective_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_objectives WHERE
                                                                        strategic_objective_id='".$sql_strategic_objective['strategic_objective_id']."'"));
                         echo $strategic_objective_description['strategic_objective_description'] ;?>

                  </td>
                  <td width="200px">
                    <?php
                          $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['activity_id']."'
                                                                  && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                          if(mysqli_num_rows($sql_related_risks) > 0){
                            while($related_risks = mysqli_fetch_array($sql_related_risks))
                            {
                              $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."' && changed='no'"));
                              $current_overall = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no IN ('".$related_risks['risk_reference']."')
                                                                              && current_overall_score > 19
                                                                            AND period_from='".$year_id."' AND quarter='".$quarter_id."' && changed='no'"));
                              $overall_score = $current_overall['current_overall_score'];
                              ?>
                              <?php
                              if($overall_score < 26 && $overall_score > 19 && $current_overall['risk_opportunity'] == 'risk')
                              {
                                ?>
                              <div style="background:#FF0000; overflow: auto; color:white;">
                                <?php echo $risk_description['risk_description'] ;?><br/>
                                <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                <hr style="solid"/>

                              </div>
                                <?php
                              }
                              //for opportunities
                              if($overall_score < 26 && $overall_score > 19 && $current_overall['risk_opportunity'] == 'opportunity')
                              {
                                ?>
                              <div style="background:#0272a6; overflow: auto; color:white;">
                                <?php echo $risk_description['risk_description'] ;?><br/>
                                <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                <hr style="solid"/>

                              </div>
                                <?php
                              }
                            }
                          }
                     ?>

                  </td>
                  <?php
                    //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                      //$int = $row['CURRENT_ESTIMATE'];
                      $activity_estimate_sql = mysqli_query($dbc,"SELECT * FROM performance_update WHERE activity_id='".$row['activity_id']."'
                                                                                  && year_id='".$year_id."'
                                                                                  && quarter_id='".$quarter_id."' && changed='no'");
                      $activity_estimate = mysqli_fetch_array($activity_estimate_sql);
                      $int = $activity_estimate['estimated_current_performance'];
                      $activity_description_sql = mysqli_query($dbc,"SELECT * FROM perfomance_management WHERE activity_id='".$row['activity_id']."'")
                                                                or die (mysqli_error($dbc));
                      $activity_description = mysqli_fetch_array($activity_description_sql);


                      if($int < 20 && $int > 0)
                      {
                        ?>
                        <td style="background:#FF0000;color:white;">
                          <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                              <input type="hidden" value="<?php echo $row['activity_id'];?>" name="activity_id">
                              <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                              <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                              <?php
                              echo $activity_description['activity_description'];?>
                              </button>
                          </form>
                          <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          <hr style="solid"/>

                        </td>

                        <?php
                      }
                      if($int < 40 && $int > 19)
                      {
                        ?>
                        <td style="background:#FFC200;color:black;">
                          <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                              <input type="hidden" value="<?php  echo $row['activity_id'];?>" name="activity_id">
                              <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                              <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                              <?php echo $activity_description['activity_description'];?>
                              </button>
                          </form>
                          <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          <hr style="solid"/>
                        </td>

                        <?php
                      }
                      if($int < 60 && $int > 39)
                      {
                        ?>
                        <td style="background:#FFFF00;color:black;">
                          <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                              <input type="hidden" value="<?php echo $row['activity_id'];?>" name="activity_id">
                              <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                              <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                              <?php echo $activity_description['activity_description'];?>
                              </button>
                          </form>
                          <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          <hr style="solid"/>
                        </td>
                        <?php
                      }
                      if($int < 80 && $int > 59)
                      {
                        ?>
                        <td style="background:#00FF00; color:black;">
                          <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                              <input type="hidden" value="<?php echo $row['activity_id'];?>" name="activity_id">
                              <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                              <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                              <?php echo $activity_description['activity_description'];?>
                              </button>
                          </form>

                          <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          <hr style="solid"/>
                        </td>
                        <?php
                      }
                      if($int < 101 && $int > 79)
                      {
                        ?>
                        <td style="background:#006400; color:white;">
                          <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                              <input type="hidden" value="<?php echo $row['activity_id'];?>" name="activity_id">
                              <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                              <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                              <?php echo $activity_description['activity_description'];?>
                              </button>
                          </form>

                          <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          <hr style="solid"/>
                        </td>
                        <?php
                      }
                      if($int < 1)
                      {
                        ?>
                        <td>N/A</td>
                        <?php
                      }

                   ?>

                </tr>
              <?php
            }
             ?>
          </table>
        </div>
     </div>
     <!-- /.card-body -->
     <div class="card-footer">

     </div>
     <!-- card-footer -->
   </div>
 </div>
   <!-- /.card -->
   <?php
       $sql_all_risks = mysqli_query($dbc,"SELECT reference_no,risk_opportunity FROM update_risk_status
                                            WHERE reference_no IN(SELECT risk_reference FROM directors_risk_table
                                                    WHERE year_id='".$year_id."' && quarter_id='".$quarter_id."'
                                                    && directorate_id='".$select_directorate."' && directors_cumulative_id IS NULL)
                                            && period_from='".$year_id."' && quarter='".$quarter_id."'

                                            ");
        $sql_opportunity = mysqli_query($dbc,"SELECT reference_no,risk_opportunity FROM update_risk_status
                                                WHERE reference_no IN(SELECT risk_reference FROM directors_risk_table
                                                        WHERE year_id='".$year_id."' && quarter_id='".$quarter_id."'
                                                        && directorate_id='".$select_directorate."' && directors_cumulative_id IS NULL)
                                                && period_from='".$year_id."' && quarter='".$quarter_id."'
                                                && risk_opportunity='opportunity'

                                          ");
        $sql_risk = mysqli_query($dbc,"SELECT reference_no,risk_opportunity FROM update_risk_status
                                                      WHERE reference_no IN(SELECT risk_reference FROM directors_risk_table
                                                              WHERE year_id='".$year_id."' && quarter_id='".$quarter_id."'
                                                              && directorate_id='".$select_directorate."' && directors_cumulative_id IS NULL)
                                               && period_from='".$year_id."' && quarter='".$quarter_id."'
                                               && risk_opportunity='risk'
                                              ");

        $count_all_rows = mysqli_num_rows($sql_all_risks);
        $count_opportunity_rows = mysqli_num_rows($sql_opportunity);
        $count_risk_rows = mysqli_num_rows($sql_risk);
        if($count_all_rows == $count_risk_rows)
        {
          ?>
          <div class="col-md-6">
            <div id="loader_submit_cumulative_risks"></div>
            <form id="cumulative-risks-directorate-form">
              <input type="hidden" value="<?php echo $select_directorate;?>" name="select_directorate">
              <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
              <input type="hidden" value="<?php echo $quarter_id;?>" name="quarter_id">
              <input type="hidden" value="risk" name="risk_opportunity">
            <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="cumulative_risk_description"><span class="required">*</span>Cumulative Risk</label><br/>
                        <textarea name="cumulative_risk_description" title="Directorate Risk Description" id="cumulative_risk_description" class="form-control" placeholder="Directorate Risk Description" maxlength="200" required></textarea>
                        <h6 class="pull-right" id="count_message_cumulative_risk"></h6>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="cumulative_activity_description"><span class="required">*</span>Cumulative Activity</label><br/>
                        <textarea name="cumulative_activity_description" title="Directorate Activity Description" id="cumulative_activity_description" class="form-control" placeholder="Directorate Activity Description" maxlength="200" required></textarea>
                        <h6 class="pull-right" id="count_message_cumulative_activity"></h6>
                </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-12 form-group">
                    <div>
                      <?php
                          $sql_scores = mysqli_query($dbc,"SELECT ROUND(AVG(current_overall_score)) AS current_overall_score FROM update_risk_status
          			                                           WHERE reference_no IN
                      		                                         (SELECT DISTINCT risk_reference FROM directors_risk_table WHERE year_id='".$year_id."'
                                                                     && quarter_id='".$quarter_id."' && directorate_id='".$select_directorate."'
                                                                     && directors_cumulative_id IS NULL GROUP BY risk_reference)

                                                                && changed='no' && period_from='".$year_id."' && quarter='".$quarter_id."'

                                                          ");
                          $sql_scores = mysqli_fetch_array($sql_scores);
                          $sql_average_scores = $sql_scores['current_overall_score'];


                      ?>
                      <label>Cumulative Risk Score</label>
                      <input type="text" name="cumulative_risk_score" class="form-control" value="<?php echo $sql_average_scores;?>" readonly="true"><br/>
                    </div>
                    <div>
                      <?php
                           $sql_scores = mysqli_query($dbc,"SELECT ROUND(AVG(estimated_current_performance)) AS estimated_current_performance FROM performance_update
                                                            WHERE activity_id IN
                                                         (SELECT activity_id FROM directors_risk_table WHERE year_id='".$year_id."'
                                                             && quarter_id='".$quarter_id."' && directorate_id='".$select_directorate."'
                                                           && directors_cumulative_id IS NULL)

                                                             && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'
                                                          ") or die(mysqli_error($dbc));
                            $sql_scores = mysqli_fetch_array($sql_scores);
                            $sql_average_scores = $sql_scores['estimated_current_performance'];
                       ?>
                      <label>Cumulative Activity Score (%)</label>
                      <input type="text" name="cumulative_activity_score" class="form-control" value="<?php echo $sql_average_scores;?>" readonly="true">
                    </div>

              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                      <label for="cumulative_outcome_description"><span class="required">*</span>Cumulative Outcome</label><br/>
                      <textarea name="cumulative_outcome_description" title="Cumulative Outcome" id="cumulative_outcome_description" class="form-control" placeholder="Cumulative Outcome Description" maxlength="400" required></textarea>
                      <h6 class="pull-right" id="count_message_cumulative_outcome"></h6>
              </div>

            </div>
            <div class="row">
              <div class="col-lg-4 col-xs-12 form-group">
                   <label for="likelihood_score"><span class="required">*</span>Likelihood Score </label>
                   <select class="select2 form-control" name="likelihood_score" id="likelihood_score" required>
                       <option value=""> --Select-- </option>
                       <option value="5">Almost Certain</option>
                       <option value="4">Highly Certain</option>
                       <option value="3">Likely</option>
                       <option value="2">Unlikely</option>
                       <option value="1">Rare</option>
                   </select>
               </div>
               <div class="col-lg-4 col-xs-12 form-group">
                    <label for="impact_score"><span class="required">*</span>Impact Score </label>
                    <select class="select2 form-control" name="impact_score" id="impact_score" required>
                        <option value=""> --Select--</option>
                        <option value="1">Insignificant</option>
                        <option value="2">Minor</option>
                        <option value="3">Moderate</option>
                        <option value="4">Major</option>
                        <option value="5">Catastrophic</option>
                    </select>
                </div>
               <div class="col-lg-4 col-xs-12 form-group">
                   <label for="overall_score"><span class="required">*</span>Overall Score/Heat </label>
                   <input type="text" class="form-control" name="overall_score" id="overall_score" readonly="readonly" required>
               </div>
            </div>

            <!--start of risk rating scALE -->
            <div class="row">

              <div class="col-lg-4 col-xs-12 form-group">
              <label for="btn btn-primary" data-toggle="modal" class="rating_scale" data-target="#view_risk_scale-">view risk Rating scale</label>



          </div>

<!--
                  <div class="col-md-12 text-center">
                      <button type="submit" class="btn btn-primary" id="create-risk-button">Create Risk</button>
                  </div>
                -->


                                    <!-- Modal -->
                                        <div class="modal fade" id="view_risk_scale-" class="modal fade" role="dialog">
                                          <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content modal-lg">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">RISK RATING SCALE</h4>
                                              </div>
                                              <div class="modal-body">

                                                <div class="row">
                                                  <div class="col-md-8">
                                                      <div class="table-responsive">
                                                          <div class="Tablescard">
                                                      </h4> Overall Risk Rating
                                                            <table class="table table-bordered">

                                                              <tbody>
                                                                <tr>

                                                                <td rowspan="5" class="impact_rotate">Impact</td>
                                                                <td>Catastrophic <br/><small class="text-primary">5</small></td>
                                                                <td class="medium" style="background-color: #FFFF00;"  title="OVERALL SCORE: 5"> <font color="black">5</font></td>        <br/>
                                                                <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10"> <font color="black">10</font </td>
                                                                    <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15"> <font color="black">15</font</td>
                                                                      <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 20"><font color="white">20</font </td>
                                                                        <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 25"><font color="white">25</font </td>

                                                                          </tr>
                                                                              <tr>

                                                                          <td>Major <br/><small class="text-primary">4</small></td>
                                                                              <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4"> <font color="black">4</font></td>
                                                                                <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8"> <font color="black">8</font></td>
                                                                                  <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12"> <font color="black">12</font></td>
                                                                                      <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 16"> <font color="black">16</font> </td>
                                                                                        <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 20"> <font color="white">20</font> </td>

                                                                                      </tr>
                                                                                          <tr>

                                                                                          <td>Moderate <br/><small class="text-primary">3</small></td>
                                                                                          <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3"> <font color="black">3</font></td>
                                                                                              <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6"> <font color="black">6</font></td>
                                                                                                  <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 9"> <font color="black">9</font></td>
                                                                                                      <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12"> <font color="black">12</font></td>
                                                                                                        <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15"> <font color="black">15</font></td>

                                                                                                      </tr>
                                                                                                          <tr>

                                                                                                          <td>Minor <br/><small class="text-primary">2</small></td>
                                                                                                          <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2"> <font color="white">2</font></td>
                                                                                                              <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4"> <font color="black">4</font></td>
                                                                                                                  <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6"> <font color="black">6</font> </td>
                                                                                                                    <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8"> <font color="black">8</font></td>
                                                                                                                      <td  class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10"> <font color="black">10</font> </td>

                                                                                                                    </tr>
                                                                                                                        <tr>



                                                                                                                        <td>Insignificant <br/><small class="text-primary">1</small></td>
                                                                                                                        <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 1"> <font color="white">1</font> </td>
                                                                                                                          <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2"> <font color="white">2</font></td>
                                                                                                                            <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3"> <font color="black">3</font> </td>
                                                                                                                              <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4"> <font color="black">4</font></td>
                                                                                                                              <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 5"> <font color="black">5</font></td>

                                                                                                                              </tr>

                                                                                                                              <tr>
                                                                                                                                <td colspan="2" rowspan="2"><i class="fa fa-times fa-lg"></i></td>
                                                                                                                                <td>Rare <br/><small class="text-primary">1</small></td>
                                                                                                                                <td>Unlikely <br/><small class="text-primary">2</small></td>
                                                                                                                                <td>Likely <br/><small class="text-primary">3</small></td>
                                                                                                                                <td>Highly Likely <br/><small class="text-primary">4</small></td>
                                                                                                                                <td>Almost Certain <br/><small class="text-primary">5</small></td>
                                                                                                                              </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                              <td colspan="5">Likelihood</td>
                                                                                                                            </tr>
                                                                                                                            </tbody>
                                                                                                                          </table>
                                                                                                                          </div>
                                                                                                                        </div>

                                                                                                                      </div>

                                                                                                                      <div class="col-sm-4">

                                                    <div class="Tablescard">
                                                    </h4> Risk Rating Scale

                                                                                                                  <table border="2">
                                                                                                                  <tr>
                                                                                                                    <th>Risk Rating </th>
                                                                                                                    <th>Risk Score</th>

                                                                                                                  </tr>
                                                                                                                  <tr>
                                                                                                                    <td>Very High</td>
                                                                                                                  <td class="small" style="background-color: #FF0000;"title="20-25">
                                                                                                    <font color="white">20-25</font>

                                                                                                                  </tr>
                                                                                                                  <tr>
                                                                                                                    <td>High</td>
                                                                                                                    <td class="small" style="background-color: #FFC200;"title="10-16">
                                                                                                    <font color="black">10-16</font>

                                                                                                                  </tr>
                                                                                                                  <tr>
                                                                                                                    <td>Medium</td>
                                                                                                                        <td class="small" style="background-color: #FFFF00;"title="5-9">
                                                                                                    <font color="black">5-9</font>


                                                                                                                  </tr>

                                                                                                                  <tr>
                                                                                                                    <td>Low</td>
                                                                                                                      <td class="small" style="background-color: #00FF00;" title="3-4">
                                                                                                    <font color="black">3-4</font>
                                                                                                                  </tr>
                                                                                                                  <tr>
                                                                                                                    <td>Very Low</td>
                                                                                                                    <td class="small" style="background-color: #006400;" title="1-2">
                                                                                                    <font color="white">1-2</font>
                                                                                                    </tr>
                                                                                                    </h4>
                                                                                                                </table>

                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        </div>



                                                              </div>
                                                              <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>




                                              </div>
                                         </div>

                                       </div>
            </div>

            <!-- end of risk rating scale -->
            <div class="row">
              <div class="col-lg-12 col-xs-12 form-group">
                <table class="table table-stripped table-stripped table-hover">
                  <thead>
                    <tr>
                      <td>#</td>
                      <td>Affected Strategic Objective/s</td>
                    </tr>
                  </thead>
                  <?php
                  $number_distinct = 1;
                  $sql_distinct = mysqli_query($dbc,"SELECT * FROM perfomance_management
                                                          WHERE activity_id IN (SELECT activity_id FROM directors_risk_table WHERE directors_cumulative_id IS NULL
                                                          && directorate_id='".$select_directorate."'
                                                          && year_id='".$year_id."'
                                                          && quarter_id='".$quarter_id."') GROUP BY strategic_objective_id");
                  if($sql_distinct)
                  {
                    while($sql_distinct_row = mysqli_fetch_array($sql_distinct))
                    {
                      ?>
                      <tr>
                          <td><?php echo $number_distinct++;?></td>
                          <td>
                            <?php
                                $sql_strategic_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM perfomance_management WHERE activity_id
                                                                              ='".$sql_distinct_row['activity_id']."' GROUP BY strategic_objective_id"));
                                $strategic_objective_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_objectives WHERE
                                                                              strategic_objective_id='".$sql_strategic_objective['strategic_objective_id']."'"));

                               ?>
                               <input type="hidden" value="<?php echo $strategic_objective_description['strategic_objective_id'];?>" name="directors_strategic_objective_id[]">
                                  <p style="white-space: normal; width: 500px; text-align:left;"><?php echo $strategic_objective_description['strategic_objective_description'] ;?></p>
                               <?php
                               ?>
                            </td>
                      </tr/>
                      <?php
                    }
                  }
                 ?>
               </table>


              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary" id="cumulative-risks-directorate-button">SUBMIT</button>
              </div>
            </div>
          </form>
          </div>
          <?php
        }
        if($count_all_rows == $count_opportunity_rows)
        {
          ?>
          <div class="col-md-6">
            <div id="loader_submit_cumulative_risks"></div>
            <form id="cumulative-risks-directorate-form">
              <input type="hidden" value="<?php echo $select_directorate;?>" name="select_directorate">
              <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
              <input type="hidden" value="<?php echo $quarter_id;?>" name="quarter_id">
              <input type="hidden" value="opportunity" name="risk_opportunity">
            <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="cumulative_risk_description"><span class="required">*</span>Cumulative Opportunity</label><br/>
                        <textarea name="cumulative_risk_description" id="cumulative_risk_description" class="form-control" placeholder="Directorate Opportunity Description" maxlength="200" required></textarea>
                        <h6 class="pull-right" id="count_message_cumulative_risk"></h6>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="cumulative_activity_description"><span class="required">*</span>Cumulative Activity</label><br/>
                        <textarea name="cumulative_activity_description" title="Directorate Activity Description" id="cumulative_activity_description" class="form-control" placeholder="Directorate Activity Description" maxlength="200" required></textarea>
                        <h6 class="pull-right" id="count_message_cumulative_activity"></h6>
                </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-12 form-group">
                    <div>
                      <?php
                          $sql_scores = mysqli_query($dbc,"SELECT ROUND(AVG(current_overall_score)) AS current_overall_score FROM update_risk_status
          			                                           WHERE reference_no IN
                      		                                         (SELECT DISTINCT risk_reference FROM directors_risk_table WHERE year_id='".$year_id."'
                                                                     && quarter_id='".$quarter_id."' && directorate_id='".$select_directorate."'
                                                                     && directors_cumulative_id IS NULL GROUP BY risk_reference)

                                                                && changed='no' && period_from='".$year_id."' && quarter='".$quarter_id."'

                                                          ");
                          $sql_scores = mysqli_fetch_array($sql_scores);
                          $sql_average_scores = $sql_scores['current_overall_score'];


                      ?>
                      <label>Cumulative Opportunity Score</label>
                      <input type="text" name="cumulative_risk_score" class="form-control" value="<?php echo $sql_average_scores;?>" readonly="true"><br/>
                    </div>
                    <div>
                      <?php
                           $sql_scores = mysqli_query($dbc,"SELECT ROUND(AVG(estimated_current_performance)) AS estimated_current_performance FROM performance_update
                                                            WHERE activity_id IN
                                                         (SELECT activity_id FROM directors_risk_table WHERE year_id='".$year_id."'
                                                             && quarter_id='".$quarter_id."' && directorate_id='".$select_directorate."'
                                                           && directors_cumulative_id IS NULL)

                                                             && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'
                                                          ") or die(mysqli_error($dbc));
                            $sql_scores = mysqli_fetch_array($sql_scores);
                            $sql_average_scores = $sql_scores['estimated_current_performance'];
                       ?>
                      <label>Cumulative Activity Score (%)</label>
                      <input type="text" name="cumulative_activity_score" class="form-control" value="<?php echo $sql_average_scores;?>" readonly="true">
                    </div>

              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                      <label for="cumulative_outcome_description"><span class="required">*</span>Cumulative Outcome</label><br/>
                      <textarea name="cumulative_outcome_description" title="Cumulative Outcome" id="cumulative_outcome_description" class="form-control" placeholder="Cumulative Outcome Description" maxlength="400" required></textarea>
                      <h6 class="pull-right" id="count_message_cumulative_outcome"></h6>
              </div>

            </div>
            <div class="row">
              <div class="col-lg-4 col-xs-12 form-group">
                   <label for="likelihood_score"><span class="required">*</span>Likelihood Score </label>
                   <select class="select2 form-control" name="likelihood_score" id="likelihood_score" required>
                       <option value=""> --Select-- </option>
                       <option value="5">Almost Certain</option>
                       <option value="4">Highly Certain</option>
                       <option value="3">Likely</option>
                       <option value="2">Unlikely</option>
                       <option value="1">Rare</option>
                   </select>
               </div>
               <div class="col-lg-4 col-xs-12 form-group">
                    <label for="impact_score"><span class="required">*</span>Impact Score </label>
                    <select class="select2 form-control" name="impact_score" id="impact_score" required>
                        <option value=""> --Select--</option>
                        <option value="1">Insignificant</option>
                        <option value="2">Minor</option>
                        <option value="3">Moderate</option>
                        <option value="4">Major</option>
                        <option value="5">Transformational</option>
                    </select>
                </div>
               <div class="col-lg-4 col-xs-12 form-group">
                   <label for="overall_score"><span class="required">*</span>Overall Score </label>
                   <input type="text" class="form-control" name="overall_score" id="overall_score" readonly="readonly" required>
               </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-xs-12 form-group">
                <table class="table table-stripped table-stripped table-hover">
                  <thead>
                    <tr>
                      <td>#</td>
                      <td>Affected Strategic Objective/s</td>
                    </tr>
                  </thead>
                  <?php
                  $number_distinct = 1;
                  $sql_distinct = mysqli_query($dbc,"SELECT * FROM perfomance_management
                                                          WHERE activity_id IN (SELECT activity_id FROM directors_risk_table WHERE directors_cumulative_id IS NULL
                                                          && directorate_id='".$select_directorate."'
                                                          && year_id='".$year_id."'
                                                          && quarter_id='".$quarter_id."') GROUP BY strategic_objective_id");
                  if($sql_distinct)
                  {
                    while($sql_distinct_row = mysqli_fetch_array($sql_distinct))
                    {
                      ?>
                      <tr>
                          <td><?php echo $number_distinct++;?></td>
                          <td>
                            <?php
                                $sql_strategic_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM perfomance_management WHERE activity_id
                                                                              ='".$sql_distinct_row['activity_id']."' GROUP BY strategic_objective_id"));
                                $strategic_objective_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_objectives WHERE
                                                                              strategic_objective_id='".$sql_strategic_objective['strategic_objective_id']."'"));

                               ?>
                               <input type="hidden" value="<?php echo $strategic_objective_description['strategic_objective_id'];?>" name="directors_strategic_objective_id[]">
                                  <p style="white-space: normal; width: 500px; text-align:left;"><?php echo $strategic_objective_description['strategic_objective_description'] ;?></p>
                               <?php
                               ?>
                            </td>
                      </tr/>
                      <?php
                    }
                  }
                 ?>
               </table>


              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary" id="cumulative-risks-directorate-button">SUBMIT</button>
              </div>
            </div>
          </form>
          </div>
          <?php
        }
        if($count_all_rows != $count_risk_rows && $count_all_rows != $count_opportunity_rows)
        {//i was here
          ?>
          <div class="col-md-6">
            <div class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Not Allowed!</strong> You can either select Activities with only Risks or Activities With only Opportunities.

            </div>
          </div>
          <?php
        }




    ?>


   <?php
 }
 else
 {
   ?>
   <div class="alert alert-danger">
     <strong>No Records Found for <?php echo $select_directorate;?>!</strong>
   </div>
   <?php
 }
 ?>
</div>

<?php
}
?>

<?php
}
else
{
  echo "not posted";
}
    ?>
<script>
var table = $('#directorate_risks_with_activities_table').DataTable({
});
//auto increase textarea
$('input[type=text], textarea').on('keyup', function(){
  $(this).css('height','auto');
  $(this).height(this.scrollHeight);
 });
//textarea risk
 var cumulative_risk_description_max = 200;
 var text_length = $('#cumulative_risk_description').val().length;
 var text_remaining = cumulative_risk_description_max - text_length;

 $('#count_message_cumulative_risk').html(text_remaining + ' characters remaining');

 $('#cumulative_risk_description').keyup(function() {
   var text_length = $('#cumulative_risk_description').val().length;
   var text_remaining = cumulative_risk_description_max - text_length;

   $('#count_message_cumulative_risk').html(text_remaining + ' characters remaining');
 });
//textarea activity
 var cumulative_activity_description_max = 200;
 var text_length = $('#cumulative_activity_description').val().length;
 var text_remaining = cumulative_activity_description_max - text_length;

 $('#count_message_cumulative_activity').html(text_remaining + ' characters remaining');

 $('#cumulative_activity_description').keyup(function() {
   var text_length = $('#cumulative_activity_description').val().length;
   var text_remaining = cumulative_activity_description_max - text_length;

   $('#count_message_cumulative_activity').html(text_remaining + ' characters remaining');
 });

 //textarea cumulative
 var cumulative_outcome_description_max = 400;
 var text_length = $('#cumulative_outcome_description').val().length;
 var text_remaining = cumulative_outcome_description_max - text_length;

 $('#count_message_cumulative_outcome').html(text_remaining + ' characters remaining');

 $('#cumulative_outcome_description').keyup(function() {
   var text_length = $('#cumulative_outcome_description').val().length;
   var text_remaining = cumulative_outcome_description_max - text_length;

   $('#count_message_cumulative_outcome').html(text_remaining + ' characters remaining');
 });

 //OVERALL SCORE CALCULATION
 $('#impact_score').change(function(){
         var likelihood_score = $('#likelihood_score').val();
         var impact_score = $('#impact_score').val();

         var overall_score = likelihood_score * impact_score ;
         $('#overall_score').val(overall_score);

         var prior_overall_score =$('#prior_overall_score').val();
         var current_overall_score = $('.overall_score').val();
         prior_overall_score = +prior_overall_score;
         current_overall_score = +current_overall_score;

 });
 $('#likelihood_score').change(function(){
         var likelihood_score = $('#likelihood_score').val();
         var impact_score = $('#impact_score').val();

         var overall_score = likelihood_score * impact_score ;
         $('#overall_score').val(overall_score);

         var prior_overall_score =$('#prior_overall_score').val();
         var current_overall_score = $('.overall_score').val();
         prior_overall_score = +prior_overall_score;
         current_overall_score = +current_overall_score;

 });
 //end of calculation of risk scores
</script>
