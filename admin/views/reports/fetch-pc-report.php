<?php

session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!isset($_SESSION['email']))
  {
     exit("unauthenticated");
  }

  if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin")
  {
    //for the admin/superuser who wants specific reports
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
      //IF USER SELECTS SP-PC & SP
        //filter monthly pc updates
        if($_POST['pc_filter_type'] == "monthly")
        {
          $month_and_year = mysqli_real_escape_string($dbc,strip_tags($_POST['month-and-year']));

          //START MONTHLY PC REPORT FOR ALL DEPARTMENTS
          if($_POST['departments'] == "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="month-and-year" value="<?php echo $month_and_year;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="all">
              <input type="hidden" name="pc_filter_type" value="monthly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>
            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">MONTHLY SP-PC REPORT FOR ALL DEPARTMENTS<br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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
                                  <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $selected_department;?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php
                                        echo $row['ACT_DESC'];?>
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
                                  <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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

          //END MONTHLY PC REPORT FOR ALL DEPARTMENTS



          //START MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
          if($_POST['departments'] != "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="month-and-year" value="<?php echo $month_and_year;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="<?php echo $selected_department;?>">
              <input type="hidden" name="pc_filter_type" value="monthly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>

            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">MONTHLY SP-PC REPORT FOR <?php echo $selected_department ;?><br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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
                                  <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php
                                        echo $row['ACT_DESC'];?>
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
                                  <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
          //END MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
        }

        //filter quarterly pc updates
        if($_POST['pc_filter_type'] == "quarterly")
        {
          $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
          //START QUARTERLY PC REPORT FOR ALL DEPARTMENTS
          if($_POST['departments'] == "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="select_quarter" value="<?php echo $quarter_id;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="all">
              <input type="hidden" name="pc_filter_type" value="quarterly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>
            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">QUARTERLY SP-PC REPORT FOR ALL DEPARTMENTS<br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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
                                  <td style="background:#FF0000;color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php
                                        echo $row['ACT_DESC'];?>
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
                                  <td style="background:#FFC200;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#FFFF00;color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#00FF00; color:black;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:black;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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
                                  <td style="background:#006400; color:white;" onclick="ViewActivity('<?php echo $row['ACT_ID'] ;?>','<?php echo $row['DEP_ID'];?>','<?php echo $year_id;?>');">
                                    <form method="post" action="pages/perfomance-management/update-workplan.php" target="_blank">
                                        <input type="hidden" value="<?php echo $row['ACT_ID'];?>" name="activity_id">
                                        <input type="hidden" value="<?php echo $year_id;?>" name="year_id">
                                        <input type="hidden" value="<?php echo $department_name['department_name'];?>" name="selected_department">
                                        <button type="submit" name="submit" class="btn btn-link" style="color:white;white-space: normal; width: 200px; text-align:left;" title="Click to Edit/Update/WORKPLAN | <?php echo $row['activity_id'] ;?>">
                                        <?php echo $row['ACT_DESC'];?>
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

          //END QUARTERLY PC REPORT FOR ALL DEPARTMENTS

          //START QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
          if($_POST['departments'] != "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="select_quarter" value="<?php echo $quarter_id;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="<?php echo $selected_department;?>">
              <input type="hidden" name="pc_filter_type" value="quarterly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>
            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">QUARTERLY SP-PC REPORT FOR <?php echo $selected_department;?><br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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

          //END QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
        }
    }
    else
    {
      //IF SUPERUSER SELECTS ALL

        //filter monthly pc updates
        if($_POST['pc_filter_type'] == "monthly")
        {
          $month_and_year = mysqli_real_escape_string($dbc,strip_tags($_POST['month-and-year']));

          //START MONTHLY PC REPORT FOR ALL DEPARTMENTS
          if($_POST['departments'] == "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="month-and-year" value="<?php echo $month_and_year;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="all">
              <input type="hidden" name="pc_filter_type" value="monthly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>
            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">MONTHLY SP-PC REPORT FOR ALL DEPARTMENTS<br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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

          //END MONTHLY PC REPORT FOR ALL DEPARTMENTS



          //START MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
          if($_POST['departments'] != "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="month-and-year" value="<?php echo $month_and_year;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="<?php echo $selected_department;?>">
              <input type="hidden" name="pc_filter_type" value="monthly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>

            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">MONTHLY SP-PC REPORT FOR <?php echo $selected_department ;?><br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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
          //END MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
        }

        //filter quarterly pc updates
        if($_POST['pc_filter_type'] == "quarterly")
        {
          $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
          //START QUARTERLY PC REPORT FOR ALL DEPARTMENTS
          if($_POST['departments'] == "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="select_quarter" value="<?php echo $quarter_id;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="all">
              <input type="hidden" name="pc_filter_type" value="quarterly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>
            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">QUARTERLY SP-PC REPORT FOR ALL DEPARTMENTS<br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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

          //END QUARTERLY PC REPORT FOR ALL DEPARTMENTS

          //START QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
          if($_POST['departments'] != "all")
          {
            ?>
            <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
              <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
              <input type="hidden" name="select_quarter" value="<?php echo $quarter_id;?>">
              <input type="hidden" name="activity_type" value="<?php echo $activity_type;?>">
              <input type="hidden" name="departments" value="<?php echo $selected_department;?>">
              <input type="hidden" name="pc_filter_type" value="quarterly">
              <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
            </form>
            <?php
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
              <!-- end insert a page break -->
              <div class="card">
               <div class="card-header with-border">
                 <h3 class="card-title">QUARTERLY SP-PC REPORT FOR <?php echo $selected_department;?><br/>
                 </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                      <thead>
                        <tr>
                          <td>NO</td>
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

          //END QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
        }
    } //END FOR SUPERUSER SELECTING ALL ACTIVIT TYPES


  }
  else
  {
    //this is for the standard user
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

      //START MONTHLY PC REPORT FOR ALL DEPARTMENTS
      if($_POST['departments'] == "all")
      {
        ?>
        <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
          <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
          <input type="hidden" name="month-and-year" value="<?php echo $month_and_year;?>">
          <input type="hidden" name="departments" value="all">
          <input type="hidden" name="pc_filter_type" value="monthly">
          <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
        </form>
        <?php
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
          <!-- end insert a page break -->
          <div class="card">
           <div class="card-header with-border">
             <h3 class="card-title">MONTHLY SP-PC REPORT FOR ALL DEPARTMENTS<br/>
             </h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                  <thead>
                    <tr>
                      <td>NO</td>
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

      //END MONTHLY PC REPORT FOR ALL DEPARTMENTS



      //START MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
      if($_POST['departments'] != "all")
      {
        ?>
        <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
          <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
          <input type="hidden" name="month-and-year" value="<?php echo $month_and_year;?>">
          <input type="hidden" name="departments" value="<?php echo $selected_department;?>">
          <input type="hidden" name="pc_filter_type" value="monthly">
          <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
        </form>

        <?php
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
          <!-- end insert a page break -->
          <div class="card">
           <div class="card-header with-border">
             <h3 class="card-title">MONTHLY SP-PC REPORT FOR <?php echo $selected_department ;?><br/>
             </h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                  <thead>
                    <tr>
                      <td>NO</td>
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
      //END MONTHLY PC REPORT FOR A PARTICULAR DEPARTMENT
    }

    //filter quarterly pc updates
    if($_POST['pc_filter_type'] == "quarterly")
    {
      $quarter_id = mysqli_real_escape_string($dbc,strip_tags($_POST['select_quarter']));
      //START QUARTERLY PC REPORT FOR ALL DEPARTMENTS
      if($_POST['departments'] == "all")
      {
        ?>
        <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
          <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
          <input type="hidden" name="select_quarter" value="<?php echo $quarter_id;?>">
          <input type="hidden" name="departments" value="all">
          <input type="hidden" name="pc_filter_type" value="quarterly">
          <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
        </form>
        <?php
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
          <!-- end insert a page break -->
          <div class="card">
           <div class="card-header with-border">
             <h3 class="card-title">QUARTERLY SP-PC REPORT FOR ALL DEPARTMENTS<br/>
             </h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                  <thead>
                    <tr>
                      <td>NO</td>
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

      //END QUARTERLY PC REPORT FOR ALL DEPARTMENTS

      //START QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
      if($_POST['departments'] != "all")
      {
        ?>
        <form action="views/reports/pdf/pdf-pc-report.php" method="post" target="_blank">
          <input type="hidden" name="select_period" value="<?php echo $year_id;?>">
          <input type="hidden" name="select_quarter" value="<?php echo $quarter_id;?>">
          <input type="hidden" name="departments" value="<?php echo $selected_department;?>">
          <input type="hidden" name="pc_filter_type" value="quarterly">
          <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
        </form>
        <?php
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
          <!-- end insert a page break -->
          <div class="card">
           <div class="card-header with-border">
             <h3 class="card-title">QUARTERLY SP-PC REPORT FOR <?php echo $selected_department;?><br/>
             </h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="detailed_activities_related_risks_table" style="overflow:hidden" >
                  <thead>
                    <tr>
                      <td>NO</td>
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

      //END QUARTERLY PC REPORT FOR A PARTICULAR DEPARTMENT
    }
  }


}
else
{
  exit("NO data");
  ?>

 <?php
}

 ?>
