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

  $sql = mysqli_query($dbc,
                          "SELECT  b.strategic_objective_description AS SOB_DESC,a.departmental_objective_id AS DOB_DESC,
                                    a.departmental_sub_objective_id AS DSO_DESC, a.activity_id AS ACT_ID,
                                    a.activity_description AS ACT_DESC,
                                    a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                    e.risk_description AS RISK_DESC, e.reference_no AS RISK_REF, e.current_overall_score AS SCORE,
                                    f.performance_update_description AS ACT_UPDATE,
                                    f.estimated_current_performance AS CURRENT_ESTIMATE,
                                    a.department_id AS DEP_ID FROM  strategic_objectives b
                                    JOIN perfomance_management a ON a.strategic_objective_id = b.strategic_objective_id
                                    JOIN activity_related_risks d ON d.activity_id =a.activity_id
                                    JOIN update_risk_status e ON e.reference_no =d.risk_reference
                                    JOIN performance_update f ON a.activity_id=f.activity_id
                                    JOIN departments g ON a.department_id = g.department_id
                                    WHERE

                                    g.directorate_id = '".$select_directorate."'
                                    AND
                                    e.changed = 'no'
                                    AND e.period_from = '".$year_id."'
                                    AND e.quarter = '".$quarter_id."'
                                    AND e.current_overall_score >19
                                    AND f.changed = 'no'
                                    AND f.year_id = '".$year_id."'
                                    AND f.quarter_id = '".$quarter_id."'
                                    AND d.changed='no'
                                    AND d.year_id = '".$year_id."'
                                    AND d.quarter_id = '".$quarter_id."'
                                    AND f.directorate_risk = 'yes'
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
       <h3 class="card-title">Activities With Cumulative Risks:<br/>
         <?php echo $select_directorate;?>
       </h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="detailed_activities_with_cumulative_risks_table" style="overflow:hidden" >
            <thead>
              <tr>
                <td>NO</td>
                <td>Strategic Objective</td>
                <td>Strategic Outcome</td>
                <td>Strategic KPI</td>
                <td>Departmental Objective</td>
                <td>Related Risk</td>
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
                <tr id="row-<?php echo $row['ACT_ID'];?>">
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
                                                                            AND period_from='".$year_id."' AND quarter='".$quarter_id."' && changed='no' && current_overall_score > 19"));
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
