<?php
session_start();
include("../../controllers/setup/connect.php");


$end_delivery = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$_POST['id']."'"));

$get_customer = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM customer WHERE id ='".$end_delivery['customer_id']."'"));

$single_delivery = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$_POST['id']."' ORDER BY ID DESC LIMIT 1"));

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
    Delivery List
    <button class="btn btn-link" style="float:right;"
            data-toggle="modal" data-target="#delivery-stock-modal">
            <i class="fa fa-plus-circle"></i> Deliver <strong><?php echo $end_delivery['product_name'] ;?> </strong> To   <strong><?php

                                   $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$end_delivery['customer_id']."' ORDER BY id "  );
                                   if(mysqli_num_rows($result))
                                   {
                                     while($customer = mysqli_fetch_array($result))
                                     {

                                        echo $customer['customer_name'];

                                     }
                                   }
                               ?>
                               </strong>

    </button>
    </div>
    <div class="card-body table-responsive">

      <?php
   $sql_query1 =  mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$_POST['id']."'");

   $number = 1;
   if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
   {?>

     <table class="table table-striped table-bordered table-hover" id="end-product-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Product Name</td>
           <td>Unit Price</td>

           <td>Delivered Qtt</td>
           <td>Total</td>
           <td>Qtt Remaining</td>

           <td>Start Date</td>
           <td>End Date</td>
           <td>Days Due</td>
            <td>Date Recorded</td>

           <td>Status</td>

         </tr>
       </thead>
       <?php
          $no = 1;
          $sql= mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$_POST['id']."' ORDER BY id DESC");
          while($end_delivery = mysqli_fetch_array($sql))
          {
            ?>
            <tr style="cursor: pointer;">
              <td width="40px"><?php echo $no++ ;?>.

              </td>

              <td>

                <?php

                     $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$end_delivery['end_product_ref']."' ORDER BY id "  );
                     if(mysqli_num_rows($result))
                     {
                       while($project= mysqli_fetch_array($result))
                       {

                          echo $project['product_name'];

                       }
                     }
                     ?>
                </td>
              <td><?php echo number_format($end_delivery['unit_price'],2) ;?></td>
              <td><?php echo $end_delivery['qtt'];?></td>
              <td><?php echo number_format($end_delivery['total'],2) ;?></td>
              <td><?php echo $end_delivery['stock_remaining'];?></td>

              <td>
                <?php

                     $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$end_delivery['end_product_ref']."' ORDER BY id "  );
                     if(mysqli_num_rows($result))
                     {
                       while($project= mysqli_fetch_array($result))
                       {

                          echo $project['start_date'];

                       }
                     }
                     ?>

              <td>
                <?php

                     $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$end_delivery['end_product_ref']."' ORDER BY id "  );
                     if(mysqli_num_rows($result))
                     {
                       while($project= mysqli_fetch_array($result))
                       {

                          echo $project['end_date'];

                       }
                     }
                     ?>


              <td>
                <?php

                $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$end_delivery['end_product_ref']."' ORDER BY id "  );
                if(mysqli_num_rows($result))
                {
                  while($project= mysqli_fetch_array($result))
                  {


                       $todays_date = date('d-M-yy');
                       $date1 = new DateTime($project['end_date']); //inclusive
                      $date2 = new DateTime($todays_date); //exclusive
                      $diff = $date2->diff($date1);
                      echo $diff->format("%a");



                  }
                }



                 ?>


              </td>
              <td>
                  <?php echo $get_customer['time_recorded'] ;?>


                   </td>

                   <td>



                     <?php

                          $result = mysqli_query($dbc, "SELECT * FROM customer_end_delivery WHERE  end_product_ref ='".$_POST['id']."' && status = 'pending_approval'  ORDER BY id "  );
                          if(mysqli_num_rows($result) > 0)
                          {

                              ?>

                               <i class="fa fa-info-circle text-warning" data-toggle="tooltip" title="Product Pending Approval"></i>
                                 <?php

                          }
                          else
                          {
                            ?>
                               <i class="fa fa-check-circle text-success" data-toggle="tooltip" title="Product  Approved"></i>
                              <?php

                          }
                             ?>

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
<strong><i class="fa fa-info-circle"></i> No Deliveries Have been made</strong>
</div>

       <?php
     }
     ?>



    </div>
  </div>
</div>



<!-- stock delivery modal -->
<div class="modal fade" id="delivery-stock-modal" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php
        $end_delivery23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$_POST['id']."'"));
         ?>

        <h5 class="modal-title"><?php echo $end_delivery23['product_name'];?>  delivery to <strong><?php echo $get_customer['customer_name'] ;?></strong>

          <div class="row">
              <div class="col-lg-6 col-xs-12 form-group">
                <label> Quantity Available </label>
               <?php

               $single_delivery2  = mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$_POST['id']."' ORDER BY id ");
               if(mysqli_num_rows($single_delivery2 ) > 0)
               {

            ?>
                         <input type="text"  class="select2 form-control previous_stock_remaining"  value ="<?php echo $single_delivery['stock_remaining'];?>" name="qtt" readonly>
          <?php
                 }

                  else {
                    ?>
                       <input type="text"  class="select2 form-control previous_stock_remaining"  value ="<?php echo $end_delivery['qtt'];?>" name="qtt" readonly >

                    <?php

                  }
                  ?>

                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                  <label>Quantity Remaining</label>
                    <input type="text"  class="select2 form-control new_stock_remaining" name="stock_remaining" readonly>


                  </div>

          </div>

        <span class="font-weight-bold"></h5>

   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
     <span aria-hidden="true">&times;</span>
   </button>
 </div>
 <div class="modal-body">
   <form id="add-delivery-product-form" class="mt-4" enctype="multipart/form-data">
     <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
     <input type="hidden" name="product_name" value="<?php echo $end_delivery23['product_name'];?>">
     <input type="hidden" name="customer_name" value="<?php echo $get_customer['customer_name'] ;?>">

     <input type="hidden" name="add-delivery-product" value="add-delivery-product">
     <input type="hidden"  class="select2 form-control new_stock_remaining" name="stock_remaining">
     <!-- start of row -->
     <div class="row">
   <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
   title="Confirm your password" id="quantity_help2"></i>
   </div>
     <div class="row">

       <div class="col-lg-3 col-xs-12 form-group">
           <label><span class="required">*</span>Unit_price</label>
           <?php

           $single_delivery2  = mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$_POST['id']."' ORDER BY id");
           if(mysqli_num_rows($single_delivery2 ) > 0)
           {

        ?>
                <input type="number" autocomplete="off" class="select2 form-control stock_unit_price" value ="<?php echo $single_delivery['unit_price'];?>"  name="unit_price" readonly>
      <?php
             }

              else {
                ?>
                <input type="number" autocomplete="off" class="select2 form-control stock_unit_price" value ="<?php echo $end_delivery['unit_price'];?>"  name="unit_price" readonly>

                <?php

              }
              ?>

       </div>
         <div class="col-lg-3 col-xs-12 form-group">
             <label><span class="required">*</span>Quantity Delivered</label>

               <input type="number" autocomplete="off" class="select2 form-control stock_qtt"   name="qtt">
         </div>
         <div class="col-lg-3 col-xs-12 form-group">
             <label><span class="required">*</span>Delivery Total Value</label>
               <input type="number" autocomplete="off" class="select2 form-control stock_total"  name="total_stock" readonly>
         </div>
         <div class="col-lg-3 col-xs-12 form-group">
         <label> Delivery Approver</label>
         <select name="delivery_approver[]" class="select2 form-control">
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


         <div class="row">
           <div class="pull-left mt-4">
             <small class="text-muted">Recorded by:-</br> <?php echo $_SESSION['name'];?></small>
           </div>
           </div>


     <div class="row text-center">
         <button type="submit" class="btn btn-primary btn-block btn_submit_total submitting">SUBMIT</button>
     </div>
   </form>
   </div>

 <div class="modal-footer">
   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
 </div>
 </div>
</div>
</div>

<!-- end invoice payment modal  -->
