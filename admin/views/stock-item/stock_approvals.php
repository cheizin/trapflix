<?php
session_start();
include("../../controllers/setup/connect.php");

?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Stock Approvals</li>
  </ol>
</nav>

<ul class="nav nav-fill nav-pills mb-3" id="pills-tab" role="tablist">

  <li class="nav-item">
    <a class="nav-link pending_approval_tab" data-toggle="pill" href="#pending_approval_new_edited_tab" role="tab"  aria-selected="false"><span class="new-edited-badge"></span> New Stocks Pending Approval</a>
  </li>
  <li class="nav-item">
    <a class="nav-link approved_stocks_tab" data-toggle="pill" href="#approved_risks_tab" role="tab" aria-selected="false">Approved Stocks</a>
  </li>
  <li class="nav-item">
    <a class="nav-link rejected_stocks_tab" data-toggle="pill" href="#rejected_risks_tab" role="tab" aria-selected="false">Rejected Stocks</a>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="risk-approvals-data" role="tabpanel">

  </div>
  </div>
</div>
