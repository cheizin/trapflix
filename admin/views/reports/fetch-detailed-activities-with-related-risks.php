<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
  $select_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
  $activity_type = mysqli_real_escape_string($dbc,strip_tags($_POST['activity_type']));
  $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
  $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));

  // FOR ALL ACTIVITY TYPES AND ALL Departments
  if($_POST['activity_type'] == "all" && $_POST['departments'] == "all")
  {
    ob_start();

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
                                      AND f.changed = 'no'
                                      AND f.year_id = '".$year_id."'
                                      AND f.quarter_id = '".$quarter_id."'
                                      AND a.activity_status='open'
                                      AND d.risk_reference IS NOT NULL

                                      GROUP BY a.activity_id
                            "
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {

      ?>

      <!-- start insert a page break -->
      <!-- end insert a page break -->
      <div class="card">
       <div class="card-header with-border">
         <h3 class="card-title">Detailed Activities With Related Risks (All Activity Types for All Departments):<br/>
         </h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
              <thead>
                <tr>
                  <td>NO</td>
                  <td>Strategic Objective</td>
                  <td>Strategic Outcome</td>
                  <td>Strategic KPI</td>
                  <td>Departmental Objective</td>
                  <td>Related Risk</td>
                  <td>Departmental Sub Objective</td>
                  <td>Activity Type</td>
                  <td>Department</td>
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
                    <td><?php echo $row['SOB_DESC'] ;?></td>
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
                                  - <?php echo $outcome_description['strategic_outcome_description'] ;?> <br/>

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
                              - <?php echo $kpi_description['strategic_kpi_description'] ;?><br/>
                                <?php
                              }
                            }
                       ?>
                    </td>
                    <td>
                      <?php
                            $mapped_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_objectives
                                                              WHERE departmental_objective_id='".$row['DOB_DESC']."'"));
                              echo $mapped_objective['departmental_objective_description'] ;
                        ?>
                    </td>
                    <td width="200px">
                      <?php
                            $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                    && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                            if(mysqli_num_rows($sql_related_risks) > 0){
                              while($related_risks = mysqli_fetch_array($sql_related_risks))
                              {
                                $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."' && changed='no'"));
                                $current_overall = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no='".$related_risks['risk_reference']."'
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFC200; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFFF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#00FF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                  <div style="background:#006400;overflow: auto;color:white;">
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#008dcf; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#59b4e0; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#99d1ec; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background:#d4ecf8;overflow: auto;color:white;">
                                    <?php echo $risk_description['risk_description'] ;?><br/>
                                    <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                    <hr style="solid"/>
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
                    <td>
                      <?php
                          $select_sub_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_sub_objectives
                                                                  WHERE department_sub_objective_id = '".$row['DSO_DESC']."'"));
                      echo $select_sub_objective['department_sub_objective_description'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_TYPE'] ;?>
                    </td>
                    <td>
                    <?php
                          $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                          department_id='".$row['DEP_ID']."'"));


                    echo $department_name['department_name'] ;?>
                    </td>
                    <?php
                      //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                        $int = $row['CURRENT_ESTIMATE'];
                        if($int < 20 && $int > 0)
                        {
                          ?>
                          <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 40 && $int > 19)
                        {
                          ?>
                          <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 60 && $int > 39)
                        {
                          ?>
                          <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 80 && $int > 59)
                        {
                          ?>
                          <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>

                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 101 && $int > 79)
                        {
                          ?>
                          <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>

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
                    <td>
                    <?php echo $row['DEP_KPI'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_UPDATE'] ;?>

                    </td>


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
    else
    {
      exit(mysqli_error($dbc));
    }
    ?>

    <?php
    exit();
  }

  //FOR A SPECIFIC ACTIVITY TYPE, BUT FOR ALL Departments
  if($_POST['activity_type'] != "all" && $_POST['departments'] == "all")
  {
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
                                      a.activity_type_id = '".$activity_type."'
                                      AND d.year_id = '".$year_id."'
                                      AND d.quarter_id = '".$quarter_id."'
                                      AND e.changed = 'no'
                                      AND e.period_from = '".$year_id."'
                                      AND e.quarter = '".$quarter_id."'
                                      AND f.changed = 'no'
                                      AND f.year_id = '".$year_id."'
                                      AND f.quarter_id = '".$quarter_id."'
                                      AND a.activity_status='open'
                                      AND d.risk_reference IS NOT NULL

                                      GROUP BY a.activity_id
                            "
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {

      ?>

      <!-- start insert a page break -->
      <!-- end insert a page break -->
      <div class="card">
       <div class="card-header with-border">
         <h3 class="card-title">Detailed Activities With Related Risks (Specific Activity Type for All Departments):<br/>
         </h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
              <thead>
                <tr>
                  <td>NO</td>
                  <td>Strategic Objective</td>
                  <td>Strategic Outcome</td>
                  <td>Strategic KPI</td>
                  <td>Departmental Objective</td>
                  <td>Related Risk</td>
                  <td>Departmental Sub Objective</td>
                  <td>Activity Type</td>
                  <td>Department</td>
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
                    <td><?php echo $row['SOB_DESC'] ;?></td>
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
                                  - <?php echo $outcome_description['strategic_outcome_description'] ;?> <br/>

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
                              - <?php echo $kpi_description['strategic_kpi_description'] ;?><br/>
                                <?php
                              }
                            }
                       ?>
                    </td>
                    <td>
                      <?php
                            $mapped_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_objectives
                                                              WHERE departmental_objective_id='".$row['DOB_DESC']."'"));
                              echo $mapped_objective['departmental_objective_description'] ;
                        ?>
                    </td>
                    <td width="200px">
                      <?php
                            $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                    && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                            if(mysqli_num_rows($sql_related_risks) > 0){
                              while($related_risks = mysqli_fetch_array($sql_related_risks))
                              {
                                $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."' && changed='no'"));
                                $current_overall = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no='".$related_risks['risk_reference']."'
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFC200; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFFF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#00FF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                  <div style="background:#006400;overflow: auto;color:white;">
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#008dcf; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#59b4e0; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#99d1ec; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background:#d4ecf8;overflow: auto;color:white;">
                                    <?php echo $risk_description['risk_description'] ;?><br/>
                                    <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                    <hr style="solid"/>
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
                    <td>
                      <?php
                          $select_sub_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_sub_objectives
                                                                  WHERE department_sub_objective_id = '".$row['DSO_DESC']."'"));
                      echo $select_sub_objective['department_sub_objective_description'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_TYPE'] ;?>
                    </td>
                    <td>
                    <?php
                          $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                          department_id='".$row['DEP_ID']."'"));


                    echo $department_name['department_name'] ;?>
                    </td>
                    <?php
                      //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                        $int = $row['CURRENT_ESTIMATE'];
                        if($int < 20 && $int > 0)
                        {
                          ?>
                          <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 40 && $int > 19)
                        {
                          ?>
                          <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 60 && $int > 39)
                        {
                          ?>
                          <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 80 && $int > 59)
                        {
                          ?>
                          <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>

                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 101 && $int > 79)
                        {
                          ?>
                          <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
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
                    <td>
                    <?php echo $row['DEP_KPI'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_UPDATE'] ;?>

                    </td>


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
    else
    {
      exit(mysqli_error($dbc));
    }
    ?>

    <?php
    exit();
  }

  //FOR A SPECIFIC ACTIVITY TYPE AND FOR A SPECIFIC Department
  if($_POST['activity_type'] != "all" && $_POST['departments'] != "all")
  {
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
                                      a.activity_type_id = '".$activity_type."'
                                      AND
                                      a.department_id = '".$select_department."'
                                      AND
                                      e.changed = 'no'
                                      AND d.year_id = '".$year_id."'
                                      AND d.quarter_id = '".$quarter_id."'
                                      AND e.period_from = '".$year_id."'
                                      AND e.quarter = '".$quarter_id."'
                                      AND f.changed = 'no'
                                      AND f.year_id = '".$year_id."'
                                      AND f.quarter_id = '".$quarter_id."'
                                      AND d.changed='no'
                                      AND a.activity_status='open'
                                      AND d.risk_reference IS NOT NULL

                                      GROUP BY a.activity_id
                            "
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {

      ?>

      <!-- start insert a page break -->
      <!-- end insert a page break -->
      <div class="card">
       <div class="card-header with-border">
         <h3 class="card-title">Detailed Activities With Related Risks (Specific Activity Types for Selected Department):<br/>
         </h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
              <thead>
                <tr>
                  <td>NO</td>
                  <td>Strategic Objective</td>
                  <td>Strategic Outcome</td>
                  <td>Strategic KPI</td>
                  <td>Departmental Objective</td>
                  <td>Related Risk</td>
                  <td>Departmental Sub Objective</td>
                  <td>Activity Type</td>
                  <td>Department</td>
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
                    <td><?php echo $row['SOB_DESC'] ;?></td>
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
                                  - <?php echo $outcome_description['strategic_outcome_description'] ;?> <br/>

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
                              - <?php echo $kpi_description['strategic_kpi_description'] ;?><br/>
                                <?php
                              }
                            }
                       ?>
                    </td>
                    <td>
                      <?php
                            $mapped_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_objectives
                                                              WHERE departmental_objective_id='".$row['DOB_DESC']."'"));
                              echo $mapped_objective['departmental_objective_description'] ;
                        ?>
                    </td>
                    <td width="200px">
                      <?php
                            $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                    && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                            if(mysqli_num_rows($sql_related_risks) > 0){
                              while($related_risks = mysqli_fetch_array($sql_related_risks))
                              {
                                $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."' && changed='no'"));
                                $current_overall = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no='".$related_risks['risk_reference']."'
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFC200; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFFF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#00FF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                  <div style="background:#006400;overflow: auto;color:white;">
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#008dcf; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#59b4e0; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#99d1ec; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background:#d4ecf8;overflow: auto;color:white;">
                                    <?php echo $risk_description['risk_description'] ;?><br/>
                                    <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                    <hr style="solid"/>
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
                    <td>
                      <?php
                          $select_sub_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_sub_objectives
                                                                  WHERE department_sub_objective_id = '".$row['DSO_DESC']."'"));
                      echo $select_sub_objective['department_sub_objective_description'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_TYPE'] ;?>
                    </td>
                    <td>
                    <?php
                          $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                          department_id='".$row['DEP_ID']."'"));


                    echo $department_name['department_name'] ;?>
                    </td>
                    <?php
                      //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                        $int = $row['CURRENT_ESTIMATE'];
                        if($int < 20 && $int > 0)
                        {
                          ?>
                          <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 40 && $int > 19)
                        {
                          ?>
                          <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 60 && $int > 39)
                        {
                          ?>
                          <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 80 && $int > 59)
                        {
                          ?>
                          <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>

                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 101 && $int > 79)
                        {
                          ?>
                          <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>

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
                    <td>
                    <?php echo $row['DEP_KPI'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_UPDATE'] ;?>

                    </td>


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
    else
    {
      exit(mysqli_error($dbc));
    }
    ?>

    <?php
    exit();
  }

  //FOR ALL Activities BUT FOR A SPECIFIC DEPARTMENT
  if($_POST['activity_type'] == "all" && $_POST['departments'] != "all")
  {
    ob_start();

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
                                      a.department_id = '".$selected_department."'
                                      AND d.year_id = '".$year_id."'
                                      AND d.quarter_id = '".$quarter_id."'
                                      AND  e.changed = 'no'
                                      AND e.period_from = '".$year_id."'
                                      AND e.quarter = '".$quarter_id."'
                                      AND f.changed = 'no'
                                      AND f.year_id = '".$year_id."'
                                      AND f.quarter_id = '".$quarter_id."'
                                      AND a.activity_status='open'
                                      AND d.risk_reference IS NOT NULL

                                      GROUP BY a.activity_id
                            "
                            );
    if($sql)
    {
      $total_rows = mysqli_num_rows($sql);
      if($total_rows > 0)
      {

      ?>

      <!-- start insert a page break -->
      <!-- end insert a page break -->
      <div class="card">
       <div class="card-header with-border">
         <h3 class="card-title">Detailed Activities With Related Risks:<br/><b>
                <?php
                    $sql_select_department =mysqli_fetch_array(mysqli_query($dbc,"SELECT department_id,department_name
                                                      FROM departments WHERE department_id='".$selected_department."'"));
                echo $sql_select_department['department_name'];

                ;?>
              </b>
         </h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
              <thead>
                <tr>
                  <td>NO</td>
                  <td>Strategic Objective</td>
                  <td>Strategic Outcome</td>
                  <td>Strategic KPI</td>
                  <td>Departmental Objective</td>
                  <td>Related Risk</td>
                  <td>Departmental Sub Objective</td>
                  <td>Activity Type</td>
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
                    <td><?php echo $row['SOB_DESC'] ;?></td>
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
                                  - <?php echo $outcome_description['strategic_outcome_description'] ;?> <br/>

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
                              - <?php echo $kpi_description['strategic_kpi_description'] ;?><br/>
                                <?php
                              }
                            }
                       ?>
                    </td>
                    <td>
                      <?php
                            $mapped_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_objectives
                                                              WHERE departmental_objective_id='".$row['DOB_DESC']."'"));
                              echo $mapped_objective['departmental_objective_description'] ;
                        ?>
                    </td>
                    <td width="200px">
                      <?php
                            $sql_related_risks = mysqli_query($dbc,"SELECT * FROM activity_related_risks WHERE activity_id='".$row['ACT_ID']."'
                                                                    && changed='no' && year_id='".$year_id."' && quarter_id='".$quarter_id."'") or die(mysqli_error($dbc));
                            if(mysqli_num_rows($sql_related_risks) > 0){
                              while($related_risks = mysqli_fetch_array($sql_related_risks))
                              {
                                $risk_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$related_risks['risk_reference']."' && changed='no'"));
                                $current_overall = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no='".$related_risks['risk_reference']."'
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFC200; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#FFFF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                <div style="background:#00FF00; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                  <div style="background:#006400;overflow: auto;color:white;">
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
                                if($overall_score < 17 && $overall_score > 9 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#008dcf; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 10 && $overall_score > 5 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#59b4e0; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 5 && $overall_score > 2 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                <div style="background:#99d1ec; overflow: auto;">
                                  <?php echo $risk_description['risk_description'] ;?><br/>
                                  <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                  <hr style="solid"/>
                                </div>
                                  <?php
                                }
                                if($overall_score < 3 && $overall_score > 0 && $current_overall['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <div style="background:#d4ecf8;overflow: auto;color:white;">
                                    <?php echo $risk_description['risk_description'] ;?><br/>
                                    <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></div><br/>
                                    <hr style="solid"/>
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
                    <td>
                      <?php
                          $select_sub_objective = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departmental_sub_objectives
                                                                  WHERE department_sub_objective_id = '".$row['DSO_DESC']."'"));
                      echo $select_sub_objective['department_sub_objective_description'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_TYPE'] ;?>
                    </td>
                    <?php
                      //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                        $int = $row['CURRENT_ESTIMATE'];
                        if($int < 20 && $int > 0)
                        {
                          ?>
                          <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 40 && $int > 19)
                        {
                          ?>
                          <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>

                          <?php
                        }
                        if($int < 60 && $int > 39)
                        {
                          ?>
                          <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                            <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 80 && $int > 59)
                        {
                          ?>
                          <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                            <hr style="solid"/>
                          </td>
                          <?php
                        }
                        if($int < 101 && $int > 79)
                        {
                          ?>
                          <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                          <?php echo $row['ACT_DESC'];?>

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
                    <td>
                    <?php echo $row['DEP_KPI'] ;?>
                    </td>
                    <td>
                    <?php echo $row['ACT_UPDATE'] ;?>

                    </td>


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
    else
    {
      exit(mysqli_error($dbc));
    }
    ?>

    <?php
    exit();
  }


}
else
{
  exit("NO data");
  ?>

 <?php
}

 ?>
