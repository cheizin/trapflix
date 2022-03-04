

<?php
session_start();
include("../../controllers/setup/connect.php");
if(!isset($_POST['id']))
{
  exit("Please select Stock ");
}

$end_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$_POST['id']."'"));
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">End Product Name: <strong><?php echo $end_product['product_name'];?></strong></li>
     </ol>
</nav>
<br/>

<input type="hidden" name="id" class="id" value="<?php echo $_POST['id'] ;?>">

<div class="row">
  <div class="col-lg-12">
    <ul class="nav nav-tabs nav-fill" role="tablist">
      <li class="nav-item">
        <a class="nav-link product-delivery-tab" data-toggle="tab" href="#product-delivery-tab" role="tab"
            aria-controls="contact" aria-selected="false">
           <i class="fa fa-truck fa-lg"></i> Product Deliveries
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link return-inwards-tab" data-toggle="tab" href="#return-inwards-tab" role="tab"
           aria-selected="false">
           <i class="fa fa-exchange fa-lg"></i> Return Inwards
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link stocks-used-tab" data-toggle="tab" href="#stocks-used-tab" role="tab"
           aria-selected="false">
           <i class="fa fa-shopping-cart fa-lg"></i> Stocks Used
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link delivery-evidence-doc-tab" data-toggle="tab" href="#delivery-evidence-doc-tab" role="tab"
           aria-selected="false">
           <i class="fa fa-paperclip fa-lg"></i> Evidence Documents
        </a>
      </li>
      <!--

      <li class="nav-item">
        <a class="nav-link return-outwards-tab" data-toggle="tab" href="#return-outwards-tab" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="nav-icon fal fa-chalkboard-teacher fa-lg"></i> Return Inwards
        </a>
      </li>
    -->

    </ul>
    <div class="tab-content">

      <div class="tab-pane fade" id="product-delivery-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="return-inwards-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="stocks-used-tab" role="tabpanel"></div>

      <div class="tab-pane fade" id="delivery-evidence-doc-tab" role="tabpanel"></div>



    </div>

  </div>
</div>




<!--PROJECT MODALS -->
