<?php
session_start();
include("../../controllers/setup/connect.php");

$select_period = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$select_quarter = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$year_id= mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$select_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
$selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  ?>
  <!-- start insert a page break -->

   <!-- /.card-header -->
    <div style="page-break-after:always;"></div>
  <!-- start static content -->

<form action="views/reports/pdf/pdf-departmental-risk-management-report.php" method="post" target="_blank">
  <input type="hidden" name="select_period" value="<?php echo $select_period;?>">
  <input type="hidden" name="select_quarter" value="<?php echo $select_quarter;?>">
  <input type="hidden" name="departments" value="<?php echo $select_department ;?>">
  <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
</form>


  <!-- end static content -->
  <?php
    $sql = mysqli_query($dbc,
                            "SELECT  b.strategic_objective_description AS SOB_DESC,a.departmental_objective_id AS DOB_DESC,
                                      a.departmental_sub_objective_id AS DSO_DESC, a.activity_id AS ACT_ID,
                                      a.activity_description AS ACT_DESC,
                                      a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                      e.risk_description AS RISK_DESC, e.current_overall_score AS SCORE,
                                      f.performance_update_description AS ACT_UPDATE,
                                      f.estimated_current_performance AS CURRENT_ESTIMATE,
                                      a.department_id AS DEP_ID FROM  strategic_objectives b
                                      JOIN perfomance_management a ON a.strategic_objective_id = b.strategic_objective_id
                                      JOIN activity_related_risks d ON d.activity_id =a.activity_id
                                      JOIN update_risk_status e ON e.reference_no =d.risk_reference
                                      JOIN performance_update f ON a.activity_id=f.activity_id
                                      WHERE
                                       e.changed = 'no'
                                      AND d.year_id = '".$year_id."'
                                      AND d.quarter_id = '".$quarter_id."'
                                      AND e.period_from = '".$year_id."'
                                      AND e.quarter = '".$quarter_id."'
                                      AND a.department_id = '".$select_department."'
                                      AND f.changed = 'no'
                                      AND f.year_id = '".$year_id."'
                                      AND f.quarter_id = '".$quarter_id."'
                                      AND d.changed = 'no'
                                      AND a.activity_status='open'
                                      AND d.risk_reference IS NOT NULL
                                      GROUP BY ACT_ID
                               "
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {



      ?>

     <!-- start departmental activities with  risks -->
     <div class="card">
      <div class="card-header with-border">
        <h3 class="card-title">Detailed Activities With Risks:<br/><b>
             </b>
        </h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive no-padding">
           <table class="table table-striped table-bordered table-hover" id="detailed_corporate_activities_related_risks_table" width="100%" style="overflow:hidden;" autosize="1">
             <thead>
               <tr>
                 <td>NO</td>
                 <td width="70" >Strategic Objective</td>
                 <td>Strategic Outcome</td>
                 <td>Strategic KPI</td>
                 <td>Departmental Objective</td>
                 <td width="100">Related Risk</td>
                 <td>Activity Description</td>
                 <td width="50">Departmental Activity KPI</td>
                 <td class="activity_update_header" maxlength="500">Activity Performance Update</td>
               </tr>
             </thead>
             <?php
             $number = 1;
             while($row = mysqli_fetch_array($sql))
             {
               ?>
                 <tr>
                   <td><?php echo $number++ ;?></td>
                   <td><p class="activity-font"><?php echo $row['SOB_DESC'] ;?></p></td>
                   <td>
                     <?php
                           $sql_related_outcomes = mysqli_query($dbc,"SELECT * FROM activity_strategic_outcomes WHERE activity_id='".$row['ACT_ID']."'
                                                                   && changed='no' && year_id='".$year_id."'") or die(mysqli_error($dbc));
                           if(mysqli_num_rows($sql_related_outcomes) > 0){
                             while($related_outcomes = mysqli_fetch_array($sql_related_outcomes))
                             {
                               $outcome_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_outcomes
                                                       WHERE strategic_outcome_id='".$related_outcomes['strategic_outcome_id']."'"))
                               ?>
                                 <span style="display:block" class="activity-font">-> <?php echo $outcome_description['strategic_outcome_description'] ;?>
                                   </span>
                                   <br/>
                               <?php
                             }
                           }
                      ?>
                   </td>
                   <td>
                     <?php
                           $sql_related_kpis = mysqli_query($dbc,"SELECT * FROM activity_strategic_kpis WHERE activity_id='".$row['ACT_ID']."'
                                                                   && changed='no' && year_id='".$year_id."'") or die(mysqli_error($dbc));
                           if(mysqli_num_rows($sql_related_kpis) > 0){
                             while($related_kpis = mysqli_fetch_array($sql_related_kpis))
                             {
                               $kpi_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_kpis
                                                       WHERE strategic_kpi_id='".$related_kpis['strategic_kpi_id']."'"))
                               ?>
                               <span style="display:block" class="activity-font">->
                                 <?php echo $kpi_description['strategic_kpi_description'] ;?>
                                   </span>
                                   <br/>
                               <?php
                             }
                           }
                      ?>
                   </td>
                   <td>
                     <span style="display:block" class="activity-font">
                         <?php
                               $mapped_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_objectives
                                                                 WHERE departmental_objective_id='".$row['DOB_DESC']."'"));
                                 echo $mapped_objective['departmental_objective_description'] ;
                           ?>
                         </span>
                       <br/>
                   </td>
                   <td>
                     <?php
                           $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                   && changed='no' && year_id='".$select_period."' && quarter_id='".$select_quarter."'") or die(mysqli_error($dbc));
                           if(mysqli_num_rows($sql_related_risks) > 0){
                             while($related_risks = mysqli_fetch_array($sql_related_risks))
                             {
                               $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no='".$related_risks['risk_reference']."' && changed='no' && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC"));
                               $risk_description_updated = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."' && changed = 'no'"));
                               $overall_score = $risk_description['current_overall_score'];
                               if($overall_score < 26 && $overall_score > 19 && $risk_description['risk_opportunity'] == 'risk')
                               {
                                 ?>
                                 <table>
                                   <tr style="border-style:hidden;">
                                     <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;border-style:hidden;">
                                       <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                       <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                     </td>
                                   </tr>
                                 </table>
                                 <?php
                               }
                               if($overall_score < 17 && $overall_score > 9 && $risk_description['risk_opportunity'] == 'risk')
                               {
                                 ?>
                                 <table>
                                   <tr style="border-style:hidden;">
                                     <td class="risk-div-background" style="background-color:#FFC200; overflow: wrap;border-style:hidden;">
                                       <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                       <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                     </td>
                                   </tr>
                                 </table>
                                 <?php
                               }
                               if($overall_score < 10 && $overall_score > 5 && $risk_description['risk_opportunity'] == 'risk')
                               {
                                 ?>
                                 <table>
                                   <tr style="border-style:hidden;">
                                     <td class="risk-div-background" style="background-color:#FFFF00; overflow: wrap;border-style:hidden;">
                                       <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                       <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                     </td>
                                   </tr>
                                 </table>
                                 <?php
                               }
                               if($overall_score < 5 && $overall_score > 2 && $risk_description['risk_opportunity'] == 'risk')
                               {
                                 ?>
                                 <table>
                                   <tr style="border-style:hidden;">
                                     <td class="risk-div-background" style="background-color:#00FF00; overflow: wrap;border-style:hidden;">
                                       <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                       <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                     </td>
                                   </tr>
                                 </table>
                                 <?php
                               }
                               if($overall_score < 3 && $overall_score > 0 && $risk_description['risk_opportunity'] == 'risk')
                               {
                                 ?>
                                 <table>
                                   <tr style="border-style:hidden;">
                                     <td class="risk-div-background" style="background-color:#006400; overflow: wrap;border-style:hidden;">
                                       <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                       <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                     </td>
                                   </tr>
                                 </table>
                                 <?php
                               }
                               //for opportunities
                               if($overall_score < 26 && $overall_score > 19 && $risk_description['risk_opportunity'] == 'opportunity')
                               {
                                 ?>
                                 <table>
                                   <tr>
                                     <td class="risk-div-background activity-font" style="background-color:#0272a6; overflow: wrap; color:white;border-style:hidden;">
                                       <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                       <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                     </td>
                                   </tr>
                                 </table>
                                 <?php
                               }
                               if($overall_score < 17 && $overall_score > 9 && $risk_description['risk_opportunity'] == 'opportunity')
                              {
                                ?>
                                <table>
                                  <tr>
                                    <td class="risk-div-background activity-font" style="background-color:#008dcf; overflow: wrap;border-style:hidden;">
                                      <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                      <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                    </td>
                                  </tr>
                                </table>
                                <?php
                              }
                              if($overall_score < 10 && $overall_score > 5 && $risk_description['risk_opportunity'] == 'opportunity')
                              {
                                ?>
                                <table>
                                  <tr>
                                    <td class="risk-div-background activity-font" style="background-color:#59b4e0; overflow: wrap;border-style:hidden;">
                                      <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                      <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                    </td>
                                  </tr>
                                </table>
                                <?php
                              }
                              if($overall_score < 5 && $overall_score > 2 && $risk_description['risk_opportunity'] == 'opportunity')
                              {
                                ?>
                                <table>
                                  <tr>
                                    <td class="risk-div-background activity-font" style="background-color:#99d1ec; overflow: wrap;border-style:hidden;">
                                      <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                      <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                    </td>
                                  </tr>
                                </table>
                                <?php
                              }
                              if($overall_score < 3 && $overall_score > 0 && $risk_description['risk_opportunity'] == 'opportunity')
                              {
                                ?>
                                <table>
                                  <tr>
                                    <td class="risk-div-background activity-font" style="background-color:#d4ecf8; overflow: wrap;border-style:hidden;">
                                      <p class="activity-font"><?php echo $risk_description_updated['risk_description'] ;?></p><br/>
                                      <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                    </td>
                                  </tr>
                                </table>
                                <?php
                              }

                              if($overall_score <1)
                              {
                                ?>
                               <div style="overflow: auto;">N/A<br/></div>
                                <?php
                              }
                               ?>
                               <?php
                             }
                           }
                      ?>

                   </td>
                   <?php
                     //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                       $int = $row['CURRENT_ESTIMATE'];
                       if($int < 20 && $int > 0)
                       {
                         ?>
                         <td style="background-color:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                           <?php echo $row['ACT_DESC'];?>
                           <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                         </td>

                         <?php
                       }
                       if($int < 40 && $int > 19)
                       {
                         ?>
                         <td style="background-color:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                           <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                         </td>

                         <?php
                       }
                       if($int < 60 && $int > 39)
                       {
                         ?>
                         <td style="background-color:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                           <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                         </td>
                         <?php
                       }
                       if($int < 80 && $int > 59)
                       {
                         ?>
                         <td style="background-color:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                           <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                         </td>
                         <?php
                       }
                       if($int < 101 && $int > 79)
                       {
                         ?>
                         <td style="background-color:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                        <?php echo $row['ACT_DESC'];?>

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
                   <td>
                     <span style="display:block" class="activity-font"><?php echo $row['DEP_KPI'] ;?>
                     </span>
                     <br/>
                   </td>
                   <td>
                     <span style="display:block" class="activity-font">
                       <?php echo $row['ACT_UPDATE'] ;?>
                       </span>
                     <br/>
                   </td>
                 </tr>
               <?php
             }
              ?>
           </table>
      </div>
    </div>
    <!-- /.card -->
      <?php
    } // end num row
    else  //no rows
    {
      ?>
      <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>No Records!<br/></strong> Sorry, no records found for the selected combination.
      </div>
      <?php
    }
  }

    ?>
<!-- start insert a page break -->
 <div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<!-- start risk analysis -->
<div class="card">
<h4 class="card-title">RISK ANALYSIS<br/>Overview</h4>
<div class="card-body table-responsive no-padding">
  <div class="col-md-8 heatmap-opportunity-chart" style="overflow:wrap;width:500px; float:left;">
    <table class="table table-bordered" style="page-break-inside: avoid;">

     <?php
        $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."' && risk_opportunity='risk' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC LIMIT 10");
        $sql_query_risk_position = mysqli_fetch_array($sql_query);
      ?>
     <tbody>
       <tr>
         <td rowspan="5" class="impact_rotate">Impact</td>
         <td>Catastrophic <br/><small class="text-primary">5</small></td>
         <td class="medium" style="background-color: #FFFF00;"  title="OVERALL SCORE: 5">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."' && current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
            <br/>
         </td>
         <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 20">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 25">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
       </tr>
       <tr>
         <td>Major <br/><small class="text-primary">4</small></td>
             <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
               <?php
                  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                             WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                             && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                   if(mysqli_num_rows($sql) > 0)
                   {
                     while ($risk_position = mysqli_fetch_array($sql)) {
                       echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                     }
                   }

                ?>
             </td>
             <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8">
               <?php
                  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                             WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                             && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                   if(mysqli_num_rows($sql) > 0)
                   {
                     while ($risk_position = mysqli_fetch_array($sql)) {
                       echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                     }
                   }

                ?>
             </td>
             <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12">
               <?php
                  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                             WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                             && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                   if(mysqli_num_rows($sql) > 0)
                   {
                     while ($risk_position = mysqli_fetch_array($sql)) {
                       echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                     }
                   }

                ?>
             </td>
             <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 16">
               <?php
                  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                             WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                             && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                   if(mysqli_num_rows($sql) > 0)
                   {
                     while ($risk_position = mysqli_fetch_array($sql)) {
                       echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";

                     }
                   }

                ?>
             </td>
             <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 20">
               <?php
                  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                             WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                             && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                   if(mysqli_num_rows($sql) > 0)
                   {
                     while ($risk_position = mysqli_fetch_array($sql)) {
                       echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                     }
                   }

                ?>
             </td>

       </tr>
       <tr>
         <td>Moderate <br/><small class="text-primary">3</small></td>
         <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 9">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
       </tr>
       <tr>
         <td>Minor <br/><small class="text-primary">2</small></td>
         <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td  class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
       </tr>
       <tr>
         <td>Insignificant <br/><small class="text-primary">1</small></td>
         <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 1">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
         <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 5">
           <?php
              $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                         WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                         && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
               if(mysqli_num_rows($sql) > 0)
               {
                 while ($risk_position = mysqli_fetch_array($sql)) {
                   echo "<span class='ref_no'>". $risk_position['reference_no'] ."</span><br/>";
                 }
               }

            ?>
         </td>
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

<!-- end of first div-col-8 -->
<div class="col-md-4 heatmap-ratings-table"  style="overflow:wrap;width:500px; float:right;">
<?php
 $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."'
                           && risk_opportunity='risk' && changed='no'  && period_from='".$select_period."'
                           && quarter='".$select_quarter."' && status='approved'
                           && risk_status='open'
                           ORDER BY current_overall_score DESC LIMIT 10");

 $number = 1;
 if($total_rows = mysqli_num_rows($sql_query) > 0)
 {?>
   <table class="table table-bordered table-striped table-hover" id="risks-heatmap-table">
       <thead>
         <tr>
           <td style="font-size:12px;">No</td>
           <td style="font-size:12px;">Risk</td>
           <td style="font-size:12px;">Score</td>
           <td style="font-size:12px;">Ref No</td>
         </tr>
       </thead>
       <?php
       while($row = mysqli_fetch_array($sql_query))
       {
         $sql_query_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' && changed='no'"));
        ?>
         <tr>
           <td style="font-size:12px;"><?php echo $number++ ;?></td>
           <td style="font-size:12px;"><?php echo $sql_query_description['risk_description'];?></td>
           <td style="font-size:12px;"><?php echo $row['current_overall_score'];?></td>
           <td style="font-size:12px;"><?php echo $row['reference_no'];?></td>
         </tr>
       <?php
       }
        ?>
     </table>

     <?php
}
else {
 ?>
 <table class="table table-bordered">
   <thead>
     <tr>
       <td class="text-danger"><i class="fa fa-info-circle"></i> No Records Found</td>

     </tr>

   </thead>
   <tr>
     <td class="text-danger">Sorry, no records have been found for
       the selected quarter (<span class="text-info"><?php echo $select_quarter?></span>)
       and period (<span class="text-info"><?php echo $select_period?></span>)
       for the department (<span class="text-info"><?php echo $select_department?></span>)

     </td>

   </tr>

 </table>

 <?php
}
      ?>
</div>
</div>
<!-- end risk analysis -->
<!-- start insert a page break -->
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->
<!-- START RISK DRIVERS -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Detailed Status of Departmental Risks</h3>
  </div>
   <!-- end of departmental activities with risks -->
   <div class="card-body table-responsive no-padding">
     <?php
   //  $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE changed='no'"));
   /*  if($_SESSION['access_level'] == 'admin')
     {
       $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE changed='no' ORDER BY current_overall_score DESC");
     }
     else
     {*/
      //fetch stagnant risks
       $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                     dep_code='".$select_department."' && changed='no' && status='approved' &&
                     risk_opportunity='risk' && period_from='".$select_period."' && quarter='".$select_quarter."'
                     && risk_status='open'
                    ORDER BY id DESC");

       /*
     }
     */

     $number = 1;
     $rank = 1;
     if($total_rows = mysqli_num_rows($sql_query) > 0)
     {?>
     <table class="table table-striped table-bordered table-hover risk-analysis-table" id="detailed-analysis-table" width="100%" style="overflow:hidden;">
       <thead>
         <tr>
           <td><p class="department-analysis-font">#</p></td>
           <td><p class="department-analysis-font">Description</p></td>
           <td><p class="department-analysis-font">Current Rating</p></td>
           <td><p class="department-analysis-font">Prior Rating</p></td>
           <td><p class="department-analysis-font">Risk Drivers</p></td>
           <td><p class="department-analysis-font">Risk Management Strategy Undertaken</p></td>
           <td><p class="department-analysis-font">Effect of Risk to Authority</p></td>
           <td><p class="department-analysis-font">Further action to be undertaken</p></td>
           <td><p class="department-analysis-font">Update & Monitoring</p></td>
           <td><p class="department-analysis-font">Person Responsible</p></td>
         </tr>
       </thead>
       <?php
       while($row = mysqli_fetch_array($sql_query))
       {
           //fetch department name from the risk management table
           $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no'"));
         ?>
       <tr style="cursor: pointer;">
         <input type="hidden"  value="<?php echo $fetch['department'];?>">
         <input type="hidden"  value="<?php echo $row['period_from'];?>">
         <input type="hidden"   value="<?php echo $row['quarter'];?>">
           <td><?php echo $rank++ ;?></td>
         <td><p class="department-analysis-font"><?php echo $row['risk_description'];?></p></td>
         <td><p class="department-analysis-font"><?php echo $row['current_overall_score'];?>
           <br/>
             (<?php echo $row['current_impact_score'] .'*' . $row['current_likelihood_score'];?>)
             <br/>
             <?php
             if($row['current_overall_score'] > $row['prior_overall_score'])
             {
               ?> <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-up-48.png'><?php
             }
             else if($row['current_overall_score'] == $row['prior_overall_score'])
             {
               ?> <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-bi-48.png'><?php
             }

             else
             {
             ?> <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-down-48.png'><?php
             }
             ?>
             </p>
         </td>
         <td><p class="department-analysis-font"><?php echo $row['prior_overall_score'];?>
         <br/>
           (<?php echo $row['prior_impact_score'] .'*' . $row['prior_likelihood_score'];?>)
         </p>
         </td>
         <td>
           <?php
             $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                   period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
             if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
               {
                       while($risk_driver_row = mysqli_fetch_array($driver_query))
                       {
                         ?>
                              <p class="department-analysis-font"> - <?php echo $risk_driver_row['risk_drivers'];?></p>

                         <?php
                       }
                 }

           ?>
         </td>
         <td>
           <?php
           $sql_risk_drivers = "SELECT DISTINCT risk_management_strategy_undertaken  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                 period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
           if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
             {
                     while($risk_driver_row = mysqli_fetch_array($driver_query))
                     {
                       ?>
                        <p class="department-analysis-font-driver"> - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_management_strategy_undertaken']));?></p>

                       <?php
                     }
               }


            ?>


         </td>
         <td>
           <?php
           $sql_risk_drivers = "SELECT DISTINCT effects_of_risk_to_authority FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                 period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
           if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
             {
                     while($risk_driver_row = mysqli_fetch_array($driver_query))
                     {
                       ?>
                       <p class="department-analysis-font"> - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['effects_of_risk_to_authority']));?></p>

                       <?php
                     }
               }


            ?>

         </td>
         <td>
           <?php
           $sql_risk_drivers = "SELECT DISTINCT action_to_be_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                 period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
           if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
             {
                     while($risk_driver_row = mysqli_fetch_array($driver_query))
                     {
                       ?>
                       <p class="department-analysis-font"> - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['action_to_be_undertaken']));?></p>

                       <?php
                     }
               }


            ?>
         </td>
         <td><p class="department-analysis-font"><?php echo $row['comments_updates_monitoring'];?></p></td>
         <td><p class="department-analysis-font"><?php echo $fetch['person_responsible'];?></p></td>
       </tr>
       <?php
       }
       ?>
     </table>
     <?php
   } else
     {
      ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <td class="text-danger"><i class="fa fa-info-circle"></i> No Records Found</td>

          </tr>

        </thead>
        <tr>
          <td class="text-danger">Sorry, no records have been found for
            the selected quarter (<span class="text-info"><?php echo $select_quarter?></span>)
            and period (<span class="text-info"><?php echo $select_period?></span>)
            for the department (<span class="text-info"><?php echo $select_department?> </span>)

          </td>

        </tr>

      </table>

      <?php
    }

     ?>
   </div>
 </div>
   <!-- /.card-body -->
<!-- END Detailed Status of Departmental Risks -->
<!-- start insert a page break -->
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<!--start heatmap opportunities -->
<div class="card">
<h4 class="card-title">OPPORTUNITY ANALYSIS<br/>Overview</h4>
<div class="card-body table-responsive no-padding">
  <div class="col-md-8 heatmap-opportunity-chart" style="overflow:wrap;width:500px; float:left;">
    <table class="table table-bordered" style="page-break-inside: avoid;">

      <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."' && risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC LIMIT 10");
         $sql_query_risk_position = mysqli_fetch_array($sql_query);
       ?>
      <tbody>
        <tr>
          <td rowspan="5" class="impact_rotate">Impact</td>
          <td>Transformational <br/><small class="text-primary">5</small></td>
          <td class="medium" style="background-color: #59b4e0;"  title="OVERALL SCORE: 5">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
             <br/>
          </td>
          <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 20">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 25">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
        </tr>
        <tr>
          <td>Major <br/><small class="text-primary">4</small></td>
              <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                              WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                              && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                      }
                    }

                 ?>
              </td>
              <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                              WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                              && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='bg-star'>".$risk_position['reference_no'] ."</span><br/>";
                      }
                    }

                 ?>
              </td>
              <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                              WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                              && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                      }
                    }

                 ?>
              </td>
              <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 16">
                <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                              WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                              && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";

                      }
                    }

                 ?>
              </td>
              <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 20">
                <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                              WHERE dep_code='".$select_department."'&& current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                              && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                      }
                    }

                 ?>
              </td>

        </tr>
        <tr>
          <td>Moderate <br/><small class="text-primary">3</small></td>
          <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 9">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
        </tr>
        <tr>
          <td>Minor <br/><small class="text-primary">2</small></td>
          <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td  class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
        </tr>
        <tr>
          <td>Insignificant <br/><small class="text-primary">1</small></td>
          <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 1">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
          <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 5">
            <?php
               $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                          WHERE dep_code='".$select_department."'&& current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                          && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10 ");
                if(mysqli_num_rows($sql) > 0)
                {
                  while ($risk_position = mysqli_fetch_array($sql)) {
                    echo "<span class='ref_no'>". $risk_position['reference_no'] ."</span><br/>";
                  }
                }

             ?>
          </td>
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
<div class="col-md-4 heatmap-ratings-table"  style="overflow:wrap;width:500px; float:right;">
<?php
  $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."'
    && risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."'
     && status='approved'
     && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10");
  $number = 1;
  if($total_rows = mysqli_num_rows($sql_query) > 0)
  {?>
    <table class="table table-bordered table-striped table-hover" id="risks-heatmap-opportunities-table">
        <thead>
          <tr>
            <td style="font-size:12px;">No</td>
            <td style="font-size:12px;">Risk</td>
            <td style="font-size:12px;">Score</td>
            <td style="font-size:12px;">Ref No</td>
          </tr>
        </thead>
        <?php
        while($row = mysqli_fetch_array($sql_query))
        {
         ?>
          <tr>
            <td style="font-size:12px;"><?php echo $number++ ;?></td>
            <td style="font-size:12px;"><?php echo $row['risk_description'];?></td>
            <td style="font-size:12px;"><?php echo $row['current_overall_score'];?></td>
            <td style="font-size:12px;"><?php echo $row['reference_no'];?></td>
          </tr>
        <?php
}
         ?>
      </table>

      <?php
}
else {
  ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <td class="text-danger"><i class="fa fa-info-circle"></i> No Records Found</td>

      </tr>

    </thead>
    <tr>
      <td class="text-danger">Sorry, no records have been found for
        the selected quarter (<span class="text-info"><?php echo $select_quarter;?></span>)
        and period (<span class="text-info"><?php echo $select_period;?></span>)
        for department (<span class="text-info"><?php echo $select_department;?></span>)

      </td>

    </tr>

  </table>

  <?php
}
       ?>
</div>
</div>
<!--end heatmap opportunities -->

<!-- start insert a page break -->
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->
<!-- start detailed status of corporate opportunites -->
<div class="card">
   <div class="card-header">
     <h3 class="card-title">Detailed Status of Opportunities</h3>
   </div>
   <!-- /.card-header -->
   <div class="card-body table-responsive no-padding">
     <?php
   //  $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE changed='no'"));
   /*  if($_SESSION['access_level'] == 'admin')
     {
       $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE changed='no' ORDER BY current_overall_score DESC");
     }
     else
     {*/
      //fetch stagnant risks
       $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."' &&
                      changed='no' &&
                     period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved'
                     && risk_opportunity='opportunity'
                     && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");

       /*
     }
     */

     $number = 1;
     $rank = 1;
     if($total_rows = mysqli_num_rows($sql_query) > 0)
     {?>
     <table class="table table-striped table-bordered table-hover" id="quarterly-updates-opportunity-reports-all-table" width="100%" style="overflow:wrap;">
       <thead>
         <tr>
           <td>#</td>
           <td style="font-size:14px;" width="100">Description</td>
           <td style="font-size:14px;" width="70">Current Rating</td>
           <td style="font-size:14px;" width="70">Prior Rating</td>
           <td style="font-size:14px;" width="200">Risk Drivers</td>
           <td style="font-size:14px;" width="200">Risk Management Strategy Undertaken</td>
           <td style="font-size:14px;" width="200">Effect of Risk to Authority</td>
           <td style="font-size:14px;" width="200">Further action to be undertaken</td>
           <td style="font-size:14px;" width="70">Person Responsible</td>
         </tr>
       </thead>
       <?php
       while($row = mysqli_fetch_array($sql_query))
       {
           //fetch department name from the risk management table
           $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE department_code='".$select_department."' && risk_reference='".$row['reference_no']."' &&  changed='no' && status='approved'"));
         ?>
       <tr style="cursor: pointer;">
         <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
         <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
         <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
           <td style="font-size:12px;"><?php echo $rank++ ;?></td>
         <td style="font-size:12px;"><?php echo $row['risk_description'];?></td>
         <td style="font-size:12px;"><?php echo $row['current_overall_score'];?>
           <br/>
             (<?php echo $row['current_impact_score'] .'*' . $row['current_likelihood_score'];?>)

             <br/>
             <?php
             if($row['current_overall_score'] > $row['prior_overall_score'])
             {
               ?> <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-up-48.png'><?php
             }
             else if($row['current_overall_score'] == $row['prior_overall_score'])
             {
               ?> <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-bi-48.png'><?php
             }

             else
             {
             ?> <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-down-48.png'><?php
             }
             ?>
         </td>

         <td style="font-size:12px;"><?php echo $row['prior_overall_score'];?>
         <br/>
           (<?php echo $row['prior_impact_score'] .'*' . $row['prior_likelihood_score'];?>)
         </td>
         <td style="font-size:12px;">
           <?php
             $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                   period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
             if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
               {
                       while($risk_driver_row = mysqli_fetch_array($driver_query))
                       {
                         ?>
                         - <?php echo $risk_driver_row['risk_drivers'];?></br>

                         <?php
                       }
                 }

           ?>
         </td>
         <td style="font-size:12px;">
           <?php
           $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                 period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
           if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
             {
                     while($risk_driver_row = mysqli_fetch_array($driver_query))
                     {
                       ?>
                       - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_management_strategy_undertaken']));?></br>

                       <?php
                     }
               }


            ?>


         </td>
         <td style="font-size:12px;">
           <?php
           $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                 period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
           if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
             {
                     while($risk_driver_row = mysqli_fetch_array($driver_query))
                     {
                       ?>
                       - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['effects_of_risk_to_authority']));?></br>

                       <?php
                     }
               }


            ?>

         </td>
         <td style="font-size:12px;">
           <?php
           $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                 period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
           if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
             {
                     while($risk_driver_row = mysqli_fetch_array($driver_query))
                     {
                       ?>
                       - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['action_to_be_undertaken']));?></br>

                       <?php
                     }
               }


            ?>
         </td style="font-size:12px;">
         <td style="font-size:12px;"><?php echo $fetch['person_responsible'];?></td>
       </tr>
       <?php
       }
       ?>
     </table>
     <?php
   } else
     {
      ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <td class="text-danger"><i class="fa fa-info-circle"></i> No Records Found</td>

          </tr>

        </thead>
        <tr>
          <td class="text-danger">Sorry, no records have been found for
            the selected quarter (<span class="text-info"><?php echo $select_quarter?></span>)
            and period (<span class="text-info"><?php echo $select_period?></span>)


          </td>

        </tr>

      </table>

      <?php
    }

     ?>
   </div>
 </div>
   <!-- /.card-body -->

<!-- end detailed status of corporate opportunities -->
<!-- start insert a page break -->
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<!-- start trend analysis-->
<div class="row">
  <div class="col-md-12">
    <h4>TREND ANALYSIS</h4>
    <?php
        /* Getting demo_viewer table data */
    $sql = "SELECT * FROM update_risk_status WHERE dep_code='".$select_department."'
            && period_from='2019-2020'
            && quarter='July - September (Quarter 1)'
            && changed='no'
            ORDER BY current_overall_score DESC LIMIT 5";
    $viewer = mysqli_query($dbc,$sql);
    $viewer = mysqli_fetch_all($viewer,MYSQLI_ASSOC);
    $all = mysqli_fetch_all($viewer,MYSQLI_ASSOC);
    $risk_description = json_encode(array_column($viewer, 'risk_description'),JSON_NUMERIC_CHECK);
    $risk_opp = json_encode(array_column($viewer, 'risk_opportunity'),JSON_NUMERIC_CHECK);
    $viewer = json_encode(array_column($viewer, 'current_overall_score'),JSON_NUMERIC_CHECK);



    /* Getting demo_click table data */
    $sql = "SELECT * FROM update_risk_status WHERE dep_code='".$select_department."'
            && period_from='2019-2020'
            && quarter='October - December (Quarter 2)'
            && changed='no'
            ORDER BY current_overall_score DESC LIMIT 5";
    $click = mysqli_query($dbc,$sql);
    $click = mysqli_fetch_all($click,MYSQLI_ASSOC);
    $click = json_encode(array_column($click, 'current_overall_score'),JSON_NUMERIC_CHECK);

    ?>
    <div id="container"></div>

  </div>
</div>

<!-- end trend analysis -->
<div style="page-break-after:always;"></div>

<!-- START EMERGING TRENDS -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Emerging Trends</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body table-responsive no-padding">
    <?php
    $sql_query = mysqli_query($dbc,"SELECT * FROM emerging_trends WHERE changed='no' && dep_code='".$select_department."'
                              && period='".$select_period."' && quarter ='".$select_quarter."' && is_corporate='no' ORDER BY id ASC");
    $number = 1;
    if($total_rows = mysqli_num_rows($sql_query) > 0)
    {?>
    <table class="table table-striped table-bordered table-hover" id="emerging-trends-table" style="overflow:hidden;">
      <thead>
        <tr>
          <td style="font-size:12px;">NO</td>
          <td style="font-size:12px;">Factor</td>
          <td style="font-size:12px;">External/Internal</td>
          <td style="font-size:12px;">Related Risk Event</td>
          <td style="font-size:12px;">Changes in Risk Profile</td>
        </tr>
      </thead>

      <?php
      while($row = mysqli_fetch_array($sql_query))
      {?>
      <tr style="cursor: pointer;">
        <td style="font-size:12px;"><?php echo $number++;?></td>
        <td style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['factor']));?></td>
        <td style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['external_internal']));?></td>
        <td style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['related_risk_event']));?></td>
        <td style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['changes_in_risk_profile']));?></td>
      </tr>
      <?php
      }
      ?>
    </table>
    <?php
    }
    ?>
  </div>
  <!-- /.card-body -->
</div>
<!-- end emerging trends -->

<!-- start insert a page break -->
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->
<!-- start lessons learnt -->
  <div class="row">
    <div class="col-xs-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Lessons Learnt</h3>
          <div class="feeback"></div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive no-padding">
          <?php
          $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_worked_well WHERE changed='no' && dep_code='".$select_department."' &&
                                          period='".$select_period."' && quarter='".$select_quarter."' && is_corporate='no' ORDER BY id DESC");
          $number = 1;
          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" width="100%" style="overflow:hidden;">
            <thead>
              <tr>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Worked Well</p></td>
              </tr>
            </thead>

            <?php
            while($row = mysqli_fetch_array($sql_query))
            {?>
            <tr style="cursor: pointer;">
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
            </tr>
            <?php
            }
            ?>
          </table>
          <p></p>
          <?php
          }
          else
          {
            ?>
              <p class="text-warning">No Strategies That Worked Well Found for the selected quarter</p>
            <?php
          }

          $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE changed='no' && dep_code='".$select_department."' &&
                                          period='".$select_period."' && quarter='".$select_quarter."' && is_corporate='no' ORDER BY id DESC");
          $number = 1;

          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" width="100%" style="overflow:hidden;">
            <thead>
              <tr>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Did Not Work</p></td>
              </tr>
            </thead>

            <?php
            while($row = mysqli_fetch_array($sql_query))
            {?>
            <tr style="cursor: pointer;">
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
            </tr>
            <?php
            }
            ?>
          </table>
          <p></p>
          <?php
          }
          else
          {
            ?>
              <p class="text-warning">No Strategic That Did Not Work Found for the selected quarter</p>
            <?php
          }

          $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE changed='no' && dep_code='".$select_department."' &&
                                          period='".$select_period."' && quarter='".$select_quarter."' && is_corporate='no' ORDER BY id DESC");
          $number = 1;

          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" width="100%" style="overflow:hidden;">
            <thead>
              <tr>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Near Misses</p></td>
              </tr>
            </thead>

            <?php
            while($row = mysqli_fetch_array($sql_query))
            {?>
            <tr style="cursor: pointer;">
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
            </tr>
            <?php
            }
            ?>
          </table>
          <?php
          }
          else
          {
            ?>
              <p class="text-warning">No Near Misses Found for the selected quarter</p>
            <?php
          }
          ?>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>

  </div>
  <!-- end lessons learnt -->
<!-- start insert a page break -->
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->
<!-- END EMERGING TRENDS -->
<?php
}
 ?>
<script type="text/javascript">


$(function () {


    var data_click = <?php echo $click; ?>;
    var data_viewer = <?php echo $viewer; ?>;
    var risk_description = <?php echo $risk_description; ?>;


    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 5 Risks & Opportunities Trend Analysis'
        },
        xAxis: {
            categories: risk_description,
        },
        yAxis: {
            title: {
                text: 'Rating'
            }
        },
        series: [{
            name: 'QUARTER 2',
            data: data_click
        }, {
            name: 'QUARTER 1',
            data: data_viewer
        }],
        exporting: {
        enabled: true,
        allowHTML: true,
        fallbackToExportServer: true,
        sourceWidth: 1000,
        scale: 1,
        chartOptions: {
          subtitle: null,
          chart: {
            marginLeft: 100,
            marginRight: 100
          }
        }
      }
    });
});


</script>
