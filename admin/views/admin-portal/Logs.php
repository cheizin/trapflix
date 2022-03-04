<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Admin Portal : Logs</li>
     </ol>
</nav>

<ul class="nav nav-fill nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link activity_logs_tab" data-toggle="pill" href="#sign_in_logs_tab" role="tab"  aria-selected="true">Activity Logs</a>
  </li>
  <li class="nav-item">
    <a class="nav-link page_logs_tab" data-toggle="pill" href="#sign_in_logs_tab" role="tab"  aria-selected="true">Page Logs</a>
  </li>
  <li class="nav-item">
    <a class="nav-link sign_in_logs_tab" data-toggle="pill" href="#sign_in_logs_tab" role="tab"  aria-selected="true">Sign in Logs</a>
  </li>
  <li class="nav-item">
    <a class="nav-link mail_logs_tab" data-toggle="pill" href="#mail_logs_tab" role="tab"  aria-selected="false">Mail Logs</a>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="logs-data" role="tabpanel">

  </div>
</div>
