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
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //for superuser
$select_period = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$select_quarter = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$year_id= mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));
$quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
$select_directorate = mysqli_real_escape_string($dbc,strip_tags($_POST['directorates']));


if($_POST['directorates'] == 'all')
{
  ?>
  <center>
      <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
  </center>
  <h1 style="text-align:center">
   Consolidated Performance Risk Management Report <br/>[For <?php echo $select_quarter .','. $select_period;?>]<br/>
 </h1>
<div style="page-break-after:always;"></div>
<!-- end insert a page break -->

<div class="row">
  <div class="d-none">
   <?php include("static-content-report.php");?>
   <?php include("static-content-report-directorate.php");?>
 </div>
</div>
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
 <div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Cumulative Risks with Cumulative Activities:<br/><b>
         </b>
    </h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive no-padding">
       <table width="100%" id="activities-with-risks-table">
         <thead>
           <tr>
             <td width="30">#</td>
             <td width="250">Strategic Objective</td>
             <td>Cumulative Risk</td>
             <td>Cumulative Activity</td>
             <td>Expected Cumulative Outcome</td>
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
                          <div>
                            <table>
                                  <?php echo $so_description['strategic_objective_description']; ?>
                            </table>
                          </div>
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
                       <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;">
                         <div>
                           <table>
                              <?php echo $row['cumulative_risk_description'] ;?><br/>
                              <?php echo $overall_score; ?>
                           </table>
                         </div>
                       </td>

                   <?php
                 }
                 if($overall_score < 26 && $overall_score > 19 && $risk_opportunity == 'opportunity')
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#0272a6; overflow: wrap; color:white;">
                         <div>
                           <table>
                              <?php echo $row['cumulative_risk_description'] ;?><br/>
                              <?php echo $overall_score; ?>
                           </table>
                         </div>
                       </td>

                   <?php
                 }
                 if($overall_score < 17 && $overall_score > 9)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFC200; overflow: wrap;">
                         <div>
                           <table>
                              <?php echo $row['cumulative_risk_description'] ;?><br/>
                              <?php echo $overall_score; ?>
                           </table>
                         </div>
                       </td>
                   <?php
                 }
                 if($overall_score < 10 && $overall_score > 5)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#FFFF00; overflow: wrap;">
                         <div>
                           <table>
                              <?php echo $row['cumulative_risk_description'] ;?><br/>
                              <?php echo $overall_score; ?>
                           </table>
                         </div>
                       </td>
                   <?php
                 }
                 if($overall_score < 5 && $overall_score > 2)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#00FF00; overflow: wrap;">
                         <div>
                           <table>
                              <?php echo $row['cumulative_risk_description'] ;?><br/>
                              <?php echo $overall_score; ?>
                           </table>
                         </div>
                       </td>
                   <?php
                 }
                 if($overall_score < 3 && $overall_score > 0)
                 {
                   ?>
                       <td class="risk-div-background" style="background-color:#006400; overflow: wrap;">
                         <div>
                           <table>
                              <?php echo $row['cumulative_risk_description'] ;?><br/>
                              <?php echo $overall_score; ?>
                           </table>
                         </div>
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
                       <div>
                         <table>
                            <?php echo $row['cumulative_activity_description'];?><br/>
                            <?php echo $int; ?> %
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
                            <?php echo $row['cumulative_activity_description'];?><br/>
                            <?php echo $int; ?> %
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
                            <?php echo $row['cumulative_activity_description'];?><br/>
                            <?php echo $int; ?> %
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
                            <?php echo $row['cumulative_activity_description'];?><br/>
                            <?php echo $int; ?> %
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
                            <?php echo $row['cumulative_activity_description'];?><br/>
                            <?php echo $int; ?> %
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
                       <?php echo $row['expected_cumulative_outcomes'];?><br/>
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
else  //no rows
{
  //no cumulative risks found
}
}

?>
  <!-- start insert a page break -->
    <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->

  <!-- end of corporate activities with corporate risks -->

  <!-- check for cumulative risks -->
  <?php
  $sql_heatmap_rows = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                             WHERE risk_opportunity='risk' && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                             "));
  if($sql_heatmap_rows < 1)
  {
    //no heat map
  }
  else
  {
  ?>
 <div class="box">
  <h4 class="box-title">Cumulative Risks heatmap</h4>
 <div class="box-body table-responsive no-padding">
   <div class="col-md-8"  style="overflow:wrap;width:500px; float:left;">
     <table class="heatmap-table">
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                         echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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

 <div class="col-md-3 heatmap-ratings-table" style="overflow:wrap;width:730px; float:right;">
 <?php
   $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                            && risk_opportunity='risk' && overall_score > 19
                                           ORDER BY overall_score DESC");
   $number = 1;
   if($total_rows = mysqli_num_rows($sql_query) > 0)
   {?>
     <table>
         <thead>
           <tr>
             <td>#</td>
             <td>Cumulative Risk</td>
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
             <td><?php echo $row['cumulative_risk_description'];?></td>
             <td><?php echo $row['overall_score'];?></td>
             <td><?php echo $row['directors_cumulative_id'];?></td>
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
 <?php
} //end of check heatmap availability
  ?>
 <!-- start insert a page break -->
 <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->
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
    <div class="box">
       <div class="box-header">
         <h3 class="box-title">Detailed Status of Risks</h3>
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
     else
     {
       //no detailed analysis
     }
     ?>
     <div style="page-break-after:always;"></div>

     <?php
     $sql_heatmap_rows = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                                WHERE risk_opportunity='opportunity' && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                "));
     if($sql_heatmap_rows < 1)
     {
       //no heat map
     }
     else
     {
     ?>
       <!-- /.box-body -->
      <div class="box">
         <h4 class="box-title">Cumulative Opportunities heatmap</h4>
       <div class="box-body table-responsive no-padding">
         <div class="col-md-8"  style="overflow:wrap;width:500px; float:left;">

           <table class="heatmap-table" style="page-break-inside: avoid;">
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                            echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                            echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                               echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                            echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                            echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                        echo $risk_position['directors_cumulative_id'] . "<br/>";
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

       <div class="col-md-3 heatmap-ratings-table" style="overflow:wrap;width:730px; float:right;">
       <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE
                                          year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                          && risk_opportunity='opportunity' && overall_score > 19 ORDER BY overall_score DESC");
         $number = 1;
         if($total_rows = mysqli_num_rows($sql_query) > 0)
         {?>
           <table>
               <thead>
                 <tr>
                   <td>#</td>
                   <td>Cumulative Opportunity</td>
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
                   <td><?php echo $row['cumulative_risk_description'];?></td>
                   <td><?php echo $row['overall_score'];?></td>
                   <td><?php echo $row['directors_cumulative_id'];?></td>
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
     </div>
       <!-- end of div heatmap -->
  <?php
}
   ?>
       <div style="page-break-after:always;"></div>
       <!-- end insert a page break -->
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
                     <td>Opportunity Drivers</td>
                     <td>Opportunity Enhancement Strategy Undertaken</td>
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
                         $sql_risk_drivers = "SELECT DISTINCT risk_drivers  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
         else
         {
           //no detailed analysis
         }
            ?>
             <!-- /.box-body -->

             <div style="page-break-after:always;"></div>
             <?php
             $sql_query = mysqli_query($dbc,"SELECT * FROM emerging_trends WHERE changed='no'
                                                  && period='".$select_period."' && quarter='".$select_quarter."'
                          ");
             $number = 1;
             if($total_rows = mysqli_num_rows($sql_query) > 0)
             {?>
              <h4>Emerging Trends</h4>
             <table class="simple-table" width="100%" style="overflow:hidden;">
               <thead>
                 <tr>
                   <td>#</td>
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
                     <th>#</th>
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
             <div class="box">
               <div class="box-header">
                 <h3 class="box-title">Lessons Learnt</h3>
                 <div class="feeback"></div>
               </div>
               <!-- /.box-header -->
               <div class="box-body table-responsive no-padding">
                 <?php
                 $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_worked_well WHERE changed='no' &&
                                                 period='".$select_period."' && quarter='".$select_quarter."'
                                                 && strategies_that_worked_well IS NOT NULL ORDER BY dep_code DESC");
                 $number = 1;
                 if($total_rows = mysqli_num_rows($sql_query) > 0)
                 {?>
                 <table class="simple-table" style="overflow:hidden;">
                   <thead>
                     <tr>
                       <td><p>#</p></td>
                       <td><p>Strategies That Worked Well</p></td>
                       <td><p>Directorate</p></td>
                     </tr>
                   </thead>

                   <?php
                   while($row = mysqli_fetch_array($sql_query))
                   {?>
                   <tr style="cursor: pointer;">
                     <td><p><?php echo $number++;?></p></td>
                     <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
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
                 </table>
                 <?php
                 }
                 else
                 {
                   //no strategis that worked well
                 }

                 $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE changed='no' &&
                                                 period='".$select_period."' && quarter='".$select_quarter."'
                                                 && (id!=49 && id!=53)
                                                 && strategies_that_did_not_work IS NOT NULL ORDER BY dep_code DESC");
                 $number = 1;

                 if($total_rows = mysqli_num_rows($sql_query) > 0)
                 {?>
                 <table class="simple-table" style="overflow:hidden;">
                   <thead>
                     <tr>
                       <td><p>#</p></td>
                       <td><p>Strategies That Did Not Work</p></td>
                       <td><p>Directorate</p></td>
                     </tr>
                   </thead>

                   <?php
                   while($row = mysqli_fetch_array($sql_query))
                   {?>
                   <tr style="cursor: pointer;">
                     <td><p><?php echo $number++;?></p></td>
                     <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
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
                 </table>
                 <?php
                 }
                 else
                 {
                   //No strategies that did not work well
                 }

                 $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE changed='no' &&
                                                 period='".$select_period."' && quarter='".$select_quarter."'
                                                 && id!=51
                                                 && near_misses IS NOT NULL ORDER BY dep_code DESC");
                 $number = 1;

                 if($total_rows = mysqli_num_rows($sql_query) > 0)
                 {?>
                 <table class="simple-table" style="overflow:hidden;">
                   <thead>
                     <tr>
                       <td><p>#</p></td>
                       <td><p>Near Misses</p></td>
                       <td><p>Directorate</p></td>
                     </tr>
                   </thead>

                   <?php
                   while($row = mysqli_fetch_array($sql_query))
                   {?>
                   <tr style="cursor: pointer;">
                     <td><p><?php echo $number++;?></p></td>
                     <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
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
                 </table>
                 <?php
                 }
                 else
                 {
                   //No near misses
                 }
                 ?>
               </div>
               <!-- /.box-body -->
             </div>
             <!-- /.box -->
           </div>

         </div>
         <!-- end lessons learnt -->
         <div style="page-break-after:always;"></div>
         <?php
         $sql_query =  mysqli_query($dbc,"SELECT * FROM incident_report
                                             WHERE changed='no' && period_from='".$select_period."'
                                             && quarter='".$select_quarter."'");

          $number = 1;
          if($total_rows = mysqli_num_rows($sql_query) > 0)
          {?>
         <div class="box">
           <div class="box-header">
             <h3 class="box-title">Crystallized Risks</h3>
           </div>
           <!-- /.box-header -->
           <div class="box-body table-responsive no-padding">
             <table class="simple-table" style="overflow:hidden;">
               <thead>
                 <tr>
                   <td>#</td>
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
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /.box -->
         <?php
       }
          ?>


 <?php
 $select_quarter = implode(" ",$select_quarter);
 $filename = "Consolidated_Risk_Management_Report_".$select_period."_".$select_quarter;
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

}
else
{
?>
  <center>
      <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
  </center>
  <h1 style="text-align:center;">
   Consolidated Performance Risk Management Report <br/>[For <?php echo $select_quarter .','. $select_period;?>]<br/>
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
 <div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Cumulative Risks with Cumulative Activities:<br/><b>
         </b>
    </h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive no-padding">
       <table width="100%" id="activities-with-risks-table">
         <thead>
           <tr>
             <td  width="30">#</td>
             <td  width="250">Strategic Objective</td>
             <td >Cumulative Risk</td>
             <td >Cumulative Activity</td>
             <td >Expected Cumulative Outcome</td>
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
                        <div>
                          <table>
                                <?php echo $so_description['strategic_objective_description']; ?>
                          </table>
                        </div>
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
                     <td class="risk-div-background" style="background-color:#FF0000; overflow: wrap; color:white;">
                       <div>
                         <table>
                            <?php echo $row['cumulative_risk_description'] ;?><br/>
                            <?php echo $overall_score; ?>
                         </table>
                       </div>
                     </td>

                 <?php
               }
               if($overall_score < 26 && $overall_score > 19 && $risk_opportunity == 'opportunity')
               {
                 ?>
                     <td class="risk-div-background" style="background-color:#0272a6; overflow: wrap; color:white;">
                       <div>
                         <table>
                            <?php echo $row['cumulative_risk_description'] ;?><br/>
                            <?php echo $overall_score; ?>
                         </table>
                       </div>
                     </td>

                 <?php
               }
               if($overall_score < 17 && $overall_score > 9)
               {
                 ?>
                     <td class="risk-div-background" style="background-color:#FFC200; overflow: wrap;">
                       <div>
                         <table>
                            <?php echo $row['cumulative_risk_description'] ;?><br/>
                            <?php echo $overall_score; ?>
                         </table>
                       </div>
                     </td>
                 <?php
               }
               if($overall_score < 10 && $overall_score > 5)
               {
                 ?>
                     <td class="risk-div-background" style="background-color:#FFFF00; overflow: wrap;">
                       <div>
                         <table>
                            <?php echo $row['cumulative_risk_description'] ;?><br/>
                            <?php echo $overall_score; ?>
                         </table>
                       </div>
                     </td>
                 <?php
               }
               if($overall_score < 5 && $overall_score > 2)
               {
                 ?>
                     <td class="risk-div-background" style="background-color:#00FF00; overflow: wrap;">
                       <div>
                         <table>
                            <?php echo $row['cumulative_risk_description'] ;?><br/>
                            <?php echo $overall_score; ?>
                         </table>
                       </div>
                     </td>
                 <?php
               }
               if($overall_score < 3 && $overall_score > 0)
               {
                 ?>
                     <td class="risk-div-background" style="background-color:#006400; overflow: wrap;">
                       <div>
                         <table>
                            <?php echo $row['cumulative_risk_description'] ;?><br/>
                            <?php echo $overall_score; ?>
                         </table>
                       </div>
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
                     <div>
                       <table>
                          <?php echo $row['cumulative_activity_description'];?><br/>
                          <?php echo $int; ?> %
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
                          <?php echo $row['cumulative_activity_description'];?><br/>
                          <?php echo $int; ?> %
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
                          <?php echo $row['cumulative_activity_description'];?><br/>
                          <?php echo $int; ?> %
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
                          <?php echo $row['cumulative_activity_description'];?><br/>
                          <?php echo $int; ?> %
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
                          <?php echo $row['cumulative_activity_description'];?><br/>
                          <?php echo $int; ?> %
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
                     <?php echo $row['expected_cumulative_outcomes'];?><br/>
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
else  //no rows
{
  //no cumulative risks
}
}

?>
  <!-- start insert a page break -->
    <div style="page-break-after:always;"></div>
  <!-- end insert a page break -->

  <!-- end of corporate activities with corporate risks -->
  <?php
  $sql_heatmap_rows = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                             WHERE risk_opportunity='risk' && directorate_id='".$select_directorate."'
                             && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                             "));
  if($sql_heatmap_rows < 1)
  {
    //no heat map
  }
  else
  {
  ?>

 <div class="box">
   <h4 class="box-title">Cumulative Risks heatmap</h4>
 <div class="box-body table-responsive no-padding">
   <div class="col-md-8"  style="overflow:wrap;width:500px; float:left;">
     <table class="heatmap-table">
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                         echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                      echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                  echo $risk_position['directors_cumulative_id'] . "<br/>";
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

 <div class="col-md-3 heatmap-ratings-table" style="overflow:wrap;width:730px; float:right;">
 <?php
   $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE directorate_id='".$select_directorate."'
                                    && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                    && risk_opportunity='risk' && overall_score > 19 ORDER BY overall_score DESC");
   $number = 1;
   if($total_rows = mysqli_num_rows($sql_query) > 0)
   {?>
     <table>
         <thead>
           <tr>
             <td>#</td>
             <td>Cumulative Risk</td>
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
             <td><?php echo $row['cumulative_risk_description'];?></td>
             <td><?php echo $row['overall_score'];?></td>
             <td><?php echo $row['directors_cumulative_id'];?></td>
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
 <?php
}
  ?>
 <div style="page-break-after:always;"></div>
 <!-- end insert a page break -->
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

    <div class="box">
       <div class="box-header">
         <h3 class="box-title">Detailed Status of Risks</h3>
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
               $fetch_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no' "));
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
     else
     {
       //no detailed analysis
     }

      ?>
       <!-- /.box-body -->
    <div style="page-break-after:always;"></div>

    <?php
    $sql_heatmap_rows = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM directors_cumulative_table
                               WHERE risk_opportunity='opportunity' && directorate_id='".$select_directorate."'
                               && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                               "));
    if($sql_heatmap_rows < 1)
    {
      //no heat map
    }
    else
    {
    ?>

    <div class="box">
      <h4 class="box-title">Cumulative Opportunities heatmap</h4>
    <div class="box-body table-responsive no-padding">
      <div class="col-md-8"  style="overflow:wrap;width:500px; float:left;">
        <table class="heatmap-table" style="page-break-inside: avoid;">

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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                         echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                         echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                            echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                         echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                         echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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
                     echo $risk_position['directors_cumulative_id'] . "<br/>";
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

    <div class="col-md-3 heatmap-ratings-table" style="overflow:wrap;width:730px; float:right;">
    <?php
      $sql_query = mysqli_query($dbc,"SELECT * FROM directors_cumulative_table WHERE directorate_id='".$select_directorate."'
                                       && year_id='".$select_period."' && quarter_id='".$select_quarter."'
                                       && risk_opportunity='opportunity' && overall_score > 19 ORDER BY overall_score DESC");
      $number = 1;
      if($total_rows = mysqli_num_rows($sql_query) > 0)
      {?>
        <table>
            <thead>
              <tr>
                <td>#</td>
                <td>Cumulative Opportunity</td>
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
                <td><?php echo $row['cumulative_risk_description'];?></td>
                <td><?php echo $row['overall_score'];?></td>
                <td><?php echo $row['directors_cumulative_id'];?></td>
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
  </div>
    <!-- end of div heatmap -->
    <?php
  }
     ?>
    <div style="page-break-after:always;"></div>
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
               <td>Opportunity Drivers</td>
               <td>Opportunity Enhancement Strategy Undertaken</td>
               <td>Effect of Risk to Authority</td>
               <td>Further action to be undertaken</td>
               <td width="9%">Person Responsible</td>
             </tr>
           </thead>
           <?php
           while($row = mysqli_fetch_array($sql_query))
           {
               //fetch department name from the risk management table
               $fetch_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM risk_management WHERE risk_reference='".$row['reference_no']."' &&  changed='no' "));
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
                   $sql_risk_drivers = "SELECT DISTINCT risk_drivers  FROM risk_drivers WHERE risk_reference='".$row['reference_no']."' &&
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
     else
     {
        //no detailed status
     }

     ?>
       <!-- /.box-body -->
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
       <table class="simple-table" width="100%" style="overflow:hidden;">
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
           <div class="box">
             <div class="box-header">
               <h3 class="box-title">Lessons Learnt</h3>
               <div class="feeback"></div>
             </div>
             <!-- /.box-header -->
             <div class="box-body table-responsive no-padding">
               <?php
               $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_worked_well WHERE dep_code IN
                                                (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && changed='no' &&
                                               period='".$select_period."' && quarter='".$select_quarter."' ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="simple-table" style="overflow:hidden;">
                 <thead>
                   <tr>
                     <td><p>#</p></td>
                     <td><p>Strategies That Worked Well</p></td>
                     <td><p>Directorate</p></td>
                   </tr>
                 </thead>

                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><p><?php echo $number++;?></p></td>
                   <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_worked_well']));?></p></td>
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
               </table>
               <?php
               }
               else
               {
                 //No strategies that worked well
               }
               ?>
             </div>
             <!-- /.box-body -->

             <!-- start strategies that did not work -->
             <div class="box-body table-responsive no-padding">
               <?php
               $sql_query = mysqli_query($dbc,"SELECT * FROM strategies_that_did_not_work WHERE dep_code IN
                                                (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && changed='no' &&
                                               period='".$select_period."' && quarter='".$select_quarter."' && (id!=49 && id!=53) ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="simple-table" style="overflow:hidden;">
                 <thead>
                   <tr>
                     <td><p>#</p></td>
                     <td><p>Strategies That Did Not Work</p></td>
                     <td><p>Directorate</p></td>
                   </tr>
                 </thead>

                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><p><?php echo $number++;?></p></td>
                   <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['strategies_that_did_not_work']));?></p></td>
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
               </table>
               <?php
               }
               else
               {
                 //No strategies that did not work well
               }
               ?>
             </div>
             <!-- /.box-body -->
             <!-- end strategies that did not work -->

             <!-- start near misses -->
             <div class="box-body table-responsive no-padding">
               <?php
               $sql_query = mysqli_query($dbc,"SELECT * FROM near_misses WHERE dep_code IN
                                                (SELECT department_id FROM departments WHERE directorate_id='".$select_directorate."') && changed='no' &&
                                               period='".$select_period."' && quarter='".$select_quarter."' && id!=51 ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="simple-table" style="overflow:hidden;">
                 <thead>
                   <tr>
                     <td><p>#</p></td>
                     <td><p>Near Misses</p></td>
                     <td><p>Directorate</p></td>
                   </tr>
                 </thead>

                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><p><?php echo $number++;?></p></td>
                   <td><p><?php echo  htmlspecialchars_decode(stripslashes($row['near_misses']));?></p></td>
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
               </table>
               <?php
               }
               else
               {
                 //No near misses
               }
               ?>
             </div>
             <!-- /.box-body -->
             <!-- end near misses -->

           </div>
           <!-- /.box -->
         </div>

       </div>
       <!-- end lessons learnt -->
       <!-- start insert a page break -->
         <div style="page-break-after:always;"></div>
       <!-- end insert a page break -->


 <?php
 $select_directorate = implode(" ",$select_directorate);
 $select_quarter = implode(" ",$select_quarter);
 $filename = $select_directorate."_Consolidated_Risk_Management_Report_".$select_period."_".$select_quarter;
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

} //end of else

 ?>
 <!-- start insert a page break -->

<?php
}
 ?>
</body>
</html>
