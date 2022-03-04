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
  <h1 class="d-none" style="text-align:center;" class="corporate-risk-management-report-header">
   Consolidated Risk Management Report <br/>[For <?php echo $select_quarter .','. $select_period;?>]<br/>
 </h1>
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<form action="views/reports/pdf/pdf-directorate-risk-management-report.php" method="post" target="_blank">
  <input type="hidden" name="select_period" value="<?php echo $select_period;?>">
  <input type="hidden" name="select_quarter" value="<?php echo $select_quarter;?>">
  <input type="hidden" name="directorates" value="<?php echo $select_directorate ;?>">
  <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
</form>
<!-- start corporate activities with corporate risks -->
<?php
$sql = mysqli_query($dbc,
                        "SELECT * FROM directors_cumulative_table WHERE
                                    year_id='".$select_period."' &&
                                    quarter_id='".$select_quarter."'
                                    && overall_score > 19
                          ORDER BY overall_score DESC
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
    <h3 class="card-title">Cumulative Risks with Cumulative Activities:<br/><b>
         </b>
    </h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body table-responsive no-padding">
       <table class="table table-striped table-bordered table-hover" id="detailed_corporate_activities_related_risks_table" width="100%" style="overflow:hidden;" autosize="1">
         <thead>
           <tr>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="30">NO</td>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="250">Strategic Objective</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Cumulative Risk</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Cumulative Activity</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Expected Cumulative Outcome</td>
           </tr>
         </thead>
         <?php
         $number = 1;
         while($row = mysqli_fetch_array($sql))
         {
           ?>
             <tr>
               <td><p class="activity-font-directorate"><?php echo $number++ ;?></p></td>
               <td>
                  <?php
                        $directors_activity = mysqli_query($dbc,"SELECT * FROM directors_risk_strategic_objective WHERE
                                                              directors_cumulative_id='".$row['directors_cumulative_id']."'
                                                              ");
                        while($directors_activity_row = mysqli_fetch_array($directors_activity))
                        {
                          $so_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_objectives WHERE
                                                                  strategic_objective_id='".$directors_activity_row['strategic_objective_id']."'"));
                          ?>
                          <table>
                            <tr style="border-style:hidden;">
                              <td style="overflow: wrap;border-style:hidden;">
                                <p class="activity-font-directorate">- <?php echo $so_description['strategic_objective_description']; ?></p>
                              </td>

                            </tr>
                          </table>
                          <?php

                        }
                   ?>

               </td>
                 <?php
                 $overall_score = $row['overall_score'];
                 $risk_opportunity = $row['risk_opportunity'];
                 if($overall_score < 26 && $overall_score > 19 && $risk_opportunity == 'risk')
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>

                   <?php
                 }
                 if($overall_score < 26 && $overall_score > 19 && $risk_opportunity == 'opportunity')
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#0272a6; overflow: wrap; color:white;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>

                   <?php
                 }
                 if($overall_score < 17 && $overall_score > 9)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFC200; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 10 && $overall_score > 5)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFFF00; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 5 && $overall_score > 2)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#00FF00; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 3 && $overall_score > 0)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#006400; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 ?>

               <?php
                 //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                   $int = $row['cumulative_activity_score'];
                   if($int < 20 && $int > 0)
                   {
                     ?>
                     <td style="background-color:#FF0000;color:white;">
                       <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 40 && $int > 19)
                   {
                     ?>
                     <td style="background-color:#FFC200;color:black;">
                      <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 60 && $int > 39)
                   {
                     ?>
                     <td style="background-color:#FFFF00;color:black;">
                      <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 80 && $int > 59)
                   {
                     ?>
                     <td style="background-color:#00FF00; color:black;">
                      <?php echo $row['cumulative_activity_description'];?>

                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 101 && $int > 79)
                   {
                     ?>
                     <td style="background-color:#006400; color:white;">
                      <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
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
                   <p class="activity-font-directorate"><?php echo $row['expected_cumulative_outcomes'];?></p>
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
   <div class="col-md-8"  style="overflow:wrap;width:600px; float:left;">
     <h3 class="card-title">Cumulative Risks heatmap</h3>
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
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='5' && likelihood_score='1'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
              <br/>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='5' && likelihood_score='2'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='5' && likelihood_score='3'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="very_high" style="background-color: #FF0000; width:200px;" title="OVERALL SCORE: 20">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='5' && likelihood_score='4'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }
              ?>
           </td>
           <td class="very_high" style="background-color: #FF0000;width:200px;" title="OVERALL SCORE: 25">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='5' && likelihood_score='5'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
         </tr>
         <tr>
           <td>Major <br/><small class="text-primary">4</small></td>
               <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE impact_score='4' && likelihood_score='1'
                                            && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }
                  ?>
               </td>
               <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE impact_score='4' && likelihood_score='2'
                                            && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }

                  ?>
               </td>
               <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12">
                 <?php
                    $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                               WHERE impact_score='4' && likelihood_score='3'
                                                && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                     if(mysqli_num_rows($sql) > 0)
                     {
                       while ($risk_position = mysqli_fetch_array($sql)) {
                         echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                       }
                     }

                  ?>
               </td>
               <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 16">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE impact_score='4' && likelihood_score='4'
                                            && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }

                  ?>
               </td>
               <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 20">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE impact_score='4' && likelihood_score='5'
                                            && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }
                  ?>
               </td>

         </tr>
         <tr>
           <td>Moderate <br/><small class="text-primary">3</small></td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='3' && likelihood_score='1'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='3' && likelihood_score='2'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 9">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='3' && likelihood_score='3'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='3' && likelihood_score='4'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='3' && likelihood_score='5'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
         </tr>
         <tr>
           <td>Minor <br/><small class="text-primary">2</small></td>
           <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='2' && likelihood_score='1'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='2' && likelihood_score='2'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }
              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='2' && likelihood_score='3'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='2' && likelihood_score='4'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td  class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='2' && likelihood_score='5'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
         </tr>
         <tr>
           <td>Insignificant <br/><small class="text-primary">1</small></td>
           <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 1">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='1' && likelihood_score='1'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='1' && likelihood_score='2'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }
              ?>
           </td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='1' && likelihood_score='3'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='1' && likelihood_score='4'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 5">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE impact_score='1' && likelihood_score='5'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
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

 <div class="col-md-4 heatmap-ratings-table table-responsive" style="overflow:wrap;width:400px; float:right;">
 <?php
   $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                            && risk_opportunity='risk' && overall_score > 19
                                           ORDER BY overall_score DESC");
   $number = 1;
   if($total_rows = mysqli_num_rows($sql_query) > 0)
   {?>
     <table class="table table-bordered table-striped table-hover" id="corporate-risks-heatmap-table">
         <thead>
           <tr>
             <td style="font-size:12px;">No</td>
             <td style="font-size:12px;">Cumulative Risk</td>
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
             <td style="font-size:12px;"><?php echo $row['cumulative_risk_description'];?></td>
             <td style="font-size:12px;"><?php echo $row['overall_score'];?></td>
             <td style="font-size:12px;"><?php echo $row['directors_cumulative_id'];?></td>
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
         <h3 class="card-title">Detailed Status of Risks</h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body table-responsive no-padding">
         <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no IN
                                                               (SELECT risk_reference FROM directors_risk_table WHERE
                                                                 year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                                                  )
                                        && risk_opportunity='risk' &&
                                       changed='no' &&
                                       period_from='".$select_period."' && quarter='".$select_quarter."'
                                       ORDER BY current_overall_score DESC ");

         $number = 1;
         $rank = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="quarterly-updates-reports-all-table" width="100%" style="overflow:wrap;">
           <thead>
             <tr>
               <td class="numbering" width="30px"><p class="paragraph-font">#</p></td>
               <td><p class="paragraph-font">Description</p></td>
               <td><p class="paragraph-font">Risk Drivers</td>
               <td><p class="paragraph-font">Risk Management Strategy Undertaken</p></td>
               <td width="200"><p class="paragraph-font">Effect of Risk to Authority</p></td>
               <td><p class="paragraph-font">Further action to be undertaken</p></td>
               <td><p class="paragraph-font">Person Responsible</p></td>
             </tr>
           </thead>
           <?php
           while($row = mysqli_fetch_array($sql_query))
           {
               //fetch department name from the risk management table
               $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no'"));
             ?>
           <tr style="cursor: pointer;">
             <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
             <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
             <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
             <td class="numbering"><p class="paragraph-font"><?php echo $rank++ ;?></p></td>
              <td><p class="paragraph-font"><?php echo $fetch['risk_description'];?>
                <br/>
                Overall Score: <?php echo $row['current_overall_score'];?>
              </p>

              </td>
             <td >
               <?php
                 $sql_risk_drivers = "SELECT DISTINCT risk_drivers FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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


              </td>

            </tr>

          </table>

          <?php
        }

         ?>
       </div>
     </div>
       <!-- /.card-body -->
       <div style="page-break-after:always;"></div>
       <div class="card-body table-responsive no-padding">
         <div class="col-md-8"  style="overflow:wrap;width:600px; float:left;">
           <h3 class="card-title">Cumulative Opportunities heatmap</h3>
           <div class="table-responsive">
           <table class="table table-bordered" id="corporate-risk-analysis-heatmap">

             <?php
                $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN (SELECT department_id FROM departments) && risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC LIMIT 10");
                $sql_query_risk_position = mysqli_fetch_array($sql_query);
              ?>
             <tbody>
               <tr>
                 <td rowspan="5" class="impact_rotate">Impact</td>
                 <td>Transformational <br/><small class="text-primary">5</small></td>
                 <td class="medium" style="background-color: #59b4e0;"  title="OVERALL SCORE: 5">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='5' && likelihood_score='1'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                    <br/>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='5' && likelihood_score='2'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='5' && likelihood_score='3'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="very_high" style="background-color: #0272a6; width:200px;" title="OVERALL SCORE: 20">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='5' && likelihood_score='4'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }
                    ?>
                 </td>
                 <td class="very_high" style="background-color: #0272a6;width:200px;" title="OVERALL SCORE: 25">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='5' && likelihood_score='5'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
               </tr>
               <tr>
                 <td>Major <br/><small class="text-primary">4</small></td>
                     <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                       <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                                  WHERE  impact_score='4' && likelihood_score='1'
                                                   && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                          }
                        }
                        ?>
                     </td>
                     <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                       <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                                  WHERE  impact_score='4' && likelihood_score='2'
                                                    && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                          }
                        }

                        ?>
                     </td>
                     <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                       <?php
                          $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                                     WHERE  impact_score='4' && likelihood_score='3'
                                                       && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                           if(mysqli_num_rows($sql) > 0)
                           {
                             while ($risk_position = mysqli_fetch_array($sql)) {
                               echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                             }
                           }

                        ?>
                     </td>
                     <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 16">
                       <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                                  WHERE  impact_score='4' && likelihood_score='4'
                                                    && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                          }
                        }

                        ?>
                     </td>
                     <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 20">
                       <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                                  WHERE  impact_score='4' && likelihood_score='5'
                                                    && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                          }
                        }
                        ?>
                     </td>

               </tr>
               <tr>
                 <td>Moderate <br/><small class="text-primary">3</small></td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='3' && likelihood_score='1'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='3' && likelihood_score='2'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }


                    ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 9">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='3' && likelihood_score='3'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }


                    ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='3' && likelihood_score='4'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='3' && likelihood_score='5'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }


                    ?>
                 </td>
               </tr>
               <tr>
                 <td>Minor <br/><small class="text-primary">2</small></td>
                 <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='2' && likelihood_score='1'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }


                    ?>
                 </td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='2' && likelihood_score='2'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }
                    ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='2' && likelihood_score='3'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='2' && likelihood_score='4'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td  class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='2' && likelihood_score='5'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
               </tr>
               <tr>
                 <td>Insignificant <br/><small class="text-primary">1</small></td>
                 <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 1">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='1' && likelihood_score='1'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='1' && likelihood_score='2'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }
                    ?>
                 </td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='1' && likelihood_score='3'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='1' && likelihood_score='4'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                      }
                    }

                    ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 5">
                   <?php
                   $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                              WHERE  impact_score='1' && likelihood_score='5'
                                               && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                    if(mysqli_num_rows($sql) > 0)
                    {
                      while ($risk_position = mysqli_fetch_array($sql)) {
                        echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
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
       <div class="col-md-4 heatmap-ratings-table table-responsive" style="overflow:wrap;width:400px; float:right;">

       <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE
                                          year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                          && risk_opportunity='opportunity' && overall_score > 19 ORDER BY overall_score DESC");
         $number = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
           <table class="table table-bordered table-striped table-hover" id="corporate-risks-heatmap-table">
               <thead>
                 <tr>
                   <td style="font-size:12px;">No</td>
                   <td style="font-size:12px;">Cumulative Opportunity</td>
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
                   <td style="font-size:12px;"><?php echo $row['cumulative_risk_description'];?></td>
                   <td style="font-size:12px;"><?php echo $row['overall_score'];?></td>
                   <td style="font-size:12px;"><?php echo $row['directors_cumulative_id'];?></td>
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
       <div style="page-break-after:always;"></div>
       <!-- end insert a page break -->
          <div class="card">
             <div class="card-header">
               <h3 class="card-title">Detailed Status of Opportunities</h3>
             </div>
             <!-- /.card-header -->
             <div class="card-body table-responsive no-padding">
               <?php
               $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no IN
                                                                     (SELECT risk_reference FROM directors_risk_table WHERE
                                                                       year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                                                        )
                                              && risk_opportunity='opportunity' &&
                                             changed='no' &&
                                             period_from='".$select_period."' && quarter='".$select_quarter."'
                                             ORDER BY current_overall_score DESC ");
               $number = 1;
               $rank = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="table table-striped table-bordered table-hover" id="quarterly-updates-reports-all-table" width="100%" style="overflow:hidden;">
                 <thead>
                   <tr>
                     <td class="numbering" width="30px"><p class="paragraph-font">#</p></td>
                     <td><p class="paragraph-font">Description</p></td>
                     <td><p class="paragraph-font">Opportunity Drivers</td>
                     <td><p class="paragraph-font">Opportunity Enhancement Strategy Undertaken</p></td>
                     <td><p class="paragraph-font">Effect of Enhancement to Authority</p></td>
                     <td><p class="paragraph-font">Further action to be undertaken</p></td>
                     <td><p class="paragraph-font">Person Responsible</p></td>
                   </tr>
                 </thead>
                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {
                     //fetch department name from the risk management table
                     $fetch = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no'"));
                   ?>
                 <tr style="cursor: pointer;">
                   <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
                   <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
                   <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
                   <td class="numbering"><p class="paragraph-font"><?php echo $rank++ ;?></p></td>
                    <td><p class="paragraph-font"><?php echo $fetch['risk_description'];?>
                      <br/>
                      Overall Score: <?php echo $row['current_overall_score'];?>
                    </p>

                    </td>
                   <td >
                     <?php
                       $sql_risk_drivers = "SELECT DISTINCT risk_drivers FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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


                    </td>

                  </tr>

                </table>

                <?php
              }

               ?>
             </div>
           </div>
             <!-- /.card-body -->
       <div style="page-break-after:always;"></div>
       <?php
       $sql_query = mysqli_query($dbc,"SELECT * FROM emerging_trends WHERE changed='no'
                                            && period='".$select_period."' && quarter='".$select_quarter."'
                    ");
       $number = 1;
       if($total_rows = mysqli_num_rows($sql_query) > 0)
       {?>
        <h4>Emerging Trends</h4>
       <table class="table table-striped table-bordered table-hover" id="emerging-trends-table">
         <thead>
           <tr>
             <td>NO</td>
             <td>Factor</td>
             <td>External/Internal</td>
             <td>Related Risk Event</td>
             <td>Changes in Risk Profile</td>
             <td>Directorate</td>
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
           <td>
             <?php
             $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
             echo $directorate_row['directorate_id'];
             ?>

           </td>
         </tr>
         <?php
         }
         ?>
         <tfoot>
             <tr>
               <th>NO</th>
               <th>Factor</th>
               <th>External/Internal</th>
               <th>Related Risk Event</th>
               <th>Changes in Risk Profile</th>
               <th>Directorate</th>
             </tr>
         </tfoot>
       </table>
       <?php
       }
    ?>

       <div style="page-break-after:always;"></div>

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
                                                 period='".$select_period."' && quarter='".$select_quarter."'
                                                 && strategies_that_worked_well IS NOT NULL ORDER BY dep_code DESC");
                 $number = 1;
                 if($total_rows = mysqli_num_rows($sql_query) > 0)
                 {?>
                 <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" width="100%" style="overflow:hidden;">
                   <thead>
                     <tr>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">#</p></td>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Worked Well</p></td>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">Directorate</p></td>
                     </tr>
                   </thead>

                   <?php
                   while($row = mysqli_fetch_array($sql_query))
                   {?>
                   <tr style="cursor: pointer;">
                     <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">
                       <?php
                       $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
                       echo $directorate_row['directorate_id'];

                       ?></p>

                     </td>
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

                 $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE changed='no' &&
                                                 period='".$select_period."' && quarter='".$select_quarter."'
                                                 && (id!=49 && id!=53)
                                                 && strategies_that_did_not_work IS NOT NULL ORDER BY dep_code DESC");
                 $number = 1;

                 if($total_rows = mysqli_num_rows($sql_query) > 0)
                 {?>
                 <table class="table table-striped table-bordered table-hover" width="100%" style="overflow:hidden;">
                   <thead>
                     <tr>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">#</p></td>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Did Not Work</p></td>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">Directorate</p></td>
                     </tr>
                   </thead>

                   <?php
                   while($row = mysqli_fetch_array($sql_query))
                   {?>
                   <tr style="cursor: pointer;">
                     <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">
                       <?php
                       $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
                       echo $directorate_row['directorate_id'];

                       ?>

                     </p></td>
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

                 $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE changed='no' &&
                                                 period='".$select_period."' && quarter='".$select_quarter."'
                                                 && id!=51
                                                 && near_misses IS NOT NULL ORDER BY dep_code DESC");
                 $number = 1;

                 if($total_rows = mysqli_num_rows($sql_query) > 0)
                 {?>
                 <table class="table table-striped table-bordered table-hover" width="100%" style="overflow:hidden;">
                   <thead>
                     <tr>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">#</p></td>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">Near Misses</p></td>
                       <td><p class="paragraph-font-lessons" style="font-size:12px;">Directorate</p></td>
                     </tr>
                   </thead>

                   <?php
                   while($row = mysqli_fetch_array($sql_query))
                   {?>
                   <tr style="cursor: pointer;">
                     <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">
                       <?php
                       $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
                       echo $directorate_row['directorate_id'];

                       ?></p></td>
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
         <div style="page-break-after:always;"></div>
         <div class="card">
           <div class="card-header">
             <h3 class="card-title">Crystallized Risks</h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body table-responsive no-padding">
              <?php

            $sql_query =  mysqli_query($dbc,"SELECT * FROM incident_report
                                                WHERE changed='no' && period_from='".$select_period."'
                                                && quarter='".$select_quarter."'");

             $number = 1;
             if($total_rows = mysqli_num_rows($sql_query) > 0)
             {?>
             <table class="table table-hover" id="incident-reports-table" width="100%" style="overflow:hidden;">
               <thead>
                 <tr>
                   <td>No</td>
                   <td>The Event</td>
                   <td>Impact</td>
                   <td>Root Causes</td>
                   <td>Corrective Action Plans</td>
                   <td>Lessons Learnt</td>
                   <td>Directorate</td>
                 </tr>
               </thead>
               <?php
               while($row = mysqli_fetch_array($sql_query))
               {?>
               <tr style="cursor: pointer;">

                 <input type="hidden" id="incident_report_department" value="<?php echo $row['department_name'];?>">
                 <td><?php echo $number++;?></td>
                 <td><?php echo $row['the_event'];?></td>
                 <td><?php echo $row['impact'];?></td>
                 <td><?php echo $row['root_causes'];?></td>
                 <td><?php echo $row['corrective_action_plans'];?></td>
                 <td><?php echo $row['lessons_learnt'];?></td>
                 <td>
                   <?php
                   $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['department_code']."'"));
                   echo $directorate_row['directorate_id'];
                   ?>
                 </td>

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
         <!-- /.card -->

<?php
}
else
{
?>
  <h1 class="d-none" style="text-align:center;" class="corporate-risk-management-report-header">
   Directorate Risks Management Report <br/>[For <?php echo $select_quarter .','. $select_period;?>]<br/>
   - <br/>
   <?php echo $select_directorate;?>
 </h1>
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<form action="views/reports/pdf/pdf-directorate-risk-management-report.php" method="post" target="_blank">
  <input type="hidden" name="select_period" value="<?php echo $select_period;?>">
  <input type="hidden" name="select_quarter" value="<?php echo $select_quarter;?>">
  <input type="hidden" name="directorates" value="<?php echo $select_directorate ;?>">
  <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
</form>
<!-- start corporate activities with corporate risks -->
<?php
$sql = mysqli_query($dbc,
                        "SELECT * FROM directors_cumulative_table WHERE
                                    year_id='".$select_period."' &&
                                    quarter_id='".$select_quarter."' &&
                                    directorate_id='".$select_directorate."'
                                    && overall_score > 19
                                    ORDER BY overall_score DESC

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
    <h3 class="card-title">Cumulative Risks with Cumulative Activities:<br/><b>
         </b>
    </h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body table-responsive no-padding">
       <table class="table table-striped table-bordered table-hover" id="detailed_corporate_activities_related_risks_table" width="100%" style="overflow:hidden;" autosize="1">
         <thead>
           <tr>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="30">NO</td>
             <td class="activity-font-directorate-header" style="font-size:14px;" width="250">Strategic Objective</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Cumulative Risk</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Cumulative Activity</td>
             <td class="activity-font-directorate-header" style="font-size:14px;">Expected Cumulative Outcome</td>
           </tr>
         </thead>
         <?php
         $number = 1;
         while($row = mysqli_fetch_array($sql))
         {
           ?>
             <tr>
               <td><p class="activity-font-directorate"><?php echo $number++ ;?></p></td>
               <td>
                  <?php
                        $directors_activity = mysqli_query($dbc,"SELECT * FROM directors_risk_strategic_objective WHERE
                                                              directors_cumulative_id='".$row['directors_cumulative_id']."'
                                                              ");
                        while($directors_activity_row = mysqli_fetch_array($directors_activity))
                        {
                          $so_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM strategic_objectives WHERE
                                                                  strategic_objective_id='".$directors_activity_row['strategic_objective_id']."'"));
                          ?>
                          <table>
                            <tr style="border-style:hidden;">
                              <td style="overflow: wrap;border-style:hidden;">
                                <p class="activity-font-directorate">- <?php echo $so_description['strategic_objective_description']; ?></p>
                              </td>

                            </tr>
                          </table>
                          <?php

                        }
                   ?>

               </td>
                 <?php
                 $overall_score = $row['overall_score'];
                 $risk_opportunity = $row['risk_opportunity'];
                 if($overall_score < 26 && $overall_score > 19 && $risk_opportunity == 'risk')
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>

                   <?php
                 }
                 if($overall_score < 26 && $overall_score > 19 && $risk_opportunity == 'opportunity')
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#0272a6; overflow: wrap; color:white;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>

                   <?php
                 }
                 if($overall_score < 17 && $overall_score > 9)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFC200; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 10 && $overall_score > 5)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFFF00; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 5 && $overall_score > 2)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#00FF00; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 if($overall_score < 3 && $overall_score > 0)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#006400; overflow: wrap;border-style:hidden;">
                         <p class="activity-font-directorate"><?php echo $row['cumulative_risk_description'] ;?></p><br/>
                         <p style="font-weight:bold;" class="activity-font-directorate"><?php echo $overall_score; ?></p>
                       </td>
                   <?php
                 }
                 ?>


               <?php
                 //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                   $int = $row['cumulative_activity_score'];
                   if($int < 20 && $int > 0)
                   {
                     ?>
                     <td style="background-color:#FF0000;color:white;">
                       <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 40 && $int > 19)
                   {
                     ?>
                     <td style="background-color:#FFC200;color:black;">
                       <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>

                     <?php
                   }
                   if($int < 60 && $int > 39)
                   {
                     ?>
                     <td style="background-color:#FFFF00;color:black;">
                       <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 80 && $int > 59)
                   {
                     ?>
                     <td style="background-color:#00FF00; color:black;">
                       <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
                     </td>
                     <?php
                   }
                   if($int < 101 && $int > 79)
                   {
                     ?>
                     <td style="background-color:#006400; color:white;">
                      <?php echo $row['cumulative_activity_description'];?>
                       <div style="font-weight:bold;" class="activity-font-directorate"><?php echo $int; ?> %</div>
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
                   <p class="activity-font-directorate"><?php echo $row['expected_cumulative_outcomes'];?></p>
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
   <div class="col-md-8"  style="overflow:wrap;width:600px; float:left;">
     <h3 class="card-title">Cumulative Risks heatmap</h3>
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
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='1'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
              <br/>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='2'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='3'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="very_high" style="background-color: #FF0000; width:200px;" title="OVERALL SCORE: 20">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='4'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }
              ?>
           </td>
           <td class="very_high" style="background-color: #FF0000;width:200px;" title="OVERALL SCORE: 25">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='5'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
         </tr>
         <tr>
           <td>Major <br/><small class="text-primary">4</small></td>
               <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='1'
                                            && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }
                  ?>
               </td>
               <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='2'
                                             && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }

                  ?>
               </td>
               <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12">
                 <?php
                    $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                               WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='3'
                                                && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                     if(mysqli_num_rows($sql) > 0)
                     {
                       while ($risk_position = mysqli_fetch_array($sql)) {
                         echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                       }
                     }

                  ?>
               </td>
               <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 16">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='4'
                                             && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }

                  ?>
               </td>
               <td class="very_high" style="background-color: #FF0000;" title="OVERALL SCORE: 20">
                 <?php
                 $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                            WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='5'
                                             && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                  if(mysqli_num_rows($sql) > 0)
                  {
                    while ($risk_position = mysqli_fetch_array($sql)) {
                      echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                    }
                  }
                  ?>
               </td>

         </tr>
         <tr>
           <td>Moderate <br/><small class="text-primary">3</small></td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='1'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='2'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 9">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='3'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 12">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='4'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 15">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='5'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
         </tr>
         <tr>
           <td>Minor <br/><small class="text-primary">2</small></td>
           <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='1'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }


              ?>
           </td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='2'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }
              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 6">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='3'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 8">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='4'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td  class="high" style="background-color: #FFC200;" title="OVERALL SCORE: 10">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='5'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
         </tr>
         <tr>
           <td>Insignificant <br/><small class="text-primary">1</small></td>
           <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 1">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='1'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="very_low" style="background-color: #006400;" title="OVERALL SCORE: 2">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='2'
                                         && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }
              ?>
           </td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 3">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='3'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="low" style="background-color: #00FF00;" title="OVERALL SCORE: 4">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='4'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                }
              }

              ?>
           </td>
           <td class="medium" style="background-color: #FFFF00;" title="OVERALL SCORE: 5">
             <?php
             $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                        WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='5'
                                        && risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
              if(mysqli_num_rows($sql) > 0)
              {
                while ($risk_position = mysqli_fetch_array($sql)) {
                  echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
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

 <div class="col-md-4 heatmap-ratings-table table-responsive" style="overflow:wrap;width:400px; float:right;">
 <?php
   $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE directorate_id='".$select_directorate."'
                                    && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                    && risk_opportunity='risk'  && overall_score > 19 ORDER BY overall_score DESC");
   $number = 1;
   if($total_rows = mysqli_num_rows($sql_query) > 0)
   {?>
     <table class="table table-bordered table-striped table-hover" id="corporate-risks-heatmap-table">
         <thead>
           <tr>
             <td style="font-size:12px;">No</td>
             <td style="font-size:12px;">Cumulative Risk</td>
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
             <td style="font-size:12px;"><?php echo $row['cumulative_risk_description'];?></td>
             <td style="font-size:12px;"><?php echo $row['overall_score'];?></td>
             <td style="font-size:12px;"><?php echo $row['directors_cumulative_id'];?></td>
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
         at the (<span class="text-info">Directorate level</span>)

       </td>

     </tr>

   </table>

   <?php
 }
        ?>
 </div>
 </div>
 <!-- end of div heatmap -->
 <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->
    <div class="card">
       <div class="card-header">
         <h3 class="card-title">Detailed Status of Risks</h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body table-responsive no-padding">
         <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no IN
                                                               (SELECT risk_reference FROM directors_risk_table WHERE
                                                                 year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                                                  && directorate_id='".$select_directorate."')
                                        && risk_opportunity='risk' &&
                                       changed='no' &&
                                       period_from='".$select_period."' && quarter='".$select_quarter."'
                                       ORDER BY current_overall_score DESC");
         $number = 1;
         $rank = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="quarterly-updates-reports-all-table" width="100%" style="overflow:hidden;">
           <thead>
             <tr>
               <td class="numbering"><p class="paragraph-font">#</p></td>
               <td><p class="paragraph-font">Description</p></td>
               <td class="driver-rating-table"><p class="driver-rating">Current Rating</p></td>
               <td><p class="driver-rating">Prior Rating</p></td>
               <td><p class="paragraph-font">Risk Drivers</td>
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
               $fetch_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no' "));
             ?>
           <tr style="cursor: pointer;">
             <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
             <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
             <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
             <td class="numbering"><p class="paragraph-font"><?php echo $rank++ ;?></p></td>
              <td><p class="paragraph-font"><?php echo $fetch_row['risk_description'];?></p></td>
              <td ><p class="paragraph-font"><?php echo $row['current_overall_score'];?>
               <br/>
                 (<?php echo $row['current_impact_score'] .'*' . $row['current_likelihood_score'];?>)

                 <br/>
                 <?php
                 if($row['current_overall_score'] > $row['prior_overall_score'])
                 {
                   ?> <!--<img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-up-48.png'> --><?php
                 }
                 else if($row['current_overall_score'] == $row['prior_overall_score'])
                 {
                   ?> <!-- <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-bi-48.png'> --><?php
                 }

                 else
                 {
                 ?> <!-- <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-down-48.png'> --><?php
                 }
                 ?>
               </p>
             </td>

             <td ><p class="paragraph-font"><?php echo $row['prior_overall_score'];?>
             <br/>
               (<?php echo $row['prior_impact_score'] .'*' . $row['prior_likelihood_score'];?>)
               </p>
             </td>
             <td >
               <?php
                 $sql_risk_drivers = "SELECT DISTINCT risk_drivers FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
             <td ><p class="paragraph-font"><?php echo $fetch_row['person_responsible'];?></p></td>
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
    <div style="page-break-after:always;"></div>
    <div class="card-body table-responsive no-padding">
      <div class="col-md-8"  style="overflow:wrap;width:600px; float:left;">
        <h3 class="card-title">Cumulative Opportunities heatmap</h3>
        <div class="table-responsive">
        <table class="table table-bordered" id="corporate-risk-analysis-heatmap">

          <?php
             $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE dep_code IN (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' ORDER BY current_overall_score DESC LIMIT 10");
             $sql_query_risk_position = mysqli_fetch_array($sql_query);
           ?>
          <tbody>
            <tr>
              <td rowspan="5" class="impact_rotate">Impact</td>
              <td>Transformational <br/><small class="text-primary">5</small></td>
              <td class="medium" style="background-color: #59b4e0;"  title="OVERALL SCORE: 5">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='1'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
                 <br/>
              </td>
              <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='2'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='3'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="very_high" style="background-color: #0272a6; width:200px;" title="OVERALL SCORE: 20">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='4'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }
                 ?>
              </td>
              <td class="very_high" style="background-color: #0272a6;width:200px;" title="OVERALL SCORE: 25">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='5' && likelihood_score='5'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
            </tr>
            <tr>
              <td>Major <br/><small class="text-primary">4</small></td>
                  <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                    <?php
                    $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                               WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='1'
                                                && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                     if(mysqli_num_rows($sql) > 0)
                     {
                       while ($risk_position = mysqli_fetch_array($sql)) {
                         echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                       }
                     }
                     ?>
                  </td>
                  <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                    <?php
                    $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                               WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='2'
                                                 && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                     if(mysqli_num_rows($sql) > 0)
                     {
                       while ($risk_position = mysqli_fetch_array($sql)) {
                         echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                       }
                     }

                     ?>
                  </td>
                  <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                                  WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='3'
                                                    && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                          }
                        }

                     ?>
                  </td>
                  <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 16">
                    <?php
                    $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                               WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='4'
                                                 && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                     if(mysqli_num_rows($sql) > 0)
                     {
                       while ($risk_position = mysqli_fetch_array($sql)) {
                         echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                       }
                     }

                     ?>
                  </td>
                  <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 20">
                    <?php
                    $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                               WHERE directorate_id='".$select_directorate."' && impact_score='4' && likelihood_score='5'
                                                 && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                     if(mysqli_num_rows($sql) > 0)
                     {
                       while ($risk_position = mysqli_fetch_array($sql)) {
                         echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                       }
                     }
                     ?>
                  </td>

            </tr>
            <tr>
              <td>Moderate <br/><small class="text-primary">3</small></td>
              <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='1'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='2'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }


                 ?>
              </td>
              <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 9">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='3'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }


                 ?>
              </td>
              <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='4'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='3' && likelihood_score='5'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }


                 ?>
              </td>
            </tr>
            <tr>
              <td>Minor <br/><small class="text-primary">2</small></td>
              <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='1'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }


                 ?>
              </td>
              <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='2'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }
                 ?>
              </td>
              <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='3'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='4'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td  class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='2' && likelihood_score='5'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
            </tr>
            <tr>
              <td>Insignificant <br/><small class="text-primary">1</small></td>
              <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 1">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='1'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='2'
                                             && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }
                 ?>
              </td>
              <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='3'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='4'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
                   }
                 }

                 ?>
              </td>
              <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 5">
                <?php
                $sql = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                           WHERE directorate_id='".$select_directorate."' && impact_score='1' && likelihood_score='5'
                                            && risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."' && overall_score > 19 ");
                 if(mysqli_num_rows($sql) > 0)
                 {
                   while ($risk_position = mysqli_fetch_array($sql)) {
                     echo "<span class='hitmap_bubble' data-toggle='bootstrap_tooltip' title='".$risk_position['cumulative_risk_description']."'>" .$risk_position['directors_cumulative_id'] ."</span><br/>";
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

    <div class="col-md-4 heatmap-ratings-table table-responsive" style="overflow:wrap;width:400px; float:right;">

    <?php
      $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE directorate_id='".$select_directorate."'
                                       && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                       && risk_opportunity='opportunity' && overall_score > 19 ORDER BY overall_score DESC");
      $number = 1;
      if($total_rows = mysqli_num_rows($sql_query) > 0)
      {?>
        <table class="table table-bordered table-striped table-hover" id="corporate-risks-heatmap-table">
            <thead>
              <tr>
                <td style="font-size:12px;">No</td>
                <td style="font-size:12px;">Cumulative Opportunity</td>
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
                <td style="font-size:12px;"><?php echo $row['cumulative_risk_description'];?></td>
                <td style="font-size:12px;"><?php echo $row['overall_score'];?></td>
                <td style="font-size:12px;"><?php echo $row['directors_cumulative_id'];?></td>
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
            at the (<span class="text-info">Directorate level</span>)

          </td>

        </tr>

      </table>

      <?php
    }
           ?>
    </div>
    </div>
    <!-- end of div heatmap -->
    <div style="page-break-after:always;"></div>
    <div class="card">
       <div class="card-header">
         <h3 class="card-title">Detailed Status of Opportunities</h3>
       </div>
       <!-- /.card-header -->
       <div class="card-body table-responsive no-padding">
         <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE reference_no IN
                                                               (SELECT risk_reference FROM directors_risk_table WHERE
                                                                 year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                                                  && directorate_id='".$select_directorate."')
                                        && risk_opportunity='opportunity' &&
                                       changed='no' &&
                                       period_from='".$select_period."' && quarter='".$select_quarter."'
                                       ORDER BY current_overall_score DESC");
         $number = 1;
         $rank = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
         <table class="table table-striped table-bordered table-hover" id="quarterly-updates-reports-all-table" width="100%" style="overflow:hidden;">
           <thead>
             <tr>
               <td class="numbering"><p class="paragraph-font">#</p></td>
               <td><p class="paragraph-font">Description</p></td>
               <td class="driver-rating-table"><p class="driver-rating">Current Rating</p></td>
               <td><p class="driver-rating">Prior Rating</p></td>
               <td><p class="paragraph-font">Opportunity Drivers</td>
               <td><p class="paragraph-font">Opportunity Enhancement Strategy Undertaken</p></td>
               <td><p class="paragraph-font">Effect of Enhancement to Authority</p></td>
               <td><p class="paragraph-font">Further action to be undertaken</p></td>
               <td><p class="paragraph-font">Person Responsible</p></td>
             </tr>
           </thead>
           <?php
           while($row = mysqli_fetch_array($sql_query))
           {
               //fetch department name from the risk management table
               $fetch_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no' "));
             ?>
           <tr style="cursor: pointer;">
             <input type="hidden" id="department" value="<?php echo $fetch['department'];?>">
             <input type="hidden" id="period_from" value="<?php echo $row['period_from'];?>">
             <input type="hidden" id="quarter" value="<?php echo $row['quarter'];?>">
             <td class="numbering"><p class="paragraph-font"><?php echo $rank++ ;?></p></td>
              <td><p class="paragraph-font"><?php echo $fetch_row['risk_description'];?></p></td>
              <td ><p class="paragraph-font"><?php echo $row['current_overall_score'];?>
               <br/>
                 (<?php echo $row['current_impact_score'] .'*' . $row['current_likelihood_score'];?>)

                 <br/>
                 <?php
                 if($row['current_overall_score'] > $row['prior_overall_score'])
                 {
                   ?> <!--<img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-up-48.png'> --><?php
                 }
                 else if($row['current_overall_score'] == $row['prior_overall_score'])
                 {
                   ?> <!-- <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-bi-48.png'> --><?php
                 }

                 else
                 {
                 ?> <!-- <img src='https://pprmis.cma.or.ke/prmis/dist/img/arrow-down-48.png'> --><?php
                 }
                 ?>
               </p>
             </td>

             <td ><p class="paragraph-font"><?php echo $row['prior_overall_score'];?>
             <br/>
               (<?php echo $row['prior_impact_score'] .'*' . $row['prior_likelihood_score'];?>)
               </p>
             </td>
             <td >
               <?php
                 $sql_risk_drivers = "SELECT DISTINCT risk_drivers FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
             <td ><p class="paragraph-font"><?php echo $fetch_row['person_responsible'];?></p></td>
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

       <!-- start insert a page break -->
         <div style="page-break-after:always;"></div>
       <!-- end insert a page break -->

       <!-- start emerging trends -->
       <?php
       $sql_query = mysqli_query($dbc,"SELECT * FROM emerging_trends WHERE changed='no'
                                            && period='".$select_period."' && quarter='".$select_quarter."' && dep_code IN
                                        (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."')
                    ");
       $number = 1;
       if($total_rows = mysqli_num_rows($sql_query) > 0)
       {?>
         <h4>Emerging Trends</h4>
       <table class="table table-striped table-bordered table-hover" id="emerging-trends-table">
         <thead>
           <tr>
             <td>NO</td>
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
         <tfoot>
             <tr>
               <th>NO</th>
               <th>Factor</th>
               <th>External/Internal</th>
               <th>Related Risk Event</th>
               <th>Changes in Risk Profile</th>
             </tr>
         </tfoot>
       </table>
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
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">#</p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Worked Well</p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">Directorate</p></td>
                   </tr>
                 </thead>

                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
                   <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
                   <td><p class="paragraph-font-lessons" style="font-size:12px;">
                     <?php
                     $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
                     echo $directorate_row['directorate_id'];

                     ?></p></td>
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
                                               period='".$select_period."' && quarter='".$select_quarter."'
                                                && (id!=49 && id!=53) ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
                 <thead>
                   <tr>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">#</p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">Strategies That Did Not Work</p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">Directorate</p></td>
                   </tr>
                 </thead>

                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
                   <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
                   <td><p class="paragraph-font-lessons" style="font-size:12px;">
                     <?php
                     $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
                     echo $directorate_row['directorate_id'];
                     ?>

                   </p></td>
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
                                               period='".$select_period."' && quarter='".$select_quarter."'
                                               && id!=51 ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="table table-striped table-bordered table-hover" id="lessons-learnt-table" style="overflow:hidden;">
                 <thead>
                   <tr>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">#</p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">Near Misses</p></td>
                     <td><p class="paragraph-font-lessons" style="font-size:12px;">Directorate</p></td>
                   </tr>
                 </thead>

                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo $number++;?></p></td>
                   <td><p class="paragraph-font-lessons" style="font-size:12px;"><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
                   <td><p class="paragraph-font-lessons" style="font-size:12px;">
                     <?php
                     $directorate_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['dep_code']."'"));
                     echo $directorate_row['directorate_id'];

                      ?></p></td>
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

<?php

} //end of else

 ?>
 <!-- start insert a page break -->

<?php
}
 ?>
