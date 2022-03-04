
<?php
session_start();
include("../../controllers/setup/connect.php");
?>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-light">Deliveries</div>
      <div class="card-body table-responsive">
        <?php
        $fetch_photo = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE email='".$_SESSION['email']."'"));
        $fetch_approver = mysqli_fetch_array(mysqli_query($dbc,"SELECT * from delivery_approvers
                                            WHERE delivery_approver ='".$_SESSION['email']."'
                                                        ORDER BY id DESC"));

        //  $fetch_stock = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no='".$fetch_approver['stock_id']."'"));
          $fetch_end_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$fetch_approver['product_id']."'"));

    //    $fetch_stock_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no ='".$fetch_approver['stock_id']."'"));

  //fetch department email

  $updated_risks = mysqli_query($dbc,"SELECT * from customer_end_delivery
      WHERE status = 'rejected' && end_product_ref IN
          (SELECT product_id from delivery_approvers WHERE delivery_approver ='".$_SESSION['email']."')
                GROUP BY end_product_ref DESC"  );



          //  $updated_risks2 = mysqli_query($dbc,"SELECT * from invoice_received
          //      WHERE reference_no ='".$fetch_approver['stock_id']."'
            //            ORDER BY id DESC"  );
          $count_status_updated = mysqli_num_rows($updated_risks);

        $number = 1;
      //  if($total_rows = mysqli_num_rows($sql_query) > 0)
      //  {?>
          <table class="table table-hover table-striped" id="new-risk-approval-table" style="width:100%">
            <thead>
              <tr>
                <td>NO</td>
                <td>Product Name</td>
                <td>Created By</td>
                <td>Date Submitted</td>

              </tr>
            </thead>
            <?php
              while($risk = mysqli_fetch_array($updated_risks))
              {
                ?>
            <tr id="new_row-<?php echo $risk['id'] ;?>">
              <td><?php echo $number++;?></td>
              <td>
                <?php

                   $result2 = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$risk['end_product_ref']."' ORDER BY id "));

                                     ?>

                                     <a class="" href="#" data-toggle="modal" data-target="#delivery-details-modal-<?php echo $risk['id'];?>"
                                       title="Click on <?php echo $result2['product_name'];?> to view more details">
                                     <span class="text-primary" style="cursor:pointer;"><?php echo $result2['product_name'];?> </span>
                                     </a>
                                     <!-- stock details Modal -->


                                     <div class="modal fade" id="delivery-details-modal-<?php echo $risk['id'];?>" role="dialog">
                                       <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                         <div class="modal-content">
                                           <div class="modal-header">
                                             <h5 class="modal-title" id="exampleModalLongTitle">End Product Delivery To

                                                 <?php

                                                      $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$result2['customer_id']."' ORDER BY id "  );
                                                      if(mysqli_num_rows($result))
                                                      {
                                                        while($supplier = mysqli_fetch_array($result))
                                                        {

                                                           echo $supplier['customer_name'];

                                                        }
                                                      }
                                                      ?>


                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                               <span aria-hidden="true">&times;</span>
                                             </button>
                                           </div>
                                           <div class="modal-body">
                                              <div class="row border-bottom mx-3">
                                             <div class="col-lg-4 col-xs-12 form-group">
                                                 <label for="item_name"><span class="required">*</span>Product Name</label>

                                                   <input type="text" value ="<?php echo $result2['product_name'];?>"  class="select2 form-control" name="qtt" readonly>

                                             </div>

                                         </div>
                                         <!-- start row project timelines -->


                                             <div class="row border-bottom mx-3">


                                               <div class="col-lg-3 col-xs-12 form-group">

                                                   <label for="unit_price"><span class="required">*</span>Unit Price</label><br/>

                                                 <input type="text" value ="<?php echo $risk['unit_price'];?>"  class="select2 form-control" name="qtt" readonly>

                                               </div>
                                               <div class="col-lg-3 col-xs-12 form-group">

                                                   <label for="qtt"><span class="required">*</span>Delivered Quantity</label><br/>

                                                     <input type="text" value ="<?php echo $risk['qtt'];?>"  class="select2 form-control" name="qtt" readonly>
                                               </div>
                                               <div class="col-lg-3 col-xs-12 form-group">

                                                   <label for="qtt"><span class="required">*</span>Qtt Remaining</label><br/>

                                                     <input type="text" value ="     <?php

                                                               $result = mysqli_query($dbc, "SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$risk['end_product_ref']."' ORDER BY id DESC LIMIT 1"  );
                                                               if(mysqli_num_rows($result))
                                                               {
                                                                 while($product_name = mysqli_fetch_array($result))
                                                                 {

                                                                    echo $product_name['stock_remaining'] ;


                                                                 }
                                                               }
                                                               ?>"  class="select2 form-control" name="qtt" readonly>
                                               </div>

                                               <div class="col-lg-3 col-xs-12 form-group">
                                                   <label>Total Value</label>
                                                          <input type="text" value ="<?php echo $risk['total'];?>"  class="select2 form-control" name="qtt" readonly>

                                               </div>

                                               </div>


                                             </div>

                                           <div class="modal-footer">
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                           </div>
                                           </div>
                                         </div>
                                         </div>

                                     <!-- end invoice payment modal  -->


              </td>
              <td>  <?php  echo $risk['recorded_by'];  ?>
                             </td>
              <td> <?php  echo $risk['time_recorded'];   ?></td>



            </tr>
            <?php
            }
          //}
            ?>
          </table>
      </div>
  </div>
</div>
