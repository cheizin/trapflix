<?php
session_start();
include("../../controllers/setup/connect.php");
$stock = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no='".$_POST['reference_no']."'"));

$end_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE reference_no='".$_POST['reference_no']."'"));

$invoice_received = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$_POST['reference_no']."'"));

$single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$invoice_received['reference_no']."' ORDER BY ID DESC LIMIT 1"));


    /*       $results = mysqli_query($dbc,"SELECT sum(total_cost) as tot FROM phpc_add_game ORDER BY id ASC");
           $rows = mysqli_fetch_assoc($results);

           $var1 = $rows['tot'];
           $var12 = $get_sub_amount['amount'];

           $var_difference = ($var1 - $var12);
           */

?>
<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Return Outwards
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-end-product-modal">
              <i class="fa fa-plus-circle"></i> Add End Product for <?php echo $stock['item_name'] ;?>
      </button>
    </div>
    <div class="card-body table-responsive">

      <?php
   $sql_query1 =  mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$stock['reference_no']."'");

   $number = 1;
   if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
   {?>

     <table class="table table-striped table-bordered table-hover" id="end-product-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Product Name</td>
           <td>Unit Price</td>
           <td>Quantity Used</td>
           <td>Quantity Remaining</td>
           <td>Total</td>
           <td>Requested By</td>
           <td>Date requested</td>

            <td>Project Name</td>

           <td>Sample Image</td>

         </tr>
       </thead>
       <?php
          $no = 1;
          $sql= mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$invoice_received['reference_no']."' ");
          while($product = mysqli_fetch_array($sql))
          {
            ?>
            <tr style="cursor: pointer;">
              <td width="40px"><?php echo $no++ ;?>.

              </td>

              <td>
                <?php

                     $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$product['product_name']."' "  );
                     if(mysqli_num_rows($result))
                     {
                       while($product_name = mysqli_fetch_array($result))
                       {

                          echo $product_name['product_name'];

                       }
                     }
                     ?>


               </td>
               <td>
                 <?php

                      $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$product['product_name']."' "  );
                      if(mysqli_num_rows($result))
                      {
                        while($product_name = mysqli_fetch_array($result))
                        {

                           echo $product_name['unit_price'];

                        }
                      }
                      ?>


                </td>

              <td><?php echo $product['qtt'];?></td>
              <td><?php echo $product['stock_remaining'];?></td>

              <td><?php echo $product['total'];?></td>
              <td><?php echo $product['recorded_by'];?></td>
              <td><?php echo $product['time_recorded'];?></td>

                 <td>

                   <?php

                        $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$product['product_name']."'  "  );
                        if(mysqli_num_rows($result))
                        {
                          while($product_name = mysqli_fetch_array($result))
                          {

                             $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$product_name['customer_id']."' "  );
                             if(mysqli_num_rows($result))
                             {
                               while($project= mysqli_fetch_array($result))
                               {

                                  echo $project['customer_name'];

                               }
                             }

                          }
                        }
                        ?>


                    </td>

              <td>          <?php
                        $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE Email ='".$_SESSION['email']."'"));
                        ?>
                          <img width="30%" height="10%"src="assets/img/<?php echo $profile_pic['emp_photo']; ?>"  alt="User Image">

                         </td>

            </tr>
            <?php
          }
        ?>
     </table>

     <?php
     }
     else
     {
           ?>
         <br/>
<div class="alert alert-info">
<strong><i class="fa fa-info-circle"></i> No Stock Returns recorded</strong>
</div>

       <?php
     }
     ?>



    </div>
  </div>
</div>

<!-- start add end product modal -->
<div class="modal fade" id="add-end-product-modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert alert-primary">

        <h5 class="modal-title">Stock <strong><?php echo $stock['item_name'] ;?></strong> Request for End Product

            <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                  <label>  Stock Available </label>
                 <?php

                 $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$_POST['reference_no']."' ORDER BY id");
                 if(mysqli_num_rows($Proj_phase) > 0)
                 {

              ?>
                           <input type="text"  class="select2 form-control previous_stock_remaining"  value ="<?php echo $single_product['stock_remaining'];?>" name="qtt" readonly>
            <?php
                   }

                    else {
                      ?>
                         <input type="text"  class="select2 form-control previous_stock_remaining"  value ="<?php echo $invoice_received['qtt'];?>" name="qtt" readonly >

                      <?php

                    }
                    ?>

                  </div>
                  <div class="col-lg-6 col-xs-12 form-group">
                    <label>Stock Remaining</label>
                      <input type="text"  class="select2 form-control new_stock_remaining" name="stock_remaining" readonly>
                    </div>

            </div>

           <span class="font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-single-end-product-form">
          <input type="hidden" name="reference_no" value="<?php echo $stock['reference_no'];?>">
          <input type="hidden" name="add-single-end-product" value="add-single-end-product">
          <input type="hidden"  class="select2 form-control new_stock_remaining" name="stock_remaining">
          <!-- start of row -->
          <div class="row">

              <div class="col-lg-6 col-xs-12 form-group">
                  <label><span class="required">*</span>End Product Name</label>
                  <?php
                  $result = mysqli_query($dbc, "SELECT * FROM end_product");
                  echo '
                  <select name="product_name" data-tags="true" class="select2 form-control" data-placeholder="Select Product Name" "required>
                  <option></option>';
                  while($row = mysqli_fetch_array($result)) {
                      echo '<option value="'.$row['id'].'">'.$row['product_name']."</option>";
                  }
                  echo '</select>';
                  ?>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                  <label><span class="required">*</span>Quantity Required<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                  title="Confirm your password" id="quantity_help"></i></label>

                    <input type="number" autocomplete="off" class="select2 form-control stock_qtt"   name="qtt">
              </div>

              </div>
              <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                    <label><span class="required">*</span>Unit_price</label>
                      <input type="number" autocomplete="off" class="select2 form-control stock_unit_price" value ="<?php echo $invoice_received['unit_price'];?>"  name="unit_price" readonly>
                </div>

              <div class="col-lg-6 col-xs-12 form-group">
                  <label><span class="required">*</span>Stock Total Value</label>
                    <input type="number" autocomplete="off" class="select2 form-control stock_total"  name="total_stock" readonly>
              </div>

              <div class="col-lg-6 col-xs-12 form-group">
                <label>  Grand Total </label>
                       <input type="text"  class="select2 form-control grand_total"  name="grand_total" readonly >

                      </div>
              <div class="col-lg-6 col-xs-12 form-group">
                  <label><span class="required">*</span>Color code</label>
                    <input type="number" autocomplete="off" class="select2 form-control order_level_color"  name="order_level_color" readonly>
              </div>
              </div>
              <div class="row">

                <div class="pull-left mt-4">
                  <small class="text-muted">Recorded by:-</br> <?php echo $_SESSION['name'];?></small>
                </div>
                </div>


          <div class="row text-center">
              <button type="submit" class="btn btn-primary btn-block btn_submit_total">SUBMIT</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end add project lesson learnt modal -->
