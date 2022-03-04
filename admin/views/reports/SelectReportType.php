<?php
session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  if(isset($_SESSION['email']))
  {
    ?>
    <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Select the appropriate report</li>
          </ol>
    </nav>
    <div class="row">
        <div class="col-md-12">
        <ul class="ml-4 report-menu nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
           <li class="nav-item has-treeview border-top">
            <a href="#" class="nav-link text-info">

              <p>
                 1. Stock Items
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_stock_items');">
                  <p>(a) All stock Items</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('stocks_in_production');">
                  <p>(b) Stocks Used In production</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('out_of_stock_items');">
                  <p>(c) out of Stock Items</p>
                </a>
              </li>

            </ul>
          </li>


          <li class="nav-item has-treeview border-top">
            <a href="#" class="nav-link text-info">

              <p>
                 2.  End Product:
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_end_product');">
                  <p>(a) All End Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_products_delivered');">
                  <p>(b) Delivered</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_products_in_production');">
                  <p>(c) In Production</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview border-top">
            <a href="#" class="nav-link text-info">

              <p>
                 3.  Invoices:
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_paid_invoices');">
                  <p>(a) Paid Invoices</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_pending_payments');">
                  <p>(b) Pending Payments</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview border-top">
            <a href="#" class="nav-link text-info">

              <p>
                 4.  Profit and Loss Report:
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link text-primary" onclick="ReportType('all_profit_loss');">
                  <p>(a) Generate Report</p>
                </a>
              </li>

            </ul>
          </li>



                </ul>
        </div>
    </div>

    <?php
  }
  else
  {
    echo "unauthorised";
  }
}
else
{
  echo "form not submitted";
}


 ?>
