
<?php
session_start();
include("../../controllers/setup/connect.php");
?>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-light">New Stock</div>
      <div class="card-body table-responsive">
  <?php
  $fetch_photo = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE email='".$_SESSION['email']."'"));
  $fetch_approver = mysqli_fetch_array(mysqli_query($dbc,"SELECT * from stock_approvers
                                      WHERE stock_approver ='".$_SESSION['email']."'
                                                  ORDER BY id DESC"));

    $fetch_stock = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no='".$fetch_approver['stock_id']."'"));
    $fetch_stock2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no='".$fetch_approver['stock_id']."'"));

    //    $fetch_stock_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no ='".$fetch_approver['stock_id']."'"));

  //fetch department email

            $updated_risks = mysqli_query($dbc,"SELECT * from stock_item
                WHERE status = 'pending_approval' && reference_no IN
                    (SELECT stock_id from stock_approvers WHERE stock_approver ='".$_SESSION['email']."')
                          ORDER BY id DESC"  );



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
                <td>Stock Name</td>
                <td>Created By</td>
                <td>Date Submitted</td>
                <td>Action</td>
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

                                 $result2 = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$risk['reference_no']."' ORDER BY id "));
                 $result23 = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$risk['reference_no']."' ORDER BY id "));
                                     ?>

                                     <a class="" href="#" data-toggle="modal" data-target="#stock-details-modal-<?php echo $risk['id'];?>"
                                       title="Click on <?php echo $result2['item_name'];?> to view more details">
                                     <span class="text-primary" style="cursor:pointer;"><?php echo $result2['item_name'];?> </span>
                                     </a>
                                     <!-- stock details Modal -->


                                     <div class="modal fade" id="stock-details-modal-<?php echo $risk['id'];?>" role="dialog">
                                       <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                         <div class="modal-content">
                                           <div class="modal-header">
                                             <h5 class="modal-title" id="exampleModalLongTitle">Updated Stock Details From

                                                 <?php

                                                      $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE id ='".$risk['supplier_id']."' ORDER BY id "  );
                                                      if(mysqli_num_rows($result))
                                                      {
                                                        while($supplier = mysqli_fetch_array($result))
                                                        {

                                                           echo $supplier['supplier_name'];

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
                                                 <label for="item_name"><span class="required">*</span>Stock Name</label>
                                                 <?php
                                                 $stock2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no='".$risk['reference_no']."'"));
                                                 ?>

                                                   <input type="text" value ="<?php echo $stock2['item_name'];?>"  class="select2 form-control" name="qtt" readonly>

                                             </div>
                                             <div class="col-lg-4 col-xs-12 form-group">
                                                 <label for="item_description"><span class="required">*</span>Stock Description</label>

                                                 <?php
                                                 $stock2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no='".$risk['reference_no']."'"));
                                                 ?>

                                                   <input type="text" value ="<?php echo $stock2['item_description'];?>"  class="select2 form-control" name="qtt" readonly>
                                             </div>



                                         </div>
                                         <!-- start row project timelines -->


                                             <div class="row border-bottom mx-3">


                                               <div class="col-lg-3 col-xs-12 form-group">

                                                   <label for="unit_price"><span class="required">*</span>Unit Price</label><br/>

                                                 <input type="text" value ="<?php echo $result23['unit_price'];?>"  class="select2 form-control" name="qtt" readonly>

                                               </div>
                                               <div class="col-lg-3 col-xs-12 form-group">

                                                   <label for="qtt"><span class="required">*</span>Quantity</label><br/>

                                                     <input type="text" value ="<?php echo $result23['qtt'];?>"  class="select2 form-control" name="qtt" readonly>
                                               </div>
                                               <div class="col-lg-3 col-xs-12 form-group">

                                                   <label for="stock_order_level"><span class="required">*</span>Order Level</label><br/>

                                                   <input type="text" value ="<?php echo $result23['stock_order_level'];?>"  class="select2 form-control" name="qtt" readonly>
                                               </div>
                                               <div class="col-lg-3 col-xs-12 form-group">
                                                   <label>Total Value</label>
                                                          <input type="text" value ="<?php echo $result23['total'];?>"  class="select2 form-control" name="qtt" readonly>

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
              <td>  <?php

                               $result = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$risk['reference_no']."' ORDER BY id DESC"  );
                               if(mysqli_num_rows($result))
                               {
                                 while($product_unit = mysqli_fetch_array($result))
                                 {

                                    echo $product_unit['recorded_by'];

                                 }
                               }
                               ?>
                             </td>
              <td> <?php

                               $result = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$risk['reference_no']."' ORDER BY id DESC"  );
                               if(mysqli_num_rows($result))
                               {
                                 while($product_unit = mysqli_fetch_array($result))
                                 {

                                    echo $product_unit['time_recorded'];

                                 }
                               }
                               ?></td>
              <td>

                   <div class="col-md-4 mb-4">
                     <form id="approve-new-stock-form-<?php echo $risk['id'];?>">

                       <input type="hidden" name="new_approval_value" id="new_approval_value-<?php echo $risk['id'] ;?>" value="approved">

                       <input type="hidden" name="new_reference_no" value="<?php echo $risk['reference_no'];?>" id="new_reference_no-<?php echo $risk['id'];?>">

                       <button class="btn btn-success" style="width:150px;" data-toggle="tooltip" title="Please click the stock Name for more
                          description before approving " id="approve-new-risk-button-<?php echo $risk['id'] ;?>"
                          onclick="approveNewStock(<?php echo $risk['id'];?>)"><i class="far fa-check-circle"></i> Approve</button>
                     </form>
                    </div>
                     <div class="col-xs-2"></div>
                     <div class="col-md-4">
                       <form id="approve-new-stock-form-<?php echo $risk['id'];?>">
                         <input type="hidden" name="new_approval_value" id="new_approval_value-<?php echo $risk['id'] ;?>" value="rejected">

                         <input type="hidden" name="new_reference_no" value="<?php echo $risk['reference_no'];?>" id="new_reference_no-<?php echo $risk['reference_no'];?>">

                         <button class="btn btn-danger" style="width:150px;" data-toggle="tooltip" title="Please click the stock Name for more
                            description before approving " id="approve-new-risk-button-<?php echo $risk['id'] ;?>"
                            onclick="approveNewStock(<?php echo $risk['id'];?>)"><i class="far fa-times-circle"></i> Reject</button>
                       </form>
                     </div>
                   </td>


            </tr>
            <?php
            }
          //}
            ?>
          </table>
      </div>
  </div>
</div>
