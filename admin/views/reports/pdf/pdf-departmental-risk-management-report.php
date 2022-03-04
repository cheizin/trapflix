<?php
session_start();
require_once('../../../controllers/setup/connect.php');
ob_start();
 ?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link href="https://fonts.googleapis.com/css?family=Crimson+Text&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/css/pdf.css" media="all">

</head>

<body>
  <?php

  $select_period = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $select_quarter = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
  $year_id= mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
  $select_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
  $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
  if($_SERVER['REQUEST_METHOD'] == "POST")
  {
    //fetch for superuser

    ?>
    <!-- start insert a page break -->
        <center>
            <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
        </center>
         <h1 style="text-align:center;">
          <?php echo $select_department ;?> <br/>
          Performance Risk Management Report <br/>Quarter ended <?php echo $select_quarter .', '.$select_period ;?>
         </h1>



     <!-- /.box-header -->
      <div style="page-break-after:always;"></div>
    <!-- start static content -->
    <div class="row">
     <?php include("static-content-report.php");?>
    </div>


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
       <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Detailed Activities With Risks:<br/><b>
               </b>
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
             <table class="table" width="100%" id="activities-with-risks-table">
               <thead>
                 <tr>
                   <td width="3%">#</td>
                   <td>Strategic Objective</td>
                   <td>Strategic Outcome</td>
                   <td>Strategic KPI</td>
                   <td>Departmental Objective</td>
                   <td>Related Risk/s</td>
                   <td>Activity Description</td>
                   <td>Departmental Activity KPI</td>
                   <td>Activity Performance Update</td>
                 </tr>
               </thead>
               <?php
               $number = 1;
               while($row = mysqli_fetch_array($sql))
               {
                 ?>
                   <tr>
                     <td><?php echo $number++ ;?></td>
                     <td>

                       <div>
                         <table>
                           <?php echo $row['SOB_DESC'] ;?>
                         </table>
                       </div>

                     </td>
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
                                 <div>
                                   <table>
                                     -> <?php echo $outcome_description['strategic_outcome_description'] ;?>
                                   </table>
                                 </div>
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
                                 <div>
                                   <table>
                                     -><?php echo $kpi_description['strategic_kpi_description'] ;?>
                                   </table>
                                 </div>
                                 <?php
                               }
                             }
                        ?>
                     </td>
                     <td>
                       <div>
                         <table>
                           <?php
                                 $mapped_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_objectives
                                                                   WHERE departmental_objective_id='".$row['DOB_DESC']."'"));
                                   echo $mapped_objective['departmental_objective_description'] ;
                             ?>
                           </table>
                         </div>
                     </td>
                     <td>
                       <div>
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
                                   <div style="background-color:#FF0000;">
                                         <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                         <?php echo $overall_score; ?>
                                 </div>
                                   <?php
                                 }
                                 if($overall_score < 17 && $overall_score > 9 && $risk_description['risk_opportunity'] == 'risk')
                                 {
                                   ?>
                                   <div style="background-color:#FFC200;">
                                         <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                         <?php echo $overall_score; ?>
                                 </div>
                                   <?php
                                 }
                                 if($overall_score < 10 && $overall_score > 5 && $risk_description['risk_opportunity'] == 'risk')
                                 {
                                   ?>
                                   <div style="background-color:#FFFF00;">
                                         <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                         <?php echo $overall_score; ?>
                                 </div>
                                   <?php
                                 }
                                 if($overall_score < 5 && $overall_score > 2 && $risk_description['risk_opportunity'] == 'risk')
                                 {
                                   ?>
                                   <div style="background-color:#00FF00;">
                                         <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                         <?php echo $overall_score; ?>
                                 </div>
                                   <?php
                                 }
                                 if($overall_score < 3 && $overall_score > 0 && $risk_description['risk_opportunity'] == 'risk')
                                 {
                                   ?>
                                   <div style="background-color:#006400;">
                                         <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                         <?php echo $overall_score; ?>
                                 </div>
                                   <?php
                                 }
                                 //for opportunities
                                 if($overall_score < 26 && $overall_score > 19 && $risk_description['risk_opportunity'] == 'opportunity')
                                 {
                                   ?>
                                   <div style="background-color:#0272a6;">
                                         <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                         <?php echo $overall_score; ?>
                                 </div>
                                   <?php
                                 }
                                 if($overall_score < 17 && $overall_score > 9 && $risk_description['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background-color:#008dcf;">
                                        <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                        <?php echo $overall_score; ?>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $risk_description['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background-color:#59b4e0;">
                                        <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                        <?php echo $overall_score; ?>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $risk_description['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background-color:#99d1ec">
                                        <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                        <?php echo $overall_score; ?>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $risk_description['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background-color:#d4ecf8;">
                                        <?php echo $risk_description_updated['risk_description'] ;?><br/>
                                        <?php echo $overall_score; ?>
                                </div>
                                  <?php
                                }

                                if($overall_score <1)
                                {
                                  ?>
                                 <div>
                                     N/A<br/>
                                  </div>
                                  <?php
                                }
                                 ?>
                                 <?php
                               }
                             }
                        ?>
                      </div>

                     </td>
                     <?php
                       //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                         $int = $row['CURRENT_ESTIMATE'];
                         if($int < 20 && $int > 0)
                         {
                           ?>
                           <td style="background-color:#FF0000;color:white;">
                             <div>
                               <table>
                                 <?php echo $row['ACT_DESC'];?>
                                 <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                               </table>
                             </div>
                           </td>

                           <?php
                         }
                         if($int < 40 && $int > 19)
                         {
                           ?>
                           <td style="background-color:#FFC200;color:black;">
                                <div>
                                  <table>
                                    <?php echo $row['ACT_DESC'];?>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                   </table>
                                </div>
                           </td>

                           <?php
                         }
                         if($int < 60 && $int > 39)
                         {
                           ?>
                           <td style="background-color:#FFFF00;color:black;">
                             <div>
                               <table>
                                  <?php echo $row['ACT_DESC'];?>
                                   <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                 </table>
                              </div>
                           </td>
                           <?php
                         }
                         if($int < 80 && $int > 59)
                         {
                           ?>
                           <td style="background-color:#00FF00; color:black;">
                             <div>
                               <table>
                                  <?php echo $row['ACT_DESC'];?>

                                   <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                 </table>
                              </div>
                           </td>
                           <?php
                         }
                         if($int < 101 && $int > 79)
                         {
                           ?>
                           <td style="background-color:#006400; color:white;">
                             <div>
                               <table>
                                  <?php echo $row['ACT_DESC'];?>

                                   <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                 </table>
                              </div>
                           </td>
                           <?php
                         }
                         if($int < 1)
                         {
                           ?>
                           <td>
                             <div>
                               <table>
                                 N/A
                               </table>
                             </div>
                           </td>
                           <?php
                         }

                      ?>
                     <td>
                       <div>
                         <table>
                           <?php echo $row['DEP_KPI'] ;?>
                         </table>
                       </div>

                     </td>
                     <td>
                       <div>
                         <table>
                         <?php echo $row['ACT_UPDATE'] ;?>
                       </table>
                     </div>

                     </td>
                   </tr>
                 <?php
               }
                ?>
             </table>
        </div>
      </div>
      <!-- /.box -->
        <?php
      } // end num row
    }

      ?>
  <!-- start insert a page break -->
   <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->

<?php
$sql_heatmap_row = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM update_risk_status
                           WHERE dep_code='".$select_department."' && changed='no'
                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."'
                           && status='approved'
                           && risk_status='open'"));
if($sql_heatmap_row > 0)
{
 ?>
  <!-- start risk analysis -->
  <div class="box">
  <h4 class="box-title">RISK ANALYSIS<br/>Overview</h4>
  <div class="box-body table-responsive no-padding">
    <div class="col-md-8 heatmap-opportunity-chart" style="width:500px; float:left;">
      <table class="heatmap-table">
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                        echo $risk_position['reference_no']."<br>";
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
                        echo $risk_position['reference_no']."<br>";
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
                        echo $risk_position['reference_no']."<br>";
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
                        echo $risk_position['reference_no']."<br>";

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
                        echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
                    echo $risk_position['reference_no']."<br>";
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
     </table>
     </div>

  <!-- end of first div-col-8 -->
  <div class="col-md-4 heatmap-ratings-table"  style="overflow:wrap;width:730px; float:right;">
  <?php
   $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."'
                                      && risk_opportunity='risk' && changed='no'  && period_from='".$select_period."'
                                      && quarter='".$select_quarter."' && status='approved'
                                      && risk_status='open' ORDER BY current_overall_score DESC LIMIT 10");

   $number = 1;
   if($total_rows = mysqli_num_rows($sql_query) > 0)
   {?>
     <table>
         <thead>
           <tr>
             <td>#</td>
             <td>Risk</td>
             <td>Score</td>
             <td>Ref No</td>
           </tr>
         </thead>
         <?php
         while($row = mysqli_fetch_array($sql_query))
         {
           $sql_query_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' && changed='no'"));
          ?>
           <tr>
             <td><?php echo $number++ ;?></td>
             <td><?php echo $sql_query_description['risk_description'];?></td>
             <td><?php echo $row['current_overall_score'];?></td>
             <td><?php echo $row['reference_no'];?></td>
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
  <?php
}
   ?>
  <!-- end risk analysis -->
  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
  <!-- START RISK DRIVERS -->
  <?php

    $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                  dep_code='".$select_department."' && changed='no' && status='approved' &&
                  risk_opportunity='risk' && period_from='".$select_period."' && quarter='".$select_quarter."'
                  && risk_status='open'
                 ORDER BY id DESC");
  $number = 1;
  $rank = 1;
  if($total_rows = mysqli_num_rows($sql_query) > 0)
  {?>

  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Detailed Status of Departmental Risks</h3>
    </div>
     <!-- end of departmental activities with risks -->
     <div class="box-body table-responsive no-padding">
       <table class="detailed-analysis-table" width="100%">
         <thead>
           <tr>
             <td width="3%">#</td>
             <td width="10%">Description</td>
             <td width="5%">Current Rating</td>
             <td width="5%">Prior Rating</td>
             <td>Risk Drivers</td>
             <td>Risk Management Strategy Undertaken</td>
             <td>Effect of Risk to Authority</td>
             <td>Further action to be undertaken</td>
             <td width="9%">Person Responsible</td>
           </tr>
         </thead>
         <?php
         while($row = mysqli_fetch_array($sql_query))
         {
             //fetch department name from the risk management table
             $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no'"));
           ?>
         <tr>
           <td><?php echo $rank++ ;?></td>
           <td><?php echo $row['risk_description'];?></td>
           <td><?php echo $row['current_overall_score'];?>
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
           <td><?php echo $row['prior_overall_score'];?>
           <br/>
             (<?php echo $row['prior_impact_score'] .'*' . $row['prior_likelihood_score'];?>)
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
                                 <p> - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_drivers']));?></p>

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
                         <div>
                           <table>
                             <p> - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_management_strategy_undertaken']));?></p>
                           </table>
                        </div>
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
                         <div>
                           <table>
                            <p>- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['effects_of_risk_to_authority']));?></p>
                            </table>
                         </div>
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
                         <div>
                           <table>
                             <p> - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['action_to_be_undertaken']));?></p>
                           </table>
                        </div>
                         <?php
                       }
                 }


              ?>
           </td>
           <td><?php echo $fetch['person_responsible'];?></td>
         </tr>
         <?php
         }
         ?>
       </table>
     </div>
   </div>
   <?php
}
    ?>
     <!-- /.box-body -->
  <!-- END Detailed Status of Departmental Risks -->
  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->

  <!--start heatmap opportunities -->
  <?php
$sql_heatmap_row = mysqli_num_rows( mysqli_query($dbc,"SELECT * FROM update_risk_status
                           WHERE dep_code='".$select_department."' && changed='no'
                           && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."'
                           && status='approved' && risk_status='open'"));
if($sql_heatmap_row > 0)
{
 ?>
  <div class="box">
  <h4 class="box-title">OPPORTUNITY ANALYSIS<br/>Overview</h4>
  <div class="box-body table-responsive no-padding">
    <div class="col-md-8 heatmap-opportunity-chart" style="overflow:wrap;width:500px; float:left;">
      <table class="heatmap-table" style="page-break-inside: avoid;">
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                         echo $risk_position['reference_no']."<br>";
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
                         echo $risk_position['reference_no']."<br>";
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
                         echo $risk_position['reference_no']."<br>";

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
                         echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
                     echo $risk_position['reference_no']."<br>";
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
  <div class="col-md-4 heatmap-ratings-table"  style="overflow:wrap;width:730px; float:right;">
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
              <td>#</td>
              <td>Risk</td>
              <td>Score</td>
              <td>Ref No</td>
            </tr>
          </thead>
          <?php
          while($row = mysqli_fetch_array($sql_query))
          {
           ?>
            <tr>
              <td><?php echo $number++ ;?></td>
              <td><?php echo $row['risk_description'];?></td>
              <td><?php echo $row['current_overall_score'];?></td>
              <td><?php echo $row['reference_no'];?></td>
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
        <td class="text-danger">Sorry, no opportunites have been found for
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
  <?php
}
   ?>
  <!--end heatmap opportunities -->

  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
  <!-- start detailed status of corporate opportunites -->
  <?php

    $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$select_department."' &&
                   changed='no' &&
                  period_from='".$select_period."' && quarter='".$select_quarter."' && status='approved'
                  && risk_opportunity='opportunity'
                  && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");

  $number = 1;
  $rank = 1;
  if($total_rows = mysqli_num_rows($sql_query) > 0)
  {?>
  <div class="box">
     <div class="box-header">
       <h3 class="box-title">Detailed Status of Opportunities</h3>
     </div>
     <!-- /.box-header -->
     <div class="box-body table-responsive no-padding">
       <table class="detailed-analysis-table" width="100%">
         <thead>
           <tr>
             <td width="3%">#</td>
             <td width="10%">Description</td>
             <td width="5%">Current Rating</td>
             <td width="5%">Prior Rating</td>
             <td>Risk Drivers</td>
             <td>Risk Management Strategy Undertaken</td>
             <td>Effect of Risk to Authority</td>
             <td>Further action to be undertaken</td>
             <td width="9%">Person Responsible</td>
           </tr>
         </thead>
         <?php
         while($row = mysqli_fetch_array($sql_query))
         {
             //fetch department name from the risk management table
             $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE department_code='".$select_department."' && risk_reference='".$row['reference_no']."' &&  changed='no' && status='approved'"));
           ?>
         <tr>
           <td><?php echo $rank++ ;?></td>
           <td><?php echo $row['risk_description'];?></td>
           <td><?php echo $row['current_overall_score'];?>
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

           <td><?php echo $row['prior_overall_score'];?>
           <br/>
             (<?php echo $row['prior_impact_score'] .'*' . $row['prior_likelihood_score'];?>)
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
                           - <?php echo $risk_driver_row['risk_drivers'];?></br>

                           <?php
                         }
                   }

             ?>
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
                         - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_management_strategy_undertaken']));?></br>

                         <?php
                       }
                 }


              ?>


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
                         - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['effects_of_risk_to_authority']));?></br>

                         <?php
                       }
                 }


              ?>

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
                         - <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['action_to_be_undertaken']));?></br>

                         <?php
                       }
                 }


              ?>
           </td>
           <td><?php echo $fetch['person_responsible'];?></td>
         </tr>
         <?php
         }
         ?>
       </table>
     </div>
   </div>
   <?php
}
    ?>
     <!-- /.box-body -->

  <!-- end detailed status of corporate opportunities -->
  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->

  <!-- START EMERGING TRENDS -->
  <?php
  $sql_query = mysqli_query($dbc,"SELECT * FROM emerging_trends WHERE changed='no' && dep_code='".$select_department."'
                            && period='".$select_period."' && quarter ='".$select_quarter."' && is_corporate='no' ORDER BY id ASC");
  $number = 1;
  if($total_rows = mysqli_num_rows($sql_query) > 0)
  {?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Emerging Trends</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
      <table class="simple-table" style="overflow:hidden;">
        <thead>
          <tr>
            <td>#</td>
            <td>Factor</td>
            <td>External/Internal</td>
            <td>Related Risk Event</td>
            <td>Changes in Risk Profile</td>
          </tr>
        </thead>

        <?php
        while($row = mysqli_fetch_array($sql_query))
        {?>
        <tr style="cursor: pointer;">
          <td><?php echo $number++;?></td>
          <td><?php echo  htmlspecialchars_decode(stripslashes($row['factor']));?></td>
          <td><?php echo  htmlspecialchars_decode(stripslashes($row['external_internal']));?></td>
          <td><?php echo  htmlspecialchars_decode(stripslashes($row['related_risk_event']));?></td>
          <td><?php echo  htmlspecialchars_decode(stripslashes($row['changes_in_risk_profile']));?></td>
        </tr>
        <?php
        }
        ?>
      </table>
    </div>
    <!-- /.box-body -->
  </div>
  <?php
  }
  ?>
  <!-- end emerging trends -->

  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
  <!-- start lessons learnt -->
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="feeback"></div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <?php
            $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_worked_well WHERE changed='no' && dep_code='".$select_department."' &&
                                            period='".$select_period."' && quarter='".$select_quarter."' && is_corporate='no' ORDER BY id DESC");
            $number = 1;
            if($total_rows = mysqli_num_rows($sql_query) > 0)
            {?>
              <h3 class="box-title">Lessons Learnt</h3>
            <table class="simple-table" width="100%" style="overflow:hidden;">
              <thead>
                <tr>
                  <td><p>#</p></td>
                  <td><p>Strategies That Worked Well</p></td>
                </tr>
              </thead>

              <?php
              while($row = mysqli_fetch_array($sql_query))
              {?>
              <tr style="cursor: pointer;">
                <td><p><?php echo $number++;?></p></td>
                <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
              </tr>
              <?php
              }
              ?>
            </table>
            <?php
            }

            $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE changed='no' && dep_code='".$select_department."' &&
                                            period='".$select_period."' && quarter='".$select_quarter."' && is_corporate='no' ORDER BY id DESC");
            $number = 1;

            if($total_rows = mysqli_num_rows($sql_query) > 0)
            {?>
            <table class="simple-table" width="100%" style="overflow:hidden;">
              <thead>
                <tr>
                  <td><p>#</p></td>
                  <td><p>Strategies That Did Not Work</p></td>
                </tr>
              </thead>

              <?php
              while($row = mysqli_fetch_array($sql_query))
              {?>
              <tr style="cursor: pointer;">
                <td><p><?php echo $number++;?></p></td>
                <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
              </tr>
              <?php
              }
              ?>
            </table>
            <?php
            }

            $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE changed='no' && dep_code='".$select_department."' &&
                                            period='".$select_period."' && quarter='".$select_quarter."' && is_corporate='no' ORDER BY id DESC");
            $number = 1;

            if($total_rows = mysqli_num_rows($sql_query) > 0)
            {?>
            <table class="simple-table" width="100%" style="overflow:hidden;">
              <thead>
                <tr>
                  <td><p>#</p></td>
                  <td><p>Near Misses</p></td>
                </tr>
              </thead>

              <?php
              while($row = mysqli_fetch_array($sql_query))
              {?>
              <tr style="cursor: pointer;">
                <td><p><?php echo $number++;?></p></td>
                <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
              </tr>
              <?php
              }
              ?>
            </table>
            <?php
            }
            ?>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>

    </div>
    <!-- end lessons learnt -->
  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
  <!-- END EMERGING TRENDS -->


</body>
</html>
<?php
$select_quarter = implode(" ",$select_quarter);
$filename = $select_department."_Departmental_Risk_Management_Report_".$select_period."_".$select_quarter;
$html = ob_get_contents();
ob_end_clean();
file_put_contents("{$filename}.html", $html);


//convert HTML to PDF
shell_exec("wkhtmltopdf -O landscape  --footer-html pdffooter.html -q {$filename}.html {$filename}.pdf");
if(file_exists("{$filename}.pdf")){
  header("Content-type:application/pdf");
  header("Content-Disposition:attachment;filename={$filename}.pdf");
  echo file_get_contents("{$filename}.pdf");
  //echo "{$filename}.pdf";
}else{
  exit;
}
// the dompdf category for instantiate and use
/*$dom_pdf = new Dompdf();
$dom_pdf->set_option('isRemoteEnabled', TRUE);
$dom_pdf->set_base_path('https://pprmis.cma.or.ke/prmis/dist/css/dompdf.css');
$dom_pdf->load_html(ob_get_clean());

// (Optional) Setup the paper size and orientation
$dom_pdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dom_pdf->render();

// Output the generated PDF to Browser
$dom_pdf->stream();
*/
}
?>
