<?php
session_start();
include("../../controllers/setup/connect.php");

?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">End Product Delivery Approvals</li>
  </ol>
</nav>

<ul class="nav nav-fill nav-pills mb-3" id="pills-tab" role="tablist">

  <li class="nav-item">
    <a class="nav-link delivery_pending_approval_tab" data-toggle="pill" href="#delivery_pending_approval_tab" role="tab"  aria-selected="false"><span class="new-edited-badge"></span> Delivery Pending Approval</a>
  </li>
  <li class="nav-item">
    <a class="nav-link approved_delivery_tab" data-toggle="pill" href="#approved_deliveries_tab" role="tab" aria-selected="false">Approved Deliveries</a>
  </li>
  <li class="nav-item">
    <a class="nav-link rejected_delivery_tab" data-toggle="pill" href="#rejected_deliveries_tab" role="tab" aria-selected="false">Rejected Deliveries</a>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="delivery-approvals-data" role="tabpanel">

  </div>
  </div>
</div>
