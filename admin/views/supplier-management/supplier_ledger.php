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
      Supplier Ledger

    </div>
    <div class="card-body table-responsive">

      <?php
   $sql_query1 =  mysqli_query($dbc,"SELECT * FROM supplier ORDER BY id");


   //$number = 1;
   if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
   {?>

     <table class="table table-striped table-bordered table-hover" id="end-product-table" style="width:100%">
       <thead>
         <tr>

           <td>Supplier Name</td>
           <td>Total Credit</td>
           <td>Total Debit</td>
           <td>Balance</td>

         </tr>
       </thead>
       <?php
          $no = 1;
          $sql= mysqli_query($dbc,"SELECT * FROM supplier ORDER BY id DESC ");
          while($product = mysqli_fetch_array($sql))
          {
            ?>
            <tr style="cursor: pointer;">

              <td>
                <a class="" href="#" data-toggle="modal" data-target="#supplier-details-modal-<?php echo $product['id'];?>"
                  title="Click to view Invoice payment">
                <span class="text-primary" style="cursor:pointer;"><?php echo $product['supplier_name'];?></span>
                </a>


                <!-- supplier payment Modal -->

                <div class="modal fade" id="supplier-details-modal-<?php echo $product['id'];?>" role="dialog">
                  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Payments for Supplier

                          <?php echo $product['supplier_name'];?>


                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <?php
                     $sql_query =  mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE supplier_id ='".$product['id']."'");

                       $no = 1;
                     if($total_rows = mysqli_num_rows($sql_query) > 0)
                     {?>

                        <table class="table table-striped table-bordered table-hover invoice-payment-table" style="width:100%">

                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Transaction Id</th>
                              <th>Payment Type</th>
                              <th>Debit Amount</th>
                              <th>Date recorded</th>
                              <th>Recorded By</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php

                            $invoice_row = mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE supplier_id ='".$product['id']."' ORDER BY id");

                            while($invoiceUtil = mysqli_fetch_array($invoice_row))
                            {
                              ?>
                              <tr>
                                <td width="50px"> <?php echo $no++;?>.

                                </td>
                                <td><?php echo $invoiceUtil['transaction_id'];?></td>
                                <td>
                                  <?php

                                       $result = mysqli_query($dbc, "SELECT * FROM payment_gateway WHERE id ='".$invoiceUtil['payment_type']."' ORDER BY id "  );
                                       if(mysqli_num_rows($result))
                                       {
                                         while($payment = mysqli_fetch_array($result))
                                         {

                                            echo $payment['payment_type'];

                                         }
                                       }
                                       ?>


                                     </td>
                                      <td><?php echo $invoiceUtil['debit'];?></td>
                                <td><?php echo $invoiceUtil['date_recorded'];?></td>
                                <td><?php echo $invoiceUtil['recorded_by'];?></td>


                              </tr>
                              <?php
                            }
                             ?>
                          </tbody>


                                        <tfoot style="background:silver;">
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>

                                                </tr>
                                            </tfoot>
                          </table>
                          <?php
                          }
                          else
                          {
                                ?>
                              <br/>
                <div class="alert alert-info">
                  <strong><i class="fa fa-info-circle"></i> No Invoice Payment Recorded</strong>
                </div>

                            <?php
                          }
                          ?>

                        </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                      </div>
                    </div>
                    </div>

                <!-- end invoice payment modal  -->

              </td>


                <td>
                  <a class="" href="#" data-toggle="modal" data-target="#supplier-credit-modal-<?php echo $product['id'];?>"
                    title="Click to view Invoice payment">
                  <span class="text-primary" style="cursor:pointer;">        <?php

                            $debit_amt2 = mysqli_query($dbc,"SELECT * FROM invoice_received WHERE supplier_id ='".$product['id']."' ORDER BY id");
                            if(mysqli_num_rows($debit_amt2) > 0)
                            {

                                 $result2 = mysqli_query($dbc, "SELECT sum(total) as tot FROM invoice_received WHERE supplier_id ='".$product['id']."' ORDER BY id"  );


                                   while($credit = mysqli_fetch_assoc($result2))
                                   {
                                      ?>

                                  <?php echo $credit['tot'];?>
                                     <?php
                                //    echo $stock_cost['tot'];
                                  }
                            }
                                 ?>
</span>
                  </a>


                  <!-- supplier payment Modal -->

                  <div class="modal fade" id="supplier-credit-modal-<?php echo $product['id'];?>" role="dialog">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Goods Purchased From Supplier:-

                            <?php echo $product['supplier_name'];?>


                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <?php
                       $sql_query4 =  mysqli_query($dbc,"SELECT * FROM invoice_received WHERE supplier_id ='".$product['id']."'");

                         $no = 1;
                       if($total_rows4 = mysqli_num_rows($sql_query4) > 0)
                       {?>

                          <table class="table table-striped table-bordered table-hover supplier-delivery-table" style="width:100%">

                              <thead>
                              <tr>
                                <td>#</td>
                                <td>Stock Name</td>
                                <td>Stock Description</td>
                                <td>Quantity Ordered</td>
                                <td>Unit_price</td>
                                <td>Total Value</td>
                              </tr>
                            </thead>
                            <tbody>
                              <?php

                              $invoice_row4 = mysqli_query($dbc,"SELECT * FROM invoice_received WHERE supplier_id ='".$product['id']."' ORDER BY id");

                              while($invoiceUtil4 = mysqli_fetch_array($invoice_row4))
                              {
                                ?>
                                <tr>
                                  <td width="50px"> <?php echo $no++;?>.

                                  </td>
                                  <td>            <?php

                                                   $result4 = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$invoiceUtil4['reference_no']."' ORDER BY id "  );
                                                   if(mysqli_num_rows($result4))
                                                   {
                                                     while($payment4 = mysqli_fetch_array($result4))
                                                     {

                                                        echo $payment4['item_name'];

                                                     }
                                                   }
                                                   ?>
                                                 </td>
                                                 <td>            <?php

                                                                  $result4 = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$invoiceUtil4['reference_no']."' ORDER BY id "  );
                                                                  if(mysqli_num_rows($result4))
                                                                  {
                                                                    while($payment4 = mysqli_fetch_array($result4))
                                                                    {

                                                                       echo $payment4['item_description'];

                                                                    }
                                                                  }
                                                                  ?>
                                                                </td>
                                        <td><?php echo $invoiceUtil4['qtt'];?></td>
                                  <td><?php echo $invoiceUtil4['unit_price'];?></td>
                                  <td><?php echo $invoiceUtil4['total'];?></td>


                                </tr>
                                <?php
                              }
                               ?>
                            </tbody>


                                          <tfoot style="background:silver;">
                                                  <tr>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>

                                                  </tr>
                                              </tfoot>
                            </table>
                            <?php
                            }
                            else
                            {
                                  ?>
                                <br/>
                  <div class="alert alert-info">
                    <strong><i class="fa fa-info-circle"></i> No Stocks from the supplier</strong>
                  </div>

                              <?php
                            }
                            ?>

                          </div>

                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                      </div>
                      </div>

                  <!-- end invoice payment modal  -->

                </td>




                  <td>

                    <?php

                      $debit_amt3 = mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE supplier_id ='".$product['id']."' ORDER BY id");
                      if(mysqli_num_rows($debit_amt3) > 0)
                      {

                           $result3 = mysqli_query($dbc, "SELECT sum(debit) as tot FROM invoice_received_payment WHERE supplier_id ='".$product['id']."' ORDER BY id"  );


                             while($debit = mysqli_fetch_assoc($result3))
                             {
                                ?>

                            <?php echo $debit['tot'];?>
                               <?php
                          //    echo $stock_cost['tot'];
                            }
                      }
                           ?>

                      </td>


              <td>

                <?php
          $result2 = mysqli_query($dbc, "SELECT sum(total) as tot FROM invoice_received WHERE supplier_id ='".$product['id']."' ORDER BY id"  );

            while($stock_cost = mysqli_fetch_assoc($result2))
            {
          // echo $stock_cost['tot'];

           $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(debit) as tot2 FROM invoice_received_payment WHERE supplier_id ='".$product['id']."' ORDER BY id" ) );

             $balance =  $stock_cost['tot'] - $result['tot2'] ;
             echo $balance;

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
<strong><i class="fa fa-info-circle"></i> No Client List Exist</strong>
</div>

       <?php
     }
     ?>



    </div>
  </div>
</div>

<!-- start add end product modal -->
<div class="modal fade" id="add-customer-modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert alert-primary">

        <h5 class="modal-title">Adding CLient Details


           <span class="font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-customer-list-form">

          <input type="hidden" name="add-customer-list" value="add-customer-list">

          <!-- start of row -->

              <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                    <label><span class="required">*</span>Customer Name</label>
                      <input type="text" autocomplete="off" class="select2 form-control stock_unit_price" name="customer_name">
                </div>

              <div class="col-lg-6 col-xs-12 form-group">
                  <label><span class="required">*</span>Contact</label>
                    <input type="text" autocomplete="off" class="select2 form-control"  name="contact">
              </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                    <label><span class="required">*</span>Email</label>
                      <input type="email" autocomplete="off" class="select2 form-control" name="email">
                </div>


              <div class="col-lg-6 col-xs-12 form-group">
                  <label><span class="required">*</span>Sector</label>
                  <?php
                  $result = mysqli_query($dbc, "SELECT * FROM customer_sector");
                  echo '
                  <select name="sector" data-tags="true" class="select2 form-control" data-placeholder="Select sector" "required>
                  <option></option>';
                  while($row = mysqli_fetch_array($result)) {
                      echo '<option value="'.$row['id'].'">'.$row['sector_name']."</option>";
                  }
                  echo '</select>';
                  ?>
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
<!-- end add project lesson learnt modal -->
