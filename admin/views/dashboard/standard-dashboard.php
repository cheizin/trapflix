<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }
  $p = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM years ORDER BY id DESC LIMIT 1"));
  $select_period = $p['period'];
  $select_quarter = $p['quarter'];
   ?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Risk Management Dashboard</li>
     </ol>
</nav>

<ul class="nav nav-fill nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#risks_heatmap_tab" role="tab"><i class="far fa-border-none text-danger"></i> Risks Heatmap</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#opportunites_heatmap_tab" role="tab"><i class="far fa-border-none text-primary"></i>  Opportunities Heatmap</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#update_monitoring_tab" role="tab"><i class="fas fa-calendar-edit"></i> Update Monitoring</a>
  </li>
  <li class="nav-item">
    <a class="nav-link standard_power_bi_tab" data-toggle="tab" href="#powerBi_tab" role="tab"> <i class="far fa-chart-network"></i> Data Visualisation</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#analysis_tab" role="tab"><i class="fad fa-analytics"></i> Analysis</a>
  </li>
</ul>


<div class="tab-content">
  <div class="tab-pane fade show active" id="risks_heatmap_tab" role="tabpanel"><?php include("StandardRisksHeatmap.php");?></div>
  <div class="tab-pane fade in" id="opportunites_heatmap_tab" role="tabpanel"><?php include("CorporateOpportunitiesHeatmap.php");?></div>
  <div class="tab-pane fade in" id="update_monitoring_tab" role="tabpanel"><?php include("UpdateMonitoring.php");?></div>
  <div class="tab-pane fade in" id="powerBi_tab" role="tabpanel"></div>
  <div class="tab-pane fade in" id="analysis_tab" role="tabpanel">

    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#all_risks_tab" role="tab">Active Risks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#new_risks_tab" role="tab"><i class="fas fa-layer-plus faa-falling animated text-warning"></i>  New Risks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#upgraded_risks_tab" role="tab"><i class="fa fa-arrow-up text-danger"></i>  Upgraded Risks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#static_risks_tab" role="tab"><i class="fa fa-arrows-h text-warning"></i> Static Risks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#downgraded_risks_tab" role="tab"> <i class="fa fa-arrow-down text-success"></i> Downgraded Risks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#closed_risks_tab" role="tab"><i class="fad fa-trash"></i> Closed Risks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#all_activities_tab" role="tab"><i class="fa fa-tasks"></i>  All Activities</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#closed_activities_tab" role="tab"><i class="fad fa-trash"></i> Closed Activities</a>
      </li>
    </ul>

    <div class="tab-content">
       <div id="all_risks_tab" class="tab-pane fade in show active">
          <?php include("analysis_all_risks.php");?>
       </div>
       <div id="new_risks_tab" class="tab-pane fade">
          <?php include("analysis_new_risks.php");?>
       </div>
       <div id="upgraded_risks_tab" class="tab-pane fade">
          <?php include("analysis_upgraded_risks.php");?>
       </div>
       <div id="static_risks_tab" class="tab-pane fade">
          <?php include("analysis_static_risks.php") ;?>
       </div>
       <div id="downgraded_risks_tab" class="tab-pane fade">
          <?php include("analysis_downgraded_risks.php");?>
       </div>
       <div id="all_activities_tab" class="tab-pane fade">
          <?php include("analysis_all_activities.php");?>
       </div>
       <div id="closed_risks_tab" class="tab-pane fade">
          <?php include("analysis_closed_risks.php");?>
       </div>
       <div id="closed_activities_tab" class="tab-pane fade">
          <?php include("analysis_closed_activities.php");?>
       </div>
    </div>

  </div>

</div>
