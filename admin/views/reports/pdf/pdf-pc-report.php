<?php
session_start();
$generated_by = str_replace(' ', '', $_SESSION['name']);
$generated_on = date("Y-m-d");
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

  if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin")
  {
    $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
    $select_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
    $activity_type = mysqli_real_escape_string($dbc,strip_tags($_POST['activity_type']));
    $activity_type_sp = "SP";
    $activity_type_pc = "PC";
    $activity_type_sp_pc = "SP & PC";
    $activity_type_pc_corporate = "PC & Corporate";

    $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));

    if($activity_type == "SP-PC & SP")
    {
      //if superuser selects sp-pc & sp

          //filter monthly pc updates
          if($_POST['pc_filter_type'] == "monthly")
          {
            $month_and_year = mysqli_real_escape_string($dbc,strip_tags($_POST['month-and-year']));

            $month = substr($month_and_year,0,2);
            $dateObj   = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F'); // March
            $monthName = strtoupper($monthName);

            //START MONTHLY PC REPORT FOR ALL DEPARTMENTS

            if($_POST['departments'] == "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT   DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                a.activity_type_id = '".$activity_type_sp."'
                                                OR
                                                a.activity_type_id = '".$activity_type_sp_pc."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.month = '".$month_and_year."'
                                                AND a.activity_status='open'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                 MONTHLY SP-PC REPORT FOR  <?php echo $monthName ;?>  <?php echo $year_id ;?><br/>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table">
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Department</td>
                            <td>Reporting</td>
                            <td>Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php
                                    $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                                    department_id='".$row['DEP_ID']."'"));


                              echo $department_name['department_name'] ;?>
                              </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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
              $filename = "Monthly_PC_Report_".$select_period."_".$monthName."-Generated-by-".$generated_by."-on".$generated_on;
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

            //END MONTHLY PC REPORT FOR ALL DEPARTMENTS



            //START MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
            if($_POST['departments'] != "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT   DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                  a.activity_type_id = '".$activity_type_sp."'
                                                  OR
                                                  a.activity_type_id = '".$activity_type_sp_pc."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.month = '".$month_and_year."'
                                                AND a.activity_status='open'
                                                AND a.department_id='".$selected_department."'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                  MONTHLY SP-PC REPORT FOR  <?php echo $monthName ;?>  <?php echo $year_id ;?><br/>
                    - <?php echo $selected_department ;?>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table">
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Reporting</td>
                            <td>Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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
                $filename = "Monthly_PC_Report_".$selected_department."_".$select_period."_".$monthName."-Generated-by-".$generated_by."-on".$generated_on;;
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
            //END MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
          }

          //filter quarterly pc updates
          if($_POST['pc_filter_type'] == "quarterly")
          {
            $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
            //START QUARTERLY PC REPORT FOR ALL DEPARTMENTS
            if($_POST['departments'] == "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT  DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                  a.activity_type_id = '".$activity_type_sp."'
                                                  OR
                                                  a.activity_type_id = '".$activity_type_sp_pc."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.quarter_id = '".$quarter_id."'
                                                AND a.activity_status='open'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                 QUARTERLY SP-PC REPORT <br/>[For <?php echo $year_id .' , '. $quarter_id;?>]<br/>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table" >
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Department</td>
                            <td>Reporting</td>
                            <td width="100">Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php
                                    $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                                    department_id='".$row['DEP_ID']."'"));


                              echo $department_name['department_name'] ;?>
                              </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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

                $select_quarter = implode(" ",$select_quarter);
                $filename = "Quarterly_PC_Report_".$select_period."_".$select_quarter."-Generated-by-".$generated_by."-on".$generated_on;;
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

            //END QUARTERLY PC REPORT FOR ALL DEPARTMENTS

            //START QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
            if($_POST['departments'] != "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                  a.activity_type_id = '".$activity_type_sp."'
                                                  OR
                                                  a.activity_type_id = '".$activity_type_sp_pc."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.quarter_id = '".$quarter_id."'
                                                AND a.activity_status='open'
                                                AND a.department_id='".$selected_department."'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                 QUARTERLY SP-PC REPORT <br/>[For <?php echo $year_id .' , '. $quarter_id;?>]<br/>
                 - <?php echo $selected_department ;?>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table" >
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Reporting</td>
                            <td width="100">Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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

              $select_quarter = implode(" ",$select_quarter);
              $filename = "Quarterly_PC_Report_".$selected_department."_".$select_period."_".$select_quarter."-Generated-by-".$generated_by."-on".$generated_on;;
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

            //END QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
          }
    }
    else
    {
      //if superuser selects all




          //filter monthly pc updates
          if($_POST['pc_filter_type'] == "monthly")
          {
            $month_and_year = mysqli_real_escape_string($dbc,strip_tags($_POST['month-and-year']));

            $month = substr($month_and_year,0,2);
            $dateObj   = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F'); // March
            $monthName = strtoupper($monthName);

            //START MONTHLY PC REPORT FOR ALL DEPARTMENTS

            if($_POST['departments'] == "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT   DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                a.activity_type_id = '".$activity_type_sp."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_sp_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc_corporate."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.month = '".$month_and_year."'
                                                AND a.activity_status='open'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                 MONTHLY SP-PC REPORT FOR  <?php echo $monthName ;?>  <?php echo $year_id ;?><br/>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table">
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Department</td>
                            <td>Reporting</td>
                            <td>Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php
                                    $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                                    department_id='".$row['DEP_ID']."'"));


                              echo $department_name['department_name'] ;?>
                              </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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
              $filename = "Monthly_PC_Report_".$select_period."_".$monthName."-Generated-by-".$generated_by."-on".$generated_on;;
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

            //END MONTHLY PC REPORT FOR ALL DEPARTMENTS



            //START MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
            if($_POST['departments'] != "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT   DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                a.activity_type_id = '".$activity_type_sp."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_sp_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc_corporate."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.month = '".$month_and_year."'
                                                AND a.activity_status='open'
                                                AND a.department_id='".$selected_department."'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                  MONTHLY SP-PC REPORT FOR  <?php echo $monthName ;?>  <?php echo $year_id ;?><br/>
                    - <?php echo $selected_department ;?>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table">
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Reporting</td>
                            <td>Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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
                $filename = "Monthly_PC_Report_".$selected_department."_".$select_period."_".$monthName."-Generated-by-".$generated_by."-on".$generated_on;;
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
            //END MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
          }

          //filter quarterly pc updates
          if($_POST['pc_filter_type'] == "quarterly")
          {
            $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
            //START QUARTERLY PC REPORT FOR ALL DEPARTMENTS
            if($_POST['departments'] == "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT  DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                a.activity_type_id = '".$activity_type_sp."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_sp_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc_corporate."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.quarter_id = '".$quarter_id."'
                                                AND a.activity_status='open'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                 QUARTERLY SP-PC REPORT <br/>[For <?php echo $year_id .' , '. $quarter_id;?>]<br/>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table" >
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Department</td>
                            <td>Reporting</td>
                            <td width="100">Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php
                                    $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                                    department_id='".$row['DEP_ID']."'"));


                              echo $department_name['department_name'] ;?>
                              </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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

                $select_quarter = implode(" ",$select_quarter);
                $filename = "Quarterly_PC_Report_".$select_period."_".$select_quarter."-Generated-by-".$generated_by."-on".$generated_on;;
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

            //END QUARTERLY PC REPORT FOR ALL DEPARTMENTS

            //START QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
            if($_POST['departments'] != "all")
            {
              $sql = mysqli_query($dbc,
                                      "SELECT DISTINCT a.activity_id AS ACT_ID,
                                                a.activity_description AS ACT_DESC,
                                                a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                                b.performance_update_description AS ACT_UPDATE,
                                                b.estimated_current_performance AS CURRENT_ESTIMATE,
                                                b.created_by AS REPORTING,
                                                a.timeline AS TARGET,
                                                a.department_id AS DEP_ID FROM
                                                perfomance_management a
                                                JOIN
                                                performance_update b
                                                ON
                                                a.activity_id = b.activity_id
                                                WHERE
                                                (
                                                a.activity_type_id = '".$activity_type_sp."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_sp_pc."'
                                                OR
                                                a.activity_type_id = '".$activity_type_pc_corporate."'
                                                )
                                                AND b.changed = 'no'
                                                AND b.year_id = '".$year_id."'
                                                AND b.quarter_id = '".$quarter_id."'
                                                AND a.activity_status='open'
                                                AND a.department_id='".$selected_department."'

                                                ORDER BY b.estimated_current_performance DESC
                                      "
                                      );
              if($sql)
              {
                $total_rows = mysqli_num_rows($sql);
                if($total_rows > 0)
                {

                ?>

                <!-- start insert a page break -->
                <center>
                    <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
                </center>
                <h1 style="text-align:center;">
                 QUARTERLY SP-PC REPORT <br/>[For <?php echo $year_id .' , '. $quarter_id;?>]<br/>
                 - <?php echo $selected_department ;?>

               </h1>
              <div style="page-break-after:always;"></div>
                <!-- end insert a page break -->
                <div class="box">
                 <!-- /.box-header -->
                 <div class="box-body">
                    <div class="table-responsive">
                      <table width="100%" id="activities-with-risks-table" >
                        <thead>
                          <tr>
                            <td width="40">#</td>
                            <td>Activity Description</td>
                            <td>Indicator</td>
                            <td>Reporting</td>
                            <td width="100">Target</td>
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
                              <?php
                                //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                                  $int = $row['CURRENT_ESTIMATE'];
                                  if($int < 20 && $int > 0)
                                  {
                                    ?>
                                    <td style="background:#FF0000;color:white;">
                                        <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                          <?php
                                          echo $row['ACT_DESC'];?>
                                        </span>
                                       <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 40 && $int > 19)
                                  {
                                    ?>
                                    <td style="background:#FFC200;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>

                                    <?php
                                  }
                                  if($int < 60 && $int > 39)
                                  {
                                    ?>
                                    <td style="background:#FFFF00;color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 80 && $int > 59)
                                  {
                                    ?>
                                    <td style="background:#00FF00; color:black;">
                                      <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
                                     <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                                    </td>
                                    <?php
                                  }
                                  if($int < 101 && $int > 79)
                                  {
                                    ?>
                                    <td style="background:#006400; color:white;">
                                      <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                        <?php
                                        echo $row['ACT_DESC'];?>
                                      </span>
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
                               <?php echo $row['DEP_KPI'] ;?>
                               </td>
                              <td>
                              <?php echo $row['REPORTING'] ;?>
                              </td>
                              <td>
                              <?php echo $row['TARGET'] ;?>
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
                 <!-- /.box-body -->
                 <div class="box-footer">

                 </div>
                 <!-- box-footer -->
               </div>
               <!-- /.box -->
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

              $select_quarter = implode(" ",$select_quarter);
              $filename = "Quarterly_PC_Report_".$selected_department."_".$select_period."_".$select_quarter."-Generated-by-".$generated_by."-on".$generated_on;;
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

            //END QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
          }
    }

  } //START STANDARD USER
  else
  {
    $selected_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
    $select_department = mysqli_real_escape_string($dbc,strip_tags($_POST['departments']));
    $activity_type_sp = "SP";
    $activity_type_pc = "PC";
    $activity_type_sp_pc = "SP & PC";
    $activity_type_pc_corporate = "PC & Corporate";

    $year_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_period']));

    //filter monthly pc updates
    if($_POST['pc_filter_type'] == "monthly")
    {
      $month_and_year = mysqli_real_escape_string($dbc,strip_tags($_POST['month-and-year']));

      $month = substr($month_and_year,0,2);
      $dateObj   = DateTime::createFromFormat('!m', $month);
      $monthName = $dateObj->format('F'); // March
      $monthName = strtoupper($monthName);

      //START MONTHLY PC REPORT FOR ALL DEPARTMENTS

      if($_POST['departments'] == "all")
      {
        $sql = mysqli_query($dbc,
                                "SELECT   DISTINCT a.activity_id AS ACT_ID,
                                          a.activity_description AS ACT_DESC,
                                          a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                          b.performance_update_description AS ACT_UPDATE,
                                          b.estimated_current_performance AS CURRENT_ESTIMATE,
                                          b.created_by AS REPORTING,
                                          a.timeline AS TARGET,
                                          a.department_id AS DEP_ID FROM
                                          perfomance_management a
                                          JOIN
                                          performance_update b
                                          ON
                                          a.activity_id = b.activity_id
                                          WHERE
                                          (
                                          a.activity_type_id = '".$activity_type_sp."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_sp_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc_corporate."'
                                          )
                                          AND b.changed = 'no'
                                          AND b.year_id = '".$year_id."'
                                          AND b.month = '".$month_and_year."'
                                          AND a.activity_status='open'

                                          ORDER BY b.estimated_current_performance DESC
                                "
                                );
        if($sql)
        {
          $total_rows = mysqli_num_rows($sql);
          if($total_rows > 0)
          {

          ?>

          <!-- start insert a page break -->
          <center>
              <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
          </center>
          <h1 style="text-align:center;">
           MONTHLY SP-PC REPORT FOR  <?php echo $monthName ;?>  <?php echo $year_id ;?><br/>

         </h1>
        <div style="page-break-after:always;"></div>
          <!-- end insert a page break -->
          <div class="box">
           <!-- /.box-header -->
           <div class="box-body">
              <div class="table-responsive">
                <table width="100%" id="activities-with-risks-table">
                  <thead>
                    <tr>
                      <td width="40">#</td>
                      <td>Activity Description</td>
                      <td>Indicator</td>
                      <td>Department</td>
                      <td>Reporting</td>
                      <td>Target</td>
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
                        <?php
                          //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                            $int = $row['CURRENT_ESTIMATE'];
                            if($int < 20 && $int > 0)
                            {
                              ?>
                              <td style="background:#FF0000;color:white;">
                                  <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                    <?php
                                    echo $row['ACT_DESC'];?>
                                  </span>
                                 <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 40 && $int > 19)
                            {
                              ?>
                              <td style="background:#FFC200;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 60 && $int > 39)
                            {
                              ?>
                              <td style="background:#FFFF00;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 80 && $int > 59)
                            {
                              ?>
                              <td style="background:#00FF00; color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 101 && $int > 79)
                            {
                              ?>
                              <td style="background:#006400; color:white;">
                                <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
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
                         <?php echo $row['DEP_KPI'] ;?>
                         </td>
                        <td>
                        <?php
                              $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                              department_id='".$row['DEP_ID']."'"));


                        echo $department_name['department_name'] ;?>
                        </td>
                        <td>
                        <?php echo $row['REPORTING'] ;?>
                        </td>
                        <td>
                        <?php echo $row['TARGET'] ;?>
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
           <!-- /.box-body -->
           <div class="box-footer">

           </div>
           <!-- box-footer -->
         </div>
         <!-- /.box -->
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
        $filename = "Monthly_PC_Report_".$select_period."_".$monthName."-Generated-by-".$generated_by."-on".$generated_on;;
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

      //END MONTHLY PC REPORT FOR ALL DEPARTMENTS



      //START MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
      if($_POST['departments'] != "all")
      {
        $sql = mysqli_query($dbc,
                                "SELECT   DISTINCT a.activity_id AS ACT_ID,
                                          a.activity_description AS ACT_DESC,
                                          a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                          b.performance_update_description AS ACT_UPDATE,
                                          b.estimated_current_performance AS CURRENT_ESTIMATE,
                                          b.created_by AS REPORTING,
                                          a.timeline AS TARGET,
                                          a.department_id AS DEP_ID FROM
                                          perfomance_management a
                                          JOIN
                                          performance_update b
                                          ON
                                          a.activity_id = b.activity_id
                                          WHERE
                                          (
                                          a.activity_type_id = '".$activity_type_sp."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_sp_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc_corporate."'
                                          )
                                          AND b.changed = 'no'
                                          AND b.year_id = '".$year_id."'
                                          AND b.month = '".$month_and_year."'
                                          AND a.activity_status='open'
                                          AND a.department_id='".$selected_department."'

                                          ORDER BY b.estimated_current_performance DESC
                                "
                                );
        if($sql)
        {
          $total_rows = mysqli_num_rows($sql);
          if($total_rows > 0)
          {

          ?>

          <!-- start insert a page break -->
          <center>
              <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
          </center>
          <h1 style="text-align:center;">
            MONTHLY SP-PC REPORT FOR  <?php echo $monthName ;?>  <?php echo $year_id ;?><br/>
              - <?php echo $selected_department ;?>

         </h1>
        <div style="page-break-after:always;"></div>
          <!-- end insert a page break -->
          <div class="box">
           <!-- /.box-header -->
           <div class="box-body">
              <div class="table-responsive">
                <table width="100%" id="activities-with-risks-table">
                  <thead>
                    <tr>
                      <td width="40">#</td>
                      <td>Activity Description</td>
                      <td>Indicator</td>
                      <td>Reporting</td>
                      <td>Target</td>
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
                        <?php
                          //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                            $int = $row['CURRENT_ESTIMATE'];
                            if($int < 20 && $int > 0)
                            {
                              ?>
                              <td style="background:#FF0000;color:white;">
                                  <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                    <?php
                                    echo $row['ACT_DESC'];?>
                                  </span>
                                 <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 40 && $int > 19)
                            {
                              ?>
                              <td style="background:#FFC200;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 60 && $int > 39)
                            {
                              ?>
                              <td style="background:#FFFF00;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 80 && $int > 59)
                            {
                              ?>
                              <td style="background:#00FF00; color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 101 && $int > 79)
                            {
                              ?>
                              <td style="background:#006400; color:white;">
                                <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
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
                         <?php echo $row['DEP_KPI'] ;?>
                         </td>
                        <td>
                        <?php echo $row['REPORTING'] ;?>
                        </td>
                        <td>
                        <?php echo $row['TARGET'] ;?>
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
           <!-- /.box-body -->
           <div class="box-footer">

           </div>
           <!-- box-footer -->
         </div>
         <!-- /.box -->
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
          $filename = "Monthly_PC_Report_".$selected_department."_".$select_period."_".$monthName."-Generated-by-".$generated_by."-on".$generated_on;;
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
      //END MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
    }

    //filter quarterly pc updates
    if($_POST['pc_filter_type'] == "quarterly")
    {
      $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
      //START QUARTERLY PC REPORT FOR ALL DEPARTMENTS
      if($_POST['departments'] == "all")
      {
        $sql = mysqli_query($dbc,
                                "SELECT  DISTINCT a.activity_id AS ACT_ID,
                                          a.activity_description AS ACT_DESC,
                                          a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                          b.performance_update_description AS ACT_UPDATE,
                                          b.estimated_current_performance AS CURRENT_ESTIMATE,
                                          b.created_by AS REPORTING,
                                          a.timeline AS TARGET,
                                          a.department_id AS DEP_ID FROM
                                          perfomance_management a
                                          JOIN
                                          performance_update b
                                          ON
                                          a.activity_id = b.activity_id
                                          WHERE
                                          (
                                          a.activity_type_id = '".$activity_type_sp."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_sp_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc_corporate."'
                                          )
                                          AND b.changed = 'no'
                                          AND b.year_id = '".$year_id."'
                                          AND b.quarter_id = '".$quarter_id."'
                                          AND a.activity_status='open'

                                          ORDER BY b.estimated_current_performance DESC
                                "
                                );
        if($sql)
        {
          $total_rows = mysqli_num_rows($sql);
          if($total_rows > 0)
          {

          ?>

          <!-- start insert a page break -->
          <center>
              <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
          </center>
          <h1 style="text-align:center;">
           QUARTERLY SP-PC REPORT <br/>[For <?php echo $year_id .' , '. $quarter_id;?>]<br/>

         </h1>
        <div style="page-break-after:always;"></div>
          <!-- end insert a page break -->
          <div class="box">
           <!-- /.box-header -->
           <div class="box-body">
              <div class="table-responsive">
                <table width="100%" id="activities-with-risks-table" >
                  <thead>
                    <tr>
                      <td width="40">#</td>
                      <td>Activity Description</td>
                      <td>Indicator</td>
                      <td>Department</td>
                      <td>Reporting</td>
                      <td width="100">Target</td>
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
                        <?php
                          //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                            $int = $row['CURRENT_ESTIMATE'];
                            if($int < 20 && $int > 0)
                            {
                              ?>
                              <td style="background:#FF0000;color:white;">
                                  <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                    <?php
                                    echo $row['ACT_DESC'];?>
                                  </span>
                                 <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 40 && $int > 19)
                            {
                              ?>
                              <td style="background:#FFC200;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 60 && $int > 39)
                            {
                              ?>
                              <td style="background:#FFFF00;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 80 && $int > 59)
                            {
                              ?>
                              <td style="background:#00FF00; color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 101 && $int > 79)
                            {
                              ?>
                              <td style="background:#006400; color:white;">
                                <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
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
                         <?php echo $row['DEP_KPI'] ;?>
                         </td>
                        <td>
                        <?php
                              $department_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT department_name,department_id FROM departments WHERE
                                                                              department_id='".$row['DEP_ID']."'"));


                        echo $department_name['department_name'] ;?>
                        </td>
                        <td>
                        <?php echo $row['REPORTING'] ;?>
                        </td>
                        <td>
                        <?php echo $row['TARGET'] ;?>
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
           <!-- /.box-body -->
           <div class="box-footer">

           </div>
           <!-- box-footer -->
         </div>
         <!-- /.box -->
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

          $select_quarter = implode(" ",$select_quarter);
          $filename = "Quarterly_PC_Report_".$select_period."_".$select_quarter."-Generated-by-".$generated_by."-on".$generated_on;;
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

      //END QUARTERLY PC REPORT FOR ALL DEPARTMENTS

      //START QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
      if($_POST['departments'] != "all")
      {
        $sql = mysqli_query($dbc,
                                "SELECT DISTINCT a.activity_id AS ACT_ID,
                                          a.activity_description AS ACT_DESC,
                                          a.activity_type_id AS ACT_TYPE, a.departmental_kpi AS DEP_KPI,
                                          b.performance_update_description AS ACT_UPDATE,
                                          b.estimated_current_performance AS CURRENT_ESTIMATE,
                                          b.created_by AS REPORTING,
                                          a.timeline AS TARGET,
                                          a.department_id AS DEP_ID FROM
                                          perfomance_management a
                                          JOIN
                                          performance_update b
                                          ON
                                          a.activity_id = b.activity_id
                                          WHERE
                                          (
                                          a.activity_type_id = '".$activity_type_sp."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_sp_pc."'
                                          OR
                                          a.activity_type_id = '".$activity_type_pc_corporate."'
                                          )
                                          AND b.changed = 'no'
                                          AND b.year_id = '".$year_id."'
                                          AND b.quarter_id = '".$quarter_id."'
                                          AND a.activity_status='open'
                                          AND a.department_id='".$selected_department."'

                                          ORDER BY b.estimated_current_performance DESC
                                "
                                );
        if($sql)
        {
          $total_rows = mysqli_num_rows($sql);
          if($total_rows > 0)
          {

          ?>

          <!-- start insert a page break -->
          <center>
              <img src="https://pprmis.cma.or.ke/prmis/dist/img/cmapicture.jpg">
          </center>
          <h1 style="text-align:center;">
           QUARTERLY SP-PC REPORT <br/>[For <?php echo $year_id .' , '. $quarter_id;?>]<br/>
           - <?php echo $selected_department ;?>

         </h1>
        <div style="page-break-after:always;"></div>
          <!-- end insert a page break -->
          <div class="box">
           <!-- /.box-header -->
           <div class="box-body">
              <div class="table-responsive">
                <table width="100%" id="activities-with-risks-table" >
                  <thead>
                    <tr>
                      <td width="40">#</td>
                      <td>Activity Description</td>
                      <td>Indicator</td>
                      <td>Reporting</td>
                      <td width="100">Target</td>
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
                        <?php
                          //  $kpi_target = (int) filter_var($row['key'], FILTER_SANITIZE_NUMBER_INT);
                            $int = $row['CURRENT_ESTIMATE'];
                            if($int < 20 && $int > 0)
                            {
                              ?>
                              <td style="background:#FF0000;color:white;">
                                  <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                    <?php
                                    echo $row['ACT_DESC'];?>
                                  </span>
                                 <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 40 && $int > 19)
                            {
                              ?>
                              <td style="background:#FFC200;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>

                              <?php
                            }
                            if($int < 60 && $int > 39)
                            {
                              ?>
                              <td style="background:#FFFF00;color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 80 && $int > 59)
                            {
                              ?>
                              <td style="background:#00FF00; color:black;">
                                <span style="color:black;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
                               <div style="font-weight:bold; text-align:center; font-size:20px;"><?php echo $int; ?> %</div>
                              </td>
                              <?php
                            }
                            if($int < 101 && $int > 79)
                            {
                              ?>
                              <td style="background:#006400; color:white;">
                                <span style="color:white;white-space: normal; width: 200px; text-align:left;">
                                  <?php
                                  echo $row['ACT_DESC'];?>
                                </span>
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
                         <?php echo $row['DEP_KPI'] ;?>
                         </td>
                        <td>
                        <?php echo $row['REPORTING'] ;?>
                        </td>
                        <td>
                        <?php echo $row['TARGET'] ;?>
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
           <!-- /.box-body -->
           <div class="box-footer">

           </div>
           <!-- box-footer -->
         </div>
         <!-- /.box -->
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

        $select_quarter = implode(" ",$select_quarter);
        $filename = "Quarterly_PC_Report_".$selected_department."_".$select_period."_".$select_quarter."-Generated-by-".$generated_by."-on".$generated_on;;
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

      //END QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
    }
    //end of standard  user
  }


}
else
{
  exit("NO data");
  ?>

 <?php
}

 ?>
</body>
</html>
