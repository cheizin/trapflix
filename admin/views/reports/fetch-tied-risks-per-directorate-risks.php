<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $directors_cumulative_id = mysqli_real_escape_string($dbc,strip_tags($_POST['directors_cumulative_id']));
  $select_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['directorate_id']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['year_id']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['quarter_id']));

  $cumulative_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE
                                                         directors_cumulative_id='".$directors_cumulative_id."'
                                                         && year_id='".$year_id."' && quarter_id='".$quarter_id."'
                                                                      "));
  $risk_opportunity = ucwords($cumulative_description['risk_opportunity']);


  $sql = mysqli_query($dbc,"SELECT * FROM directors_risk_table WHERE directors_cumulative_id='".$directors_cumulative_id."'
                                          && directorate_id='".$select_directorate."'
                                          && year_id='".$year_id."'
                                          && quarter_id='".$quarter_id."'");
  if($sql)
  {
    $total_rows = mysqli_num_rows($sql);
    if($total_rows > 0)
    {


    ?>
    <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Editing  <?php echo $cumulative_description['cumulative_risk_description'];?></li>
          </ol>
    </nav>
  <div class="col-md-12">
  <div class="card">
    <h4 class="card-title">Cumulative:<br/></h4>
     <div id="feedback_message"></div>
     <form id="edit-cumulative-risk-form">
       <input type="hidden" value="<?php echo $select_directorate;?>" name="select_directorate" id="select_directorate">
       <input type="hidden" value="<?php echo $year_id;?>" name="year_id" id="year_id">
       <input type="hidden" value="<?php echo $quarter_id;?>" name="quarter_id" id="quarter_id">
       <input type="hidden" value="<?php echo $directors_cumulative_id;?>" name="directors_cumulative_id" id="directors_cumulative_id">
     <div class="row">
         <div class="col-lg-3 col-xs-12 form-group">
                 <label for="cumulative_risk_description"><span class="required">*</span>Cumulative <?php echo $risk_opportunity;?></label><br/>
                 <textarea name="cumulative_risk_description" id="cumulative_risk_description" class="form-control" placeholder="Directorate Risk Description" maxlength="200" required><?php echo $cumulative_description['cumulative_risk_description'];?></textarea>
                 <h6 class="pull-right" id="count_message_cumulative_risk"></h6>
         </div>
         <div class="col-lg-3 col-xs-12 form-group">
                 <label for="cumulative_activity_description"><span class="required">*</span>Cumulative Activity</label><br/>
                 <textarea name="cumulative_activity_description"  id="cumulative_activity_description" class="form-control" placeholder="Directorate Activity Description" maxlength="200" required><?php echo $cumulative_description['cumulative_activity_description'];?></textarea>
                 <h6 class="pull-right" id="count_message_cumulative_activity"></h6>
         </div>

         <div class="col-lg-3 col-xs-12 form-group">
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
                 <label>Cumulative <?php echo $risk_opportunity;?> Score</label>
                 <input type="text" name="cumulative_risk_score" id="cumulative_risk_score" class="form-control" value="<?php echo $cumulative_description['cumulative_risk_score'];?>" readonly="true"><br/>
            </div>
            <div class="col-lg-3 col-xs-12 form-group">
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
                 <input type="text" name="cumulative_activity_score" id="cumulative_activity_score" class="form-control" value="<?php echo $cumulative_description['cumulative_activity_score'];?>" readonly>
               </div>
         </div>
         <div class="row">
           <div class="col-lg-3 col-xs-12 form-group">
                   <label for="cumulative_outcome_description"><span class="required">*</span>Cumulative Outcome</label><br/>
                   <textarea name="cumulative_outcome_description" id="cumulative_outcome_description" class="form-control" placeholder="Cumulative Outcome Description" maxlength="400" required><?php echo $cumulative_description['expected_cumulative_outcomes'];?></textarea>
                   <h6 class="pull-right" id="count_message_cumulative_outcome"></h6>
           </div>
           <div class="col-lg-3 col-xs-12 form-group">
                <label for="likelihood_score"><span class="required">*</span>Likelihood Score </label>
                <select class="select2 form-control" name="likelihood_score" id="cumulative_likelihood_score" quired>
                    <option value="<?php echo $cumulative_description['likelihood_score'];?>" selected> --Select-- </option>
                    <option value="5">Almost Certain</option>
                    <option value="4">Highly Certain</option>
                    <option value="3">Likely</option>
                    <option value="2">Unlikely</option>
                    <option value="1">Rare</option>
                </select>
            </div>
            <div class="col-lg-3 col-xs-12 form-group">
                 <label for="impact_score"><span class="required">*</span>Impact Score </label>
                 <select class="select2 form-control" name="impact_score" id="cumulative_impact_score" quired>
                   <?php
                       if($cumulative_description['risk_opportunity'] == "risk")
                       {
                         ?>
                         <option value="<?php echo $cumulative_description['impact_score'];?>" selected> --Select--</option>
                         <option value="1">Insignificant</option>
                         <option value="2">Minor</option>
                         <option value="3">Moderate</option>
                         <option value="4">Major</option>
                         <option value="5">Catastrophic</option>
                         <?php
                       }
                       else
                       {
                         ?>
                         <option value="<?php echo $cumulative_description['impact_score'];?>" selected> --Select--</option>
                         <option value="1">Insignificant</option>
                         <option value="2">Minor</option>
                         <option value="3">Moderate</option>
                         <option value="4">Major</option>
                         <option value="5">Transformational</option>
                         <?php
                       }


                    ?>
                 </select>
             </div>
            <div class="col-lg-3 col-xs-12 form-group">
                <label for="overall_score"><span class="required">*</span>Overall Score/Heat </label>
                <input type="text" class="form-control" name="overall_score" id="cumulative_overall_score" value="<?php echo $cumulative_description['overall_score'];?>" readonly="readonly" required>
            </div>
         </div>

         <div class="row">
           <div class="col-md-12 text-center">
               <button type="submit" class="btn btn-primary" id="edit-cumulative-risks-button">Save Changes</button>
           </div>
         </div>

     </div>

   </form>
   <!-- Tied activities -->
   <!-- /.card-header -->
   <div class="card">
   <div class="card-body">
     <div class="table-responsive">
       <div class="card-header">
         <h3 class="card-title">Tied Activities to the Cumulative Risk</h3>
       </div>
       <table class="table table-striped table-bordered table-hover" id="directorate_risks_with_activities_table" style="overflow:hidden" >
         <thead>
           <tr>
             <td width="10">NO</td>
             <td width="200">Strategic Objective</td>
             <td width="350">Related Risk</td>
             <td width="350">Activity Description</td>
           </tr>
         </thead>
         <?php
         $number = 1;
         while($row = mysqli_fetch_array($sql))
         {
           ?>
             <tr id="row-<?php echo $row['activity_id'];?>">
               <td><?php echo $number++ ;?></td>
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
                           $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."'"));
                           $current_overall = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no IN ('".$related_risks['risk_reference']."')
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


                           </div>
                             <?php
                           }
                           if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'risk')
                           {
                             ?>
                           <div style="background:#FFC200; overflow: auto;">
                             <?php echo $risk_description['risk_description'] ;?><br/>
                             <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                           </div>
                             <?php
                           }
                           if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'risk')
                           {
                             ?>
                           <div style="background:#FFFF00; overflow: auto;">
                             <?php echo $risk_description['risk_description'] ;?><br/>
                             <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                           </div>
                             <?php
                           }
                           if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'risk')
                           {
                             ?>
                           <div style="background:#00FF00; overflow: auto;">
                             <?php echo $risk_description['risk_description'] ;?><br/>
                             <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                           </div>
                             <?php
                           }
                           if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'risk')
                           {
                             ?>
                             <div style="background:#006400;overflow: auto;color:white;">
                               <?php echo $risk_description['risk_description'] ;?><br/>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

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


                           </div>
                             <?php
                           }
                           if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'opportunity')
                           {
                             ?>
                           <div style="background:#008dcf; overflow: auto;">
                             <?php echo $risk_description['risk_description'] ;?><br/>
                             <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                           </div>
                             <?php
                           }
                           if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'opportunity')
                           {
                             ?>
                           <div style="background:#59b4e0; overflow: auto;">
                             <?php echo $risk_description['risk_description'] ;?><br/>
                             <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                           </div>
                             <?php
                           }
                           if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'opportunity')
                           {
                             ?>
                           <div style="background:#99d1ec; overflow: auto;">
                             <?php echo $risk_description['risk_description'] ;?><br/>
                             <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                           </div>
                             <?php
                           }
                           if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'opportunity')
                           {
                             ?>
                             <div style="background:#d4ecf8;overflow: auto;color:white;">
                               <?php echo $risk_description['risk_description'] ;?><br/>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>

                             </div>
                             <?php
                           }
                           if($overall_score <1)
                           {
                             ?>
                            <div style="overflow: auto;">N/A<br/></div>
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
  </div>

  </div>






  <!-- end of tied activites -->

  <?php
  }
  else
  {
    echo "No records";
  }

  }
   ?>
   <script>
   //start of calculation of risk scores
   $('#cumulative_impact_score').change(function(){
           var likelihood_score = $('#cumulative_likelihood_score').val();
           var impact_score = $('#cumulative_impact_score').val();

           var overall_score = likelihood_score * impact_score ;
           $('#cumulative_overall_score').val(overall_score);


   });
   $('#cumulative_likelihood_score').change(function(){
           var likelihood_score = $('#cumulative_likelihood_score').val();
           var impact_score = $('#cumulative_impact_score').val();

           var overall_score = likelihood_score * impact_score ;
           $('#cumulative_overall_score').val(overall_score);


   });
   //end of calculation of risk scores
   </script>
   <?php
}
