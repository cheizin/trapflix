<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");

/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Stock Mangement</li>
     </ol>
</nav>

<div class="row">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        stock List

        <button class="btn btn-link" style="float:right;"
                data-toggle="modal" data-target="#add-stock-modal">
                <i class="fa fa-plus-circle"></i> Add New Stock Item
        </button>
      </div>
      <div class="card-body table-responsive">

        <?php
        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM stock_item ORDER BY id ");

        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

       <table class="table table-striped table-bordered table-hover" id="stock-list-computation-table" style="width:100%">
         <thead>
           <tr>
             <td>#</td>
             <td>Stock Name</td>
             <td>Stock Description</td>
             <td>Category</td>
             <td>Supplier</td>
              <td>Quantity Available</td>
             <td>Unit price</td>
               <td>Reorder Level</td>
             <td>Total Price</td>
             <td>Status</td>
          <!--   <td>Status</td> -->
           </tr>
         </thead>
         <?php
         $no = 1;
          $sql = mysqli_query($dbc,"SELECT * FROM stock_item  ORDER BY id DESC");
          while($row = mysqli_fetch_array($sql)){
          ?>
         <tr style="cursor: pointer;">
           <td width="50px"> <?php echo $no++;?>.

           </td>
           <td onclick="ViewStock('<?php echo $row['reference_no'];?>');" title="Click <?php echo $row['item_name'] ;?> to view more Details">
                <span class="text-primary" style="cursor:pointer;"><?php echo $row['item_name'] ;?></span>
           </td>
           <td><?php echo $row['item_description'] ;?></td>
           <td>
             <?php

                       echo $row['category_id'];

                  ?>


                </td>
                <td>
                  <?php  echo $row['supplier_id'];   ?>

                     </td>
                     <td>


                            <?php

                            $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id");
                            if(mysqli_num_rows($Proj_phase) > 0)
                            {

                              $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));

                          echo $single_product['stock_remaining'];
                            ?>

                          <?php

                              }

                               else {

                                      $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id "  );
                                      if(mysqli_num_rows($result))
                                      {
                                        while($product_qtt = mysqli_fetch_array($result))
                                        {

                                           echo $product_qtt['qtt'];

                                        }
                                      }
                                      ?>
                                 <?php

                               }
                               ?>

                          </td>




                          <td>
                            <?php

                                 $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"  );
                                 if(mysqli_num_rows($result))
                                 {
                                   while($product_unit = mysqli_fetch_array($result))
                                   {
                                     ?>


                                     <?php echo number_format($product_unit['unit_price'],2) ;


                                   }
                                 }
                                 ?>


                               </td>
                               <?php


                                     $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id");
                                     if(mysqli_num_rows($Proj_phase) > 0)
                                     {

     $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));
      $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id  DESC LIMIT 1"));

                                  $order_level2 = $single_product23['stock_order_level'];
                                  $order_level23 = $single_product['stock_remaining'];

                                  if($order_level2 < $order_level23)
                                  {

                                  $sql_statu2 = "two";

                                }
                                else if($order_level2 == $order_level23)
                                {
                                  $sql_statu2 = "four";
                              }
                              else {
                                $sql_statu2 = "five";
                              }


                                     ?>

                                   <?php

                                       }

                                        else {

                              //   $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));
                                $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id  DESC LIMIT 1"));

                                  $order_level2 = $single_product23['stock_order_level'];
                                            $order_level23 = $single_product23['qtt'];

                if($order_level2 < $order_level23)
                  {

                            $sql_statu2 = "two";

                            }
                  else if($order_level2 == $order_level23)
                    {
                      $sql_statu2 = "four";
                      }
                        else {
                          $sql_statu2 = "five";
                    }

                       ?>
                         <?php

                    }

                            //   $order_level1 = $result45['stock_order_level'];
                               //$order_level2 = $result456['stock_remaining'];

                           ?>

                            <td class="<?php echo $sql_statu2;?>" width="40px;">
                                  <?php

                                       $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1 "  );
                                       if(mysqli_num_rows($result))
                                       {
                                         while($product_unit = mysqli_fetch_array($result))
                                         {

                                            echo $product_unit['stock_order_level'];

                                         }
                                       }
                                       ?>



                                     </td>
                               <td>
                                 <?php

                                      $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1 "  );
                                      if(mysqli_num_rows($result))
                                      {
                                        while($product_total = mysqli_fetch_array($result))
                                        {


                                            $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id");
                                            if(mysqli_num_rows($Proj_phase) > 0)
                                            {

                                              $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));


                                              $invoice1234 = $single_product['stock_remaining'] * $product_total['unit_price'];

                                              echo number_format($invoice1234,2) ;
                                              }
                                              else {
                                                $invoice12 = $product_total['qtt'] * $product_total['unit_price'];

                                                echo number_format($invoice12,2) ;
                                                  //echo number_format($get_remaining['total'],2) ;
                                              }



                                        }
                                      }
                                      ?>



                                    </td>
           <td>

             <?php

                  $result = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$row['reference_no']."' && status = 'pending_approval'  ORDER BY id DESC LIMIT 1"  );
                  if(mysqli_num_rows($result) > 0)
                  {
                    while($product_unit = mysqli_fetch_array($result))
                    {
                      ?>

                       <i class="fa fa-info-circle text-warning" data-toggle="tooltip" title="Stock <?php echo $row['item_name'] ;?> Pending Approval"></i>
                         <?php
                    }
                  }
                  else
                  {
                    ?>
                       <i class="fa fa-check-circle text-success" data-toggle="tooltip" title="Stock <?php echo $row['item_name'] ;?> Approved"></i>
                      <?php

                  }
                     ?>


                      </td>


         </tr>
         <?php
            }
          ?>

          <tfoot style="background:silver;">
                  <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                        <th></th>

                  </tr>
              </tfoot>
       </table>
      </div>
    </div>
  </div>
</div>

<?php
}
else
{
      ?>
    <br/>
<div class="alert alert-info">
<strong><i class="fa fa-info-circle"></i> No Stocks recorded</strong>
</div>

  <?php
}
?>

<?php
//fetch last reference number from database and auto increment it
$reference_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT MAX(reference_no) AS ref_no FROM stock_item "));
//auto increment the fetched record
$reference_row = $reference_row['ref_no'];
//add programm name prefix, plus the auto incremented value
$reference_row = $reference_row+1;
?>

<!-- add stock modal -->

<div class="modal fade" id="add-stock-modal">
<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">

      <h5 class="modal-title">Add New stock Item </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form id="add-stock-form" class="mt-4" enctype="multipart/form-data">
        <input type="hidden" value="add-stock" name="add-stock">
        <input type="hidden" name="reference_no" id="reference_no" class="form-control" value="<?php echo $reference_row;?>" readonly="readonly">
        <div class="row border-bottom mx-3">

            <div class="col-lg-4 col-xs-12 form-group">
                <label for="item_name"><span class="required">*</span>Stock Name</label>

                  <textarea name="item_name" class="form-control" required></textarea>

            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label for="item_description"><span class="required">*</span>Stock Description</label>

                  <textarea name="item_description" class="form-control" required></textarea>
            </div>

            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Stock Category</label>
                <?php
                $result = mysqli_query($dbc, "SELECT * FROM product_category ORDER BY category_name ASC");
                echo '
                <select name="category" data-tags="true" class="select2 form-control" data-placeholder="Select product category" required>
                <option></option>';
                while($row = mysqli_fetch_array($result)) {
                    echo '<option value="'.$row['category_name'].'">'.$row['category_name']."</option>";
                }
                echo '</select>';
                ?>
            </div>


        </div>
        <!-- start row project timelines -->

          <div class="row border-bottom mx-4">

            <div class="col-lg-3 col-xs-12 form-group">
              <label for="category"><span class="required">*</span>Payment Type</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM payment_gateway");
              echo '
              <select name="payment_type" class="select2 form-control" data-placeholder="Select product category" required>
              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                // we're sending the strategic objective id to the db
                  echo '<option value="'.$row['id'].'">'.$row['payment_type']."</option>";
              }
              echo '</select>';
              ?>
            </div>
            <div class="col-lg-3 col-xs-12 form-group">

                <label for="invoice_received_id"><span class="required">*</span>Invoice Number</label><br/>
                <input type="text" class="form-control" name="invoice_received_id" required>
            </div>
            <div class="col-lg-3 col-xs-12 form-group">
              <label for="supplier_name"><span class="required">*</span>Supplier</label>
              <?php
              $result = mysqli_query($dbc, "SELECT * FROM supplier");
              echo '
              <select name="supplier_name" data-tags="true"  class="select2 form-control" data-placeholder="Select stock supplier" required>
              <option></option>';
              while($row = mysqli_fetch_array($result)) {
                // we're sending the strategic objective id to the db
                  echo '<option value="'.$row['supplier_name'].'">'.$row['supplier_name']."</option>";
              }
              echo '</select>';
              ?>
            </div>

              <div class="col-lg-3 col-xs-12 form-group">
              <label><span class="required">*</span> Stock Approver</label>
              <select name="stock_approver[]" class="select2 form-control" required multiple="multiple">
                  <?php
                    $sql_query = mysqli_query($dbc,"SELECT * FROM staff_users WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                    while($row = mysqli_fetch_array($sql_query))
                    {
                      ?>
                        <option value="<?php echo $row['Email'];?>"><?php echo $row['Name'];?></option>

                      <?php
                    }
                   ?>
              </select>
            </div>
            </div>

            <div class="row border-bottom mx-3">


              <div class="col-lg-3 col-xs-12 form-group">

                  <label for="unit_price"><span class="required">*</span>Unit Price</label><br/>

                  <input type="number" autocomplete="off" class="select2 form-control unit_price" name="unit_price"  required>

              </div>
              <div class="col-lg-3 col-xs-12 form-group">

                  <label for="qtt"><span class="required">*</span>Quantity</label><br/>

                <input type="number" autocomplete="off" class="select2 form-control qtt" name="qtt" required>
              </div>
              <div class="col-lg-3 col-xs-12 form-group">

                  <label for="stock_order_level"><span class="required">*</span>Re-Order Level</label><br/>

                <input type="number" autocomplete="off" class="select2 form-control" name="stock_order_level" required>
              </div>
              <div class="col-lg-3 col-xs-12 form-group">
                  <label>Total Value</label>
                    <input type="Currency" autocomplete="off" class="select2 form-control total" name="total" readonly>

              </div>

              </div>
              <div class="row border-bottom mx-2">


                  <div class="pull-left mt-4">
                    <small class="text-muted">Recorded by:- <?php echo $_SESSION['name'];?></small>
                  </div>
                    </div>


        <!-- end row project timelines -->





              <!-- start row button -->
        <div class="row">
          <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">SUBMIT</button>
          </div>
        </div>

              <!-- end row button -->
      </form>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
  </div>
<!-- end of add project modal -->
