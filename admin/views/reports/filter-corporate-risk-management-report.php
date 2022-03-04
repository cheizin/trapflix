<?php
session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }
  //for superuser
$select_period = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$select_quarter = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$year_id= mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$select_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['directorates']));


if($_POST['directorates'] == 'all')
{
  ?>

       <h1 style="text-align:center;" class="d-none corporate-risk-management-report-header">
        Corporate Performance Risk Management Report <br/>[For <?php echo $select_quarter .','. $select_period;?>]
      </h1>

   <form action="views/reports/pdf/pdf-corporate-risk-management-report.php" method="post" target="_blank">
     <input type="hidden" name="select_period" value="<?php echo $select_period;?>">
     <input type="hidden" name="select_quarter" value="<?php echo $select_quarter;?>">
     <input type="hidden" name="directorates" value="<?php echo $select_directorate ;?>">
     <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
   </form>
   <!-- start corporate activities with corporate risks -->
   <?php
     $sql = mysqli_query($dbc,
                             "SELECT   b.strategic_objective_description AS SOB_DESC,a.departmental_objective_id AS DOB_DESC,
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
                                       AND e.current_overall_score > 19
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
         <h3 class="card-title">Detailed Activities With Corporate Risks:<br/><b>
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
                  <td>Department</td>
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
                            $sql_related_risks = mysqli_query($dbc,"SELECT a.risk_description AS risk_description,
                                                                		    a.current_overall_score AS current_overall_score,
                                                                        a.risk_opportunity AS risk_opportunity,
                                                                        a.reference_no AS reference_no
                                                                		   FROM update_risk_status a
                                                                        JOIN activity_related_risks b ON a.reference_no=b.risk_reference
                                                                        WHERE
                                                                        b.activity_id='".$row['ACT_ID']."'
                                                                        && a.changed='no'
                                                                        && b.changed='no'
                                                                        && a.period_from='".$select_period."'
                                                                        && a.quarter='".$select_quarter."'
                                                                        && b.year_id='".$select_period."'
                                                                        && b.quarter_id='".$select_quarter."'
                                                                        && a.current_overall_score > 19 ") or die(mysqli_error($dbc));
                            if(mysqli_num_rows($sql_related_risks) > 0){
                              while($related_risks = mysqli_fetch_array($sql_related_risks))
                              {
                                $overall_score = $related_risks['current_overall_score'];
                                if($overall_score > 19 && $related_risks['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                  <table>
                                    <tr style="border-style:hidden;">
                                      <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;border-style:hidden;">
                                        <p class="activity-font"><?php echo $related_risks['risk_description'] ;?></p><br/>
                                        <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                      </td>
                                    </tr>
                              </table>
                                  <?php
                                }
                                if($overall_score < 19 && $related_risks['risk_opportunity'] == 'risk')
                                {
                                  ?>
                                  <table>
                                    <tr style="border-style:hidden;">
                                      <td class="risk-div-background" style="overflow: wrap; color:white;border-style:hidden;">
                                        <p class="activity-font"><?php echo $related_risks['risk_description'] ;?></p><br/>
                                        <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                      </td>
                                    </tr>
                              </table>
                                  <?php
                                }
                                //for opportunities
                                if($overall_score > 19 && $related_risks['risk_opportunity'] == 'opportunity')
                                {
                                  ?>
                                  <table>
                                    <tr>
                                      <td class="risk-div-background activity-font" style="background-color:#0272a6; overflow: wrap; color:white;border-style:hidden;">
                                        <p class="activity-font"><?php echo $related_risks['risk_description'] ;?></p><br/>
                                        <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                      </td>
                                    </tr>
                                  </table>
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
                          <td style="background-color:#FF0000;color:white;">
                            <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                                <button type="submit" name="submit" class="btn btn-link activity-font" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                                </button>
                            </form>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          </td>

                          <?php
                        }
                        if($int < 40 && $int > 19)
                        {
                          ?>
                          <td style="background-color:#FFC200;color:black;">
                            <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                                <button type="submit" name="submit" class="btn btn-link activity-font" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                                </button>
                            </form>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          </td>

                          <?php
                        }
                        if($int < 60 && $int > 39)
                        {
                          ?>
                          <td style="background-color:#FFFF00;color:black;">
                            <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                                <button type="submit" name="submit" class="btn btn-link activity-font" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                                </button>
                            </form>
                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          </td>
                          <?php
                        }
                        if($int < 80 && $int > 59)
                        {
                          ?>
                          <td style="background-color:#00FF00; color:black;">
                            <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                                <button type="submit" name="submit" class="btn btn-link activity-font" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                                </button>
                            </form>

                            <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                          </td>
                          <?php
                        }
                        if($int < 101 && $int > 79)
                        {
                          ?>
                          <td style="background-color:#006400; color:white;">
                            <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                                <button type="submit" name="submit" class="btn btn-link activity-font" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                              <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
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
                     <td><?php echo $row['DEP_ID'];?></td>
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

   <!-- end of corporate activities with corporate risks -->


  <div class="card">

  <div class="card-body table-responsive no-padding">
    <div class="col-md-8"  style="overflow:wrap;width:500px; float:left;">
      <h3 class="card-title">Corporate Risks heatmap</h3>
      <div class="table-responsive">
      <table class="table table-bordered" id="corporate-risk-analysis-heatmap">

        <?php
           $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code='".$_SESSION['department_code']."' && risk_opportunity='risk' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC LIMIT 10");
           $sql_query_risk_position = mysqli_fetch_array($sql_query);
         ?>
        <tbody>
          <tr>
            <td rowspan="5" class="impact_rotate">Impact</td>
            <td>Catastrophic <br/><small class="text-primary">5</small></td>
            <td class="medium" style="background-color: #FFFF00;"  title="OVERALL SCORE: 5">
              <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                            WHERE  current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                    }
                  }

               ?>
            </td>
            <td class="very_high" style="background-color: #FF0000; width:200px;" title="OVERALL SCORE: 20">
              <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                            WHERE current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                    }
                  }

               ?>
            </td>
            <td class="very_high" style="background-color: #FF0000;width:200px;" title="OVERALL SCORE: 25">
              <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                            WHERE current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                                WHERE current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                                && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                                WHERE current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                                && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                                WHERE current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                                && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                                WHERE current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                                && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                                WHERE current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                                && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                            WHERE current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
  </div>

  <div class="col-md-3 heatmap-ratings-table" style="overflow:wrap;width:500px; float:right;">
      <h3 class="card-title">Risk Description</h3>
  <?php
    $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE risk_opportunity='risk' && changed='no'
      && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved'
      && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
    $number = 1;
    if($total_rows = mysqli_num_rows($sql_query) > 0)
    {?>
      <table class="table table-bordered table-striped table-hover" id="corporate-risks-heatmap-table">
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
        <td  style="font-size:12px;" class="text-danger">Sorry, no records have been found for
          the selected quarter (<span class="text-info"><?php echo $select_quarter?></span>)
          and period (<span class="text-info"><?php echo $select_period?></span>)
          at the (<span class="text-info">Corporate level</span>)

        </td>

      </tr>

    </table>

    <?php
  }
         ?>
  </div>
  </div>
  <!-- end of div heatmap -->
  <!-- start insert a page break -->
  <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
     <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detailed Status of Corporate Risks <br/> Analysis of CMA Top Risks</h3>
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
            $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                           changed='no' &&
                          period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved'
                          && risk_opportunity='risk' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");

            /*
          }
          */

          $number = 1;
          $rank = 1;
          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" id="quarterly-updates-reports-all-table" width="100%" style="overflow:wrap;">
            <thead>
              <tr>
                <td class="numbering"><p class="paragraph-font">#</p></td>
                <td><p class="paragraph-font">Description</p></td>
                <td><p class="paragraph-font">Risk Drivers</p></td>
                <td><p class="paragraph-font">Risk Management Strategy Undertaken</p></td>
                <td><p class="paragraph-font">Effect of Risk to Authority</p></td>
                <td><p class="paragraph-font">Further action to be undertaken</p></td>
                <td><p class="paragraph-font">Person Responsible</p></td>
              </tr>
            </thead>
            <?php
            while($row = mysqli_fetch_array($sql_query))
            {
                //fetch department name from the risk management table
                $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no' && status='approved'"));
              ?>
            <tr style="cursor: pointer;">
              <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
              <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
              <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
              <td class="numbering"><p class="paragraph-font"><?php echo $rank++ ;?></p></td>
               <td><p class="paragraph-font"><?php echo $row['risk_description'];?>
                    <br/>
                    <?php echo $row['current_overall_score'];?>
               </p>

               </td>
              <td >
                <?php
                  $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                        period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
                  if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                    {
                            while($risk_driver_row = mysqli_fetch_array($driver_query))
                            {
                              ?>
                              <p class="paragraph-font">- <?php echo $risk_driver_row['risk_drivers'];?></p>

                              <?php
                            }
                      }

                ?>
              </td>
              <td >
                <?php
                $sql_risk_drivers = "SELECT DISTINCT risk_management_strategy_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                      period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
                if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                  {
                          while($risk_driver_row = mysqli_fetch_array($driver_query))
                          {
                            ?>
                            <p class="paragraph-font">- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_management_strategy_undertaken']));?></p>

                            <?php
                          }
                    }


                 ?>


              </td>
              <td >
                <?php
                $sql_risk_drivers = "SELECT DISTINCT effects_of_risk_to_authority FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                      period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
                if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                  {
                          while($risk_driver_row = mysqli_fetch_array($driver_query))
                          {
                            ?>
                            <p class="paragraph-font">- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['effects_of_risk_to_authority']));?></p>

                            <?php
                          }
                    }


                 ?>

              </td>
              <td >
                <?php
                $sql_risk_drivers = "SELECT DISTINCT action_to_be_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                      period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
                if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                  {
                          while($risk_driver_row = mysqli_fetch_array($driver_query))
                          {
                            ?>
                            <p class="paragraph-font">- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['action_to_be_undertaken']));?></p>

                            <?php
                          }
                    }


                 ?>
              </td>
              <td ><p class="paragraph-font"><?php echo $fetch['person_responsible'];?></p></td>
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
                 at the (<span class="text-info">Corporate level </span>)

               </td>

             </tr>

           </table>

           <?php
         }

          ?>
        </div>
      </div>
        <!-- /.card-body -->
  <!-- start insert a page break -->
    <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
  <!-- start of heatmap opportunity -->
  <div class="card">
    <h3 class="card-title">Corporate Opportunities Heatmap</h3>
    <div class="card-body table-responsive no-padding">
   <div class="col-md-8 heatmap-opportunity-chart" style="overflow:wrap;width:500px; float:left;">
       <div class="table-responsive">
       <table class="table table-bordered" style="page-break-inside: avoid;">

         <?php
            $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."'  && current_overall_score >='20' ORDER BY current_overall_score DESC LIMIT 20");
            $sql_query_risk_position = mysqli_fetch_array($sql_query);
          ?>
         <tbody>
           <tr>
             <td rowspan="5" class="impact_rotate">Impact</td>
             <td>Transformational <br/><small class="text-primary">5</small></td>
             <td class="medium" style="background-color: #59b4e0;"  title="OVERALL SCORE: 5">
               <?php
                  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                             WHERE current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                 WHERE current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                                 && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                 WHERE current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                                 && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                 WHERE current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                                 && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                 WHERE current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                                 && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                 WHERE current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                                 && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                             WHERE current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                             && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
   </div>

   <div class="col-md-4 heatmap-ratings-table"  style="overflow:wrap;width:500px; float:right;">
   <?php
     $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE risk_opportunity='opportunity' && changed='no'
       && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved'
       && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
           at the (<span class="text-info">Corporate Level</span>)

         </td>

       </tr>

     </table>

     <?php
   }
          ?>
   </div>
  </div>
  </div>
  <!-- end of heatmap opportunity -->
  <!-- start insert a page break -->
    <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->
  <!-- start detailed status of corporate opportunites -->
  <div class="card">
     <div class="card-header">
       <h3 class="card-title">Detailed Status of Corporate Opportunities <br/> Analysis of CMA Top Opportunities</h3>
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
         $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                        changed='no' &&
                       period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved'
                       && risk_opportunity='opportunity' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");

         /*
       }
       */

       $number = 1;
       $rank = 1;
       if($total_rows = mysqli_num_rows($sql_query) > 0)
       {?>
       <table class="table table-striped table-bordered table-hover" id="quarterly-updates-opportunity-reports-all-table" width="100%" style="overflow:hidden;">
         <thead>
           <tr>
             <td>#</td>
             <td>Description</td>
             <td>Risk Drivers</td>
             <td>Risk Management Strategy Undertaken</td>
             <td>Effect of Risk to Authority</td>
             <td>Further action to be undertaken</td>
             <td>Person Responsible</td>
           </tr>
         </thead>
         <?php
         while($row = mysqli_fetch_array($sql_query))
         {
             //fetch department name from the risk management table
             $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no' && status='approved'"));
           ?>
         <tr style="cursor: pointer;">
           <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
           <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
           <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
             <td><?php echo $rank++ ;?></td>
           <td><?php echo $row['risk_description'];?>
                <br/>
                <?php echo $row['current_overall_score'];?>
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
             $sql_risk_drivers = "SELECT DISTINCT risk_management_strategy_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
             $sql_risk_drivers = "SELECT DISTINCT effects_of_risk_to_authority FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
             $sql_risk_drivers = "SELECT DISTINCT action_to_be_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
              at the (<span class="text-info">Corporate level </span>)

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

  <!-- start emerging trends -->


  <!-- end emerging trends -->
  <!-- start insert a page break -->
  <!--  <div style="page-break-after:always;"></div>  -->
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
          $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_worked_well WHERE changed='no' &&
                                          period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
          $number = 1;
          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
            <thead>
              <tr>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Worked Well</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Department</p></td>
              </tr>
            </thead>

            <?php
            while($row = mysqli_fetch_array($sql_query))
            {?>
            <tr style="cursor: pointer;">
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $row['dep_code'];?></p></td>
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
              <h4 class="text-warning">No Strategies That Worked Well for the selected quarter</h4>
            <?php
          }
          ?>
        </div>
        <!-- /.card-body -->

        <!-- start strategies that did not work -->
        <div class="card-body table-responsive no-padding">
          <?php
          $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE changed='no' &&
                                          period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
          $number = 1;
          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
            <thead>
              <tr>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Did Not Work</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Department</p></td>
              </tr>
            </thead>

            <?php
            while($row = mysqli_fetch_array($sql_query))
            {?>
            <tr style="cursor: pointer;">
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $row['dep_code'];?></p></td>
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
              <h4 class="text-warning">There are no Strategies That Did Not Work for the selected quarter</h4>
            <?php
          }
          ?>
        </div>
        <!-- /.card-body -->
        <!-- end strategies that did not work -->

        <!-- start near misses -->
        <div class="card-body table-responsive no-padding">
          <?php
          $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE changed='no' &&
                                          period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
          $number = 1;
          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
          <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
            <thead>
              <tr>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Near Misses</p></td>
                <td><p class="paragraph-font-lessons" style="font-size:12px;">Department</p></td>
              </tr>
            </thead>

            <?php
            while($row = mysqli_fetch_array($sql_query))
            {?>
            <tr style="cursor: pointer;">
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
              <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $row['dep_code'];?></p></td>
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
              <h4 class="text-warning">No Near Misses for the selected quarter</h4>
            <?php
          }
          ?>
        </div>
        <!-- /.card-body -->
        <!-- end near misses -->

      </div>
      <!-- /.card -->
    </div>

  </div>
  <!-- end lessons learnt -->
  <!-- end lessons learnt -->
  <!-- start insert a page break -->
    <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->

  <?php

  exit();
}
else
{
?>
  <h1 style="text-align:center;" class="d-none corporate-risk-management-report-header">
   Corporate Performance Risk Management Report <br/>[For <?php echo $select_quarter .','. $select_period;?>]<br/>
   - <br/>
   <?php echo $select_directorate;?>
 </h1>
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<div class="row">
  <div class="d-none">
   <?php include("static-content-report.php");?>
 </div>
</div>

<form action="views/reports/pdf/pdf-corporate-risk-management-report.php" method="post" target="_blank">
  <input type="hidden" name="select_period" value="<?php echo $select_period;?>">
  <input type="hidden" name="select_quarter" value="<?php echo $select_quarter;?>">
  <input type="hidden" name="directorates" value="<?php echo $select_directorate ;?>">
  <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
</form>
<!-- start corporate activities with corporate risks -->
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
                                  JOIN departments g ON a.department_id = g.department_id
                                  WHERE
                                   e.changed = 'no'
                                  AND d.year_id = '".$year_id."'
                                  AND d.quarter_id = '".$quarter_id."'
                                  AND e.period_from = '".$year_id."'
                                  AND g.directorate_id='".$select_directorate."'
                                  AND e.quarter = '".$quarter_id."'
                                  AND e.current_overall_score > 19
                                  AND f.changed = 'no'
                                  AND f.year_id = '".$year_id."'
                                  AND f.quarter_id = '".$quarter_id."'
                                  and d.changed = 'no'
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
    <h3 class="card-title">Detailed Activities With Corporate Risks:<br/><b>
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
                 $sql_related_risks = mysqli_query($dbc,"SELECT a.risk_description AS risk_description,
                                                             a.current_overall_score AS current_overall_score,
                                                             a.risk_opportunity AS risk_opportunity,
                                                             a.reference_no AS reference_no
                                                            FROM update_risk_status a
                                                             JOIN activity_related_risks b ON a.reference_no=b.risk_reference
                                                             WHERE
                                                             b.activity_id='".$row['ACT_ID']."'
                                                             && a.changed='no'
                                                             && b.changed='no'
                                                             && a.period_from='".$select_period."'
                                                             && a.quarter='".$select_quarter."'
                                                             && b.year_id='".$select_period."'
                                                             && b.quarter_id='".$select_quarter."'
                                                             && a.current_overall_score > 19 ") or die(mysqli_error($dbc));
                       if(mysqli_num_rows($sql_related_risks) > 0){
                         while($related_risks = mysqli_fetch_array($sql_related_risks))
                         {
                           $overall_score = $related_risks['current_overall_score'];
                           if($overall_score < 26 && $overall_score > 19 && $related_risks['risk_opportunity'] == 'risk')
                           {
                             ?>
                             <table>
                               <tr style="border-style:hidden;">
                                 <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;border-style:hidden;">
                                   <p class="activity-font"><?php echo $related_risks['risk_description'] ;?></p><br/>
                                   <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                 </td>
                               </tr>
                         </table>
                             <?php
                           }
                           //for opportunities
                           if($overall_score < 26 && $overall_score > 19 && $related_risks['risk_opportunity'] == 'opportunity')
                           {
                             ?>
                             <table>
                               <tr>
                                 <td class="risk-div-background activity-font" style="background-color:#0272a6; overflow: wrap; color:white;border-style:hidden;">
                                   <p class="activity-font"><?php echo $related_risks['risk_description'] ;?></p><br/>
                                   <p style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $overall_score; ?></p>
                                 </td>
                               </tr>
                             </table>
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
                     <td style="background-color:#FF0000;color:white;">
                       <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                           <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                           <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                           <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                           <button type="submit" name="submit" class="btn btn-link activity-font" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                           <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                           </button>
                       </form>
                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 40 && $int > 19)
                   {
                     ?>
                     <td style="background-color:#FFC200;color:black;">
                       <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                           <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                           <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                           <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                           <button type="submit" name="submit" class="btn btn-link activity-font" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                           <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                           </button>
                       </form>
                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 60 && $int > 39)
                   {
                     ?>
                     <td style="background-color:#FFFF00;color:black;">
                       <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                           <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                           <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                           <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                           <button type="submit" name="submit" class="btn btn-link activity-font" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                           <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                           </button>
                       </form>
                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 80 && $int > 59)
                   {
                     ?>
                     <td style="background-color:#00FF00; color:black;">
                       <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                           <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                           <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                           <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                           <button type="submit" name="submit" class="btn btn-link activity-font" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                           <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
                           </button>
                       </form>

                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 101 && $int > 79)
                   {
                     ?>
                     <td style="background-color:#006400; color:white;">
                       <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                           <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                           <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                           <input type="hidden" value="<?php echo $selected_department;?>" name="selected_department">
                           <button type="submit" name="submit" class="btn btn-link activity-font" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                         <span class="activity-font"><?php echo $row['ACT_DESC'];?></span>
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

  <!-- end of corporate activities with corporate risks -->


 <div class="card">

 <div class="card-body table-responsive no-padding">
   <div class="col-md-8"  style="overflow:wrap;width:500px; float:left;">
     <h3 class="card-title">Corporate Risks heatmap</h3>
     <div class="table-responsive">
     <table class="table table-bordered" id="corporate-risk-analysis-heatmap">

       <?php
          $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && risk_opportunity='risk' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC LIMIT 10");
          $sql_query_risk_position = mysqli_fetch_array($sql_query);
        ?>
       <tbody>
         <tr>
           <td rowspan="5" class="impact_rotate">Impact</td>
           <td>Catastrophic <br/><small class="text-primary">5</small></td>
           <td class="medium" style="background-color: #FFFF00;"  title="OVERALL SCORE: 5">
             <?php
                $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                   }
                 }

              ?>
           </td>
           <td class="very_high" style="background-color: #FF0000; width:200px;" title="OVERALL SCORE: 20">
             <?php
                $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['risk_description']."'>" .$risk_position['reference_no'] ."</span><br/>";
                   }
                 }

              ?>
           </td>
           <td class="very_high" style="background-color: #FF0000;width:200px;" title="OVERALL SCORE: 25">
             <?php
                $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                               WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                               && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                               WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                               && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                               WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                               && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                               WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                               && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                               WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                               && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
                                           WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                           && risk_opportunity='risk'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
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
 </div>

 <div class="col-md-3 heatmap-ratings-table" style="overflow:wrap;width:500px; float:right;">
     <h3 class="card-title">Risk Description</h3>
 <?php
   $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN
     (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && risk_opportunity='risk'
     && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20'
     && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
   $number = 1;
   if($total_rows = mysqli_num_rows($sql_query) > 0)
   {?>
     <table class="table table-bordered table-striped table-hover" id="corporate-risks-heatmap-table">
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
         the selected quarter (<span class="text-info"><?php echo $select_quarter?></span>)
         and period (<span class="text-info"><?php echo $select_period?></span>)
         at the (<span class="text-info">Corporate level</span>)

       </td>

     </tr>

   </table>

   <?php
 }
        ?>
 </div>
 </div>
 <!-- end of div heatmap -->
 <!-- start insert a page break -->
 <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->
    <div class="card">
       <div class="card-header">
         <h3 class="card-title">Detailed Status of Corporate Risks <br/> Analysis of CMA Top Risks</h3>
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
           $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') &&
                          changed='no' &&
                         period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved'
                         && risk_opportunity='risk' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");

           /*
         }
         */

         $number = 1;
         $rank = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="quarterly-updates-reports-all-table" width="100%" style="overflow:wrap;">
           <thead>
             <tr>
               <td class="numbering"><p class="paragraph-font">#</p></td>
               <td><p class="paragraph-font">Description</p></td>
               <td><p class="paragraph-font">Risk Drivers</p></td>
               <td><p class="paragraph-font">Risk Management Strategy Undertaken</p></td>
               <td><p class="paragraph-font">Effect of Risk to Authority</p></td>
               <td><p class="paragraph-font">Further action to be undertaken</p></td>
               <td><p class="paragraph-font">Person Responsible</p></td>
             </tr>
           </thead>
           <?php
           while($row = mysqli_fetch_array($sql_query))
           {
               //fetch department name from the risk management table
               $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE department_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && risk_reference='".$row['reference_no']."' &&  changed='no' && status='approved'"));
             ?>
           <tr style="cursor: pointer;">
             <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
             <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
             <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
             <td class="numbering"><p class="paragraph-font"><?php echo $rank++ ;?></p></td>
              <td><p class="paragraph-font"><?php echo $row['risk_description'];?>
                    <br/>
                    <?php echo $row['current_overall_score'];?>
              </p>

              </td>
             <td >
               <?php
                 $sql_risk_drivers = "SELECT *  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                       period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
                 if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                   {
                           while($risk_driver_row = mysqli_fetch_array($driver_query))
                           {
                             ?>
                             <p class="paragraph-font">- <?php echo $risk_driver_row['risk_drivers'];?></p>

                             <?php
                           }
                     }

               ?>
             </td>
             <td >
               <?php
               $sql_risk_drivers = "SELECT DISTINCT risk_management_strategy_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                     period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
               if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                 {
                         while($risk_driver_row = mysqli_fetch_array($driver_query))
                         {
                           ?>
                           <p class="paragraph-font">- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['risk_management_strategy_undertaken']));?></p>

                           <?php
                         }
                   }


                ?>


             </td>
             <td >
               <?php
               $sql_risk_drivers = "SELECT DISTINCT effects_of_risk_to_authority FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                     period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
               if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                 {
                         while($risk_driver_row = mysqli_fetch_array($driver_query))
                         {
                           ?>
                           <p class="paragraph-font">- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['effects_of_risk_to_authority']));?></p>

                           <?php
                         }
                   }


                ?>

             </td>
             <td >
               <?php
               $sql_risk_drivers = "SELECT DISTINCT action_to_be_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
                                     period_from='".$select_period."' && quarter='".$select_quarter."' && changed='no'";
               if($driver_query = mysqli_query($dbc,$sql_risk_drivers))
                 {
                         while($risk_driver_row = mysqli_fetch_array($driver_query))
                         {
                           ?>
                           <p class="paragraph-font">- <?php echo htmlspecialchars_decode(stripslashes($risk_driver_row['action_to_be_undertaken']));?></p>

                           <?php
                         }
                   }


                ?>
             </td>
             <td ><p class="paragraph-font"><?php echo $fetch['person_responsible'];?></p></td>
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
                at the (<span class="text-info">Corporate level </span>)

              </td>

            </tr>

          </table>

          <?php
        }

         ?>
       </div>
     </div>
       <!-- /.card-body -->
 <!-- start insert a page break -->
   <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->
 <!-- start of heatmap opportunity -->
 <div class="card">
   <h3 class="card-title">Corporate Opportunities Heatmap</h3>
   <div class="card-body table-responsive no-padding">
  <div class="col-md-8 heatmap-opportunity-chart" style="overflow:wrap;width:500px; float:left;">
      <div class="table-responsive">
      <table class="table table-bordered" style="page-break-inside: avoid;">

        <?php
           $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."'  && current_overall_score >='20' ORDER BY current_overall_score DESC LIMIT 20");
           $sql_query_risk_position = mysqli_fetch_array($sql_query);
         ?>
        <tbody>
          <tr>
            <td rowspan="5" class="impact_rotate">Impact</td>
            <td>Transformational <br/><small class="text-primary">5</small></td>
            <td class="medium" style="background-color: #59b4e0;"  title="OVERALL SCORE: 5">
              <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                                && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                                && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                                && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                                && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                                WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                                && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
                                            WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                            && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
  </div>

  <div class="col-md-4 heatmap-ratings-table"  style="overflow:wrap;width:500px; float:right;">
  <?php
    $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code
                                    IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."')
                                    && risk_opportunity='opportunity' && changed='no'
                                    && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20'
                                    && status='approved' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
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
          at the (<span class="text-info">Corporate Level</span>)

        </td>

      </tr>

    </table>

    <?php
  }
         ?>
  </div>
 </div>
 </div>
 <!-- end of heatmap opportunity -->
 <!-- start insert a page break -->
   <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->
 <!-- start detailed status of corporate opportunites -->
 <div class="card">
    <div class="card-header">
      <h3 class="card-title">Detailed Status of Corporate Opportunities <br/> Analysis of CMA Top Opportunities</h3>
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
        $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') &&
                       changed='no' &&
                      period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && status='approved'
                      && risk_opportunity='opportunity' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");

        /*
      }
      */

      $number = 1;
      $rank = 1;
      if($total_rows = mysqli_num_rows($sql_query) > 0)
      {?>
      <table class="table table-striped table-bordered table-hover" id="quarterly-updates-opportunity-reports-all-table" width="100%" style="overflow:hidden;">
        <thead>
          <tr>
            <td>#</td>
            <td>Description</td>
            <td>Risk Drivers</td>
            <td>Risk Management Strategy Undertaken</td>
            <td>Effect of Risk to Authority</td>
            <td>Further action to be undertaken</td>
            <td>Person Responsible</td>
          </tr>
        </thead>
        <?php
        while($row = mysqli_fetch_array($sql_query))
        {
            //fetch department name from the risk management table
            $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE department_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && risk_reference='".$row['reference_no']."' &&  changed='no' && status='approved'"));
          ?>
        <tr style="cursor: pointer;">
          <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
          <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
          <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
            <td><?php echo $rank++ ;?></td>
          <td><?php echo $row['risk_description'];?>
            <br/>
              <?php echo $row['current_overall_score'];?>
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
            $sql_risk_drivers = "SELECT DISTINCT risk_management_strategy_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
            $sql_risk_drivers = "SELECT DISTINCT effects_of_risk_to_authority FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
            $sql_risk_drivers = "SELECT DISTINCT action_to_be_undertaken FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
             at the (<span class="text-info">Corporate level </span>)

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

 <!-- start emerging trends -->


 <!-- end emerging trends -->
 <!-- start insert a page break -->
 <!--  <div style="page-break-after:always;"></div>  -->
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
         $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_worked_well WHERE dep_code IN
                                          (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && changed='no' &&
                                         period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
         $number = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
           <thead>
             <tr>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Worked Well</p></td>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">Department</p></td>
             </tr>
           </thead>

           <?php
           while($row = mysqli_fetch_array($sql_query))
           {?>
           <tr style="cursor: pointer;">
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $row['dep_code'];?></p></td>
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
             <h4 class="text-warning">No Strategies That Worked Well for the selected quarter</h4>
           <?php
         }
         ?>
       </div>
       <!-- /.card-body -->

       <!-- start strategies that did not work -->
       <div class="card-body table-responsive no-padding">
         <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE dep_code IN
                                          (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && changed='no' &&
                                         period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
         $number = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
           <thead>
             <tr>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Did Not Work</p></td>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">Department</p></td>
             </tr>
           </thead>

           <?php
           while($row = mysqli_fetch_array($sql_query))
           {?>
           <tr style="cursor: pointer;">
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $row['dep_code'];?></p></td>
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
             <h4 class="text-warning">There are no Strategies That Did Not Work for the selected quarter</h4>
           <?php
         }
         ?>
       </div>
       <!-- /.card-body -->
       <!-- end strategies that did not work -->

       <!-- start near misses -->
       <div class="card-body table-responsive no-padding">
         <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE dep_code IN
                                          (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && changed='no' &&
                                         period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
         $number = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
           <thead>
             <tr>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">NO</p></td>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">Near Misses</p></td>
               <td><p class="paragraph-font-lessons" style="font-size:12px;">Department</p></td>
             </tr>
           </thead>

           <?php
           while($row = mysqli_fetch_array($sql_query))
           {?>
           <tr style="cursor: pointer;">
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
             <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $row['dep_code'];?></p></td>
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
             <h4 class="text-warning">No Near Misses for the selected quarter</h4>
           <?php
         }
         ?>
       </div>
       <!-- /.card-body -->
       <!-- end near misses -->

     </div>
     <!-- /.card -->
   </div>

 </div>
 <!-- end lessons learnt -->
 <!-- start insert a page break -->
   <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->

 <script src="functions/quarterly-updates-risks.js"></script>
 <script src="functions/datatable-perfomance-department.js"></script>
 <script src="functions/lessons-learnt-table.js"></script>
 <script src="functions/emerging-trends-table.js"></script>

<?php

} //end of else

 ?>
 <!-- start insert a page break -->

<?php
}
 ?>
