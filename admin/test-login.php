<?php
session_set_cookie_params(0);
session_start();
include("controllers/setup/connect.php");
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title id="current-title">PPRMIS | </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/fonts/fontawesome-pro-5.12.0/css/all.min.css">
  <!-- Ionicons 2.0.1-->
  <link rel="stylesheet" href="assets/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- sweetalert -->
  <link rel="stylesheet" href="assets/css/sweetalert2.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="assets/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.css">
  <!-- Datatables-->
  <link rel="stylesheet"  href="assets/css/datatables.min.css"/>
  <!-- Hover-->
  <link rel="stylesheet"  href="assets/css/hover.css"/>
    <!-- Animate-->
  <link rel="stylesheet"  href="assets/css/animate.css"/>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
  <!--Jquery UI -->
  <link href="assets/css/jquery-ui.css" rel="Stylesheet"type="text/css"/>
  <!-- Date Picker -->
<link rel="stylesheet" href="assets/css/datepicker.min.css">

  <!-- select2 css ver @4.0.12 -->
  <link href="assets/css/select2.min.css" rel="stylesheet" />
  <!-- select2-bootstrap4-theme -->
<link href="assets/css/select2-bootstrap4.css" rel="stylesheet">
  <!-- custom css -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <!-- title icon -->
  <link rel="icon" href="assets/img/cma.PNG" type="image/x-icon" />
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper" style="border:0; padding:0;">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: rgb(158, 125, 8);">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-light" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" onclick="location.reload();" class="nav-link text-light"><i class="fas fa-house"></i> Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link text-light switch-to-module"></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto" id="dynamic-navbar"></ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" data-toggle="tooltip" data-placement="bottom" title="Projects, Performance & Risk Management Information System">
      <img src="assets/img/cma.PNG" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">PPRMIS</span>
    </a>

    <!-- Sidebar -->
    <!-- IF USER IS NOT LOGGED IN, DISPLAY LOGIN SIDEBAR, ELSE, DISPLAY FULL SIDEBAR -->
    <?php
    if(!isset($_SESSION['email']))
    {
      ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-compact" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item has-treeview menu-open">
              <li class="nav-item">
                <a href="#" class="nav-link test-login-link">
                  <i class="nav-icon far fa-sign-in"></i>
                  <p>Log In</p>
                </a>
              </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
      <?php
    }
    else
    {
    ?>
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
         <?php $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE Email ='".$_SESSION['email']."'")); ?>
          <img src="assets/img/<?php echo $profile_pic['emp_photo'];?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block user-profile-link"><?php echo $_SESSION['name'] ;?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <li class="nav-item mb-1">
              <a href="#" class="nav-link active" onclick="location.reload();">
                <i class="nav-icon fal fa-house"></i>
                <p>
                  HOME
                </p>
              </a>
            </li>
            <?php
             if($_SESSION['access_level'] == 'superuser' || $_SESSION['access_level'] == 'admin')
             {
               ?>
               <li class="nav-item">
                 <a href="#" class="nav-link superuser-dashboard-link">
                   <i class="nav-icon fas fa-tachometer-alt-fast"></i>
                   <p>
                     Dashboard
                   </p>
                 </a>
               </li>
               <?php
             }
             else
             {
               ?>
               <li class="nav-item">
                 <a href="#" class="nav-link standard-dashboard-link">
                   <i class="nav-icon fas fa-tachometer-alt-fast"></i>
                   <p>
                     Dashboard
                   </p>
                 </a>
               </li>
               <?php
             }

            ?>
          <li class="nav-item">
            <a href="#" class="nav-link reports-link">
              <i class="nav-icon fas fa-file-chart-line"></i>
              <p>
                Reports
              </p>
            </a>
          </li>
          <li class="nav-item"  data-toggle="tooltip" title="Click To Add/View/Edit and Remove Objectives">
            <a href="#" class="nav-link departmental-objectives-link">
              <i class="nav-icon fas fa-bullseye-arrow"></i>
              <p>
                Objectives
              </p>
            </a>
          </li>
          <?php
           if($_SESSION['access_level'] == 'superuser' || $_SESSION['access_level'] == 'admin')
           {
             ?>
             <li class="nav-item border-top"  data-toggle="tooltip" title="Click To View departments with their updates in summary">
               <a href="#" class="nav-link update-monitoring-admin-link">
                <i class="nav-icon fal fa-ballot-check"></i>
                 <p>
                   Update Monitoring
                 </p>
               </a>
             </li>
             <?php
           }
          ?>
          <?php
          if($_SESSION['access_level'] == 'admin')
          {
           ?>
           <li class="nav-item has-treeview border-top">
            <a href="#" class="nav-link">
              <i class="nav-icon fad fa-user-shield text-success"></i>
              <p>
                Admin Portal
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link admin-user-management-link">
                  <i class="nav-icon fad fa-users-cog"></i>
                  <p>User Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link admin-logs-link">
                  <i class="nav-icon fal fa-history"></i>
                  <p>Logs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link admin-period-management-link">
                  <i class="nav-icon fad fa-calendar-day"></i>
                  <p>Period Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link admin-backup-link">
                  <i class="nav-icon fad fa-cloud-upload-alt"></i>
                  <p>Backup</p>
                </a>
              </li>
            </ul>
          </li>

           <?php
          }


          ?>

          <li class="nav-header risk-management-navbar"><strong>RISK MANAGEMENT</strong></li>
          <li class="nav-item risk-management-navbar" data-toggle="tooltip" title="Click To Add/View/Edit/Update and Retire Risks/Opportunities">


            <?php
              if($_SESSION['access_level'] == 'standard')
              {
                ?>
                <a href="#" class="nav-link" onclick="SelectDepartmentRisk('<?php echo $_SESSION['department_code']; ;?>');">
                  <i class="nav-icon far fa-file-alt"></i>
                  <p>
                    Monitor Risks
                  </p>
                </a>
                <?php
              }
              else
              {
                ?>
                <a href="#" class="nav-link monitor-risks-link">
                  <i class="nav-icon far fa-file-alt"></i>
                  <p>
                    Monitor Risks
                  </p>
                </a>
                <?php
              }


             ?>
          </li>
          <li class="nav-item risk-management-navbar"  data-toggle="tooltip" title="Click To View and Make Risk Approvals">
            <a href="#" class="nav-link risk-approvals-link">
              <i class="nav-icon far fa-thumbs-up"></i>
              <p>
                Risk Approvals
              </p>
            </a>
          </li>
          <?php
              //only admins, superuser, hods can delegate approvals -- access this link
              $sql_delegate = mysqli_num_rows(mysqli_query($dbc,"SELECT manager_id FROM departments WHERE manager_id='".$_SESSION['staff_id']."'"));

              if($sql_delegate > 0 || $_SESSION['access_level'] == 'admin' || $_SESSION['access_level'] == 'superuser')
              {
                ?>
                <li class="nav-item risk-management-navbar" data-toggle="tooltip"  title="Click to view delegated approvals or delegate Risk Approvals to another person">
                  <a href="#" class="nav-link delegate-approvals-link">
                    <i class="nav-icon fad fa-handshake"></i>
                    <p>
                      Delegate Approvals
                    </p>
                  </a>
                </li>

                <?php
              }
           ?>
          <li class="nav-item risk-management-navbar" data-toggle="tooltip" title="Click to add/view/edit or delete Emerging Trends">
            <a href="#" class="nav-link emerging-trends-link">
              <i class="nav-icon fal fa-poll"></i>
              <p>
                Emerging Trends
              </p>
            </a>
          </li>
          <li class="nav-item risk-management-navbar" data-toggle="tooltip" title="Click to add/view/edit or delete Lessons Learnt">
            <a href="#" class="nav-link lessons-learnt-link">
              <i class="nav-icon fal fa-chalkboard-teacher"></i>
              <p>
                Lessons Learnt
              </p>
            </a>
          </li>
          <li class="nav-item risk-management-navbar" data-toggle="tooltip" title="Click to add/view/edit or Crystallized risks">
            <a href="#" class="nav-link incident-reporting-link">
              <i class="nav-icon far fa-flag text-danger"></i>
              <p>
                Incident Reporting
              </p>
            </a>
          </li>

          <li class="nav-header performance-management-navbar"><strong>PERFORMANCE MANAGEMENT</strong></li>
          <li class="nav-item performance-management-navbar" data-toggle="tooltip" title="Click to add/view/edit or update activities">

            <?php
              if($_SESSION['access_level'] == 'standard')
              {
                ?>
                <a href="#" class="nav-link" onclick="SelectDepartmentWorkplan('<?php echo $_SESSION['department_code']; ;?>');">
                  <i class="nav-icon far fa-file-alt"></i>
                  <p>Monitor Workplan</p>
                </a>

                <?php
              }
              else
              {
                ?>
                <a href="#" class="nav-link monitor-workplan-link">
                  <i class="nav-icon far fa-file-alt"></i>
                  <p>Monitor Workplan</p>
                </a>
                <?php
              }

             ?>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    <?php
    }
   ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">

          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <div id="response-data">
            <?php
            if(isset($_SESSION['email']))
            {
              ?>
              <div class="row module-panel">
                  <!-- /.col -->
                  <?php
                    if($_SESSION['email'] == 'mabdalla@cma.or.ke' || $_SESSION['email'] == 'LMose@cma.or.ke' || $_SESSION['email'] == 'Pkariuki@cma.or.ke')
                    {
                      ?>
                      <div class="col-md-4 col-xs-12 project-management-module">
                        <div class="card text-center card-primary card-outline">
                            <div class="card-header">
                            <b>  PROJECT MANAGEMENT <img src="assets/img/project3.jpg" alt="" width="25" height="25"> </b>
                            </div>
                            <div class="card-body">
                              <a href="#" class="btn btn-info btn-lg btn-block">MANAGE PROJECTS</a>
                            </div>
                          </div>
                       </div>
                       <!-- /.col -->
                      <?php
                    }
                   ?>
                   <?php
                    if($_SESSION['access_level'] == 'standard')
                    {
                      ?>
                      <div class="col-md-4 risk-management-module" style="cursor:pointer;"
                            onclick="SelectDepartmentRisk('<?php echo $_SESSION['department_code']; ;?>');">
                        <div class="card text-center card-primary card-outline">
                            <div class="card-header">
                              RISK MANAGEMENT
                            </div>
                            <div class="card-body">
                              <a href="#" class="btn btn-info btn-lg btn-block">MANAGE RISKS</a>
                            </div>
                          </div>
                       </div>
                      <?php
                    }
                    else
                    {
                      ?>
                      <div class="col-md-4 risk-management-module monitor-risks-link" style="cursor:pointer;">
                        <div class="card text-center card-primary card-outline">
                            <div class="card-header">
                              RISK MANAGEMENT
                            </div>
                            <div class="card-body">
                              <a href="#" class="btn btn-info btn-lg btn-block">MANAGE RISKS</a>
                            </div>
                          </div>
                       </div>
                      <?php
                    }



                    ?>

                    <?php
                      if($_SESSION['access_level'] == 'standard')
                      {
                        ?>
                        <div class="col-md-4 performance-management-module" style="cursor:pointer;"
                            onclick="SelectDepartmentWorkplan('<?php echo $_SESSION['department_code']; ;?>');">
                          <div class="card text-center card-primary card-outline">
                              <div class="card-header">
                                PERFORMANCE MANAGEMENT
                              </div>
                              <div class="card-body">
                                <a href="#" class="btn btn-info btn-lg btn-block">MANAGE PERFORMANCE</a>
                              </div>
                            </div>
                         </div>

                        <?php
                      }
                      else
                      {
                        ?>
                        <div class="col-md-4 performance-management-module monitor-workplan-link" style="cursor:pointer;">
                          <div class="card text-center card-primary card-outline">
                              <div class="card-header">
                                PERFORMANCE MANAGEMENT
                              </div>
                              <div class="card-body">
                                <a href="#" class="btn btn-info btn-lg btn-block">MANAGE PERFORMANCE</a>
                              </div>
                            </div>
                         </div>
                        <?php
                      }


                     ?>
              </div>
              <?php
            }
            else
            {
             ?>
            <blockquote class="blockquote animated slideInUp delay-2s">
              <h4></h4>
              <div class="card">
               <div class="card-header bg-ligh">Welcome to the Projects, Performance & Risk Management Information System (PPRMIS)</div>
               <div class="card-body">
                 <h6 class="card-subtitle text-secondary mb-3">
                  The main objective for the establishment of the Enterprise Risk Management (ERM) framework is to ensure alignment of
                  strategy, processes, people, technology and funds in order to identify, evaluate and manage opportunities,
                  uncertainties and threats in a structured and disciplined manner and geared towards achieving strategic
                  objectives.
                 </h6>
                 <h6 class="card-subtitle text-secondary">
                  As part of the reporting requirements contained in the Risk Management Policy and Procedures,
                  Management is mandated to report on a periodic basis to the Board on the extent of implementation of
                  risk management strategies.
                 </h6>
               </div>
                <div class="card-footer text-muted">
                   <p class="font-italic">
                    "
                    The cost of preventing mistakes is generally much less than the cost of correcting mistakes
                    "
                    <p> ~ PMBOK Guide, 5th Edition </p>
                   </p>
                </div>
              </div>
            </blockquote>
             <?php
            }
            ?>

          </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- loader -->
  <div class="loader">
  	<span><img src="assets/img/loader.gif" alt="" /></span>
  </div>
  <!-- end loader -->

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y");?> <a href="https://www.cma.or.ke" target="_blank">Capital Markets Authority</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 2.0
    </div>
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.js"></script>
<script src="assets/js/pace.min.js"></script>

<!--blockui ver 2.70-->
<script src="assets/js/jquery.blockUI.min.js"></script>
<!--sweetalert-->
<script src="assets/js/sweetalert2@8.js"></script>


<!--select2  ver @4.0.12-->
<script src="assets/js/select2.min.js"></script>

<!-- select2 bootstrap theme -->
<script src="assets/js/select2-bootstrap4-theme.js"></script>
<!--jquery autosize-->
<script src="assets/js/jquery.autosize.js"></script>
<!-- Datatables -->
<script src="assets/js/pdfmake.min.js"></script>
<script src="assets/js/vfs_fonts.js"></script>
<script src="assets/js/datatables.min.js"></script>
<!-- datepicker -->
<script src="assets/js/bootstrap-datepicker.min.js"></script>
<!-- maxlength -->
<script src="assets/js/bootstrap-maxlength.js"></script>

<script src="assets/js/highcharts.js"></script>
<script src="assets/js/exporting.js"></script>
<script src="assets/js/offline-exporting.js"></script>

<!-- routes -->
<script src="controllers/routes.js"></script>

<!-- custom -->
<script src="controllers/custom.js"></script>

<!-- forms -->
<script src="controllers/forms.js"></script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5c6d079177e0730ce043d509/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>
</html>
