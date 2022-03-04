

<?php
session_start();
include("../../controllers/setup/connect.php");
if(!isset($_POST['reference_no']))
{
  exit("Please select Stock ");
}

$stock = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no ='".$_POST['reference_no']."'"));
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
   <li class="breadcrumb-item active" aria-current="page">Stock Name:  <b>   <strong><?php echo $stock ['item_name'];?></strong></li></b>
     </ol>
</nav>
<br/>

<input type="hidden" name="stock-id" class="stock-id" value="<?php echo $stock ['reference_no'] ;?>">

<div class="row">
  <div class="col-lg-12">
    <ul class="nav nav-tabs nav-fill" role="tablist">
      <li class="nav-item">
        <a class="nav-link stock-list-payments-tab" data-toggle="tab" href="#stock-list-payments-tab" role="tab"
            aria-controls="contact" aria-selected="false">
           <i class="fad fa-money-check-alt fa-lg"></i>  Payments
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link end-product-tab" data-toggle="tab" href="#end-product-tab" role="tab"
           aria-selected="false">
           <i class="fa fa-shopping-cart fa-lg"></i> Stocks Request
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link stocks-returns-tab" data-toggle="tab" href="#stocks-returns-tab" role="tab"
           aria-selected="false">
           <i class="fa fa-exchange fa-lg"></i> Stocks Returns
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link evidence-doc-tab" data-toggle="tab" href="#evidence-doc-tab" role="tab"
           aria-selected="false">
           <i class="fa fa-paperclip fa-lg"></i> Evidence Documents
        </a>
      </li>
      <!--

      <li class="nav-item">
        <a class="nav-link return-outwards-tab" data-toggle="tab" href="#return-outwards-tab" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="nav-icon fal fa-chalkboard-teacher fa-lg"></i> Return Outwards
        </a>
      </li>
    -->

    </ul>
    <div class="tab-content">

      <div class="tab-pane fade" id="stock-list-payments-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="end-product-tab" role="tabpanel"></div>

        <div class="tab-pane fade" id="stocks-returns-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="evidence-doc-tab" role="tabpanel"></div>


    </div>

  </div>
</div>




<!--PROJECT MODALS -->
