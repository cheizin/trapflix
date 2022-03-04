
<?php
session_start();
include("../../controllers/setup/connect.php");
$stock = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no='".$_POST['reference_no']."'"));
$invoice1 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no='".$_POST['reference_no']."'"));
$SingleProductID = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$_POST['reference_no']."'  ORDER BY id DESC LIMIT 1"));

?>

<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Invoices Mangagement List</li>
     </ol>
</nav>

<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
            <div class="card-body table-responsive">

                  <?php
               $sql_query1 =  mysqli_query($dbc,"SELECT * FROM invoice_received ORDER BY id ");

               $number = 1;
               if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
               {?>
                 <table class="table table-striped table-bordered table-hover" id="invoice-received-table" style="width:100%">
                   <thead>
                     <tr>
                       <td>#</td>
                       <td>Transaction Id</td>
                        <td>Supplier Name</td>
                          <td>Unit Price</td>
                          <td>Quantity</td>
                       <td>Total Credit</td>
                       <td>Total Debit</td>
                       <td>Balance</td>
                       <td>Due Date</td>



                     </tr>
                   </thead>
                </div>
                <?php

                  $sql = mysqli_query($dbc,"SELECT * FROM invoice_received ORDER BY id DESC ");
                    while($invoice = mysqli_fetch_array($sql))

                    {?>
                    <tr style="cursor: pointer;">
                      <td><?php echo $number++;?>. </td>
                      <td>
                        <a class="" href="#" data-toggle="modal" data-target="#invoice-payment-modal-<?php echo $invoice['id'];?>"
                          title="Click on <?php echo $invoice['invoice_received_id'];?> to update Invoice payments for the invoice">
                          <span class="text-primary" style="cursor:pointer;"><?php echo $invoice['invoice_received_id'];?></span>
                        </a>

                        <!-- start invoice payment Modal -->
                        <div class="modal fade" id="invoice-payment-modal-<?php echo $invoice['id'];?>" role="dialog">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header alert alert-primary">
                                <h5>Invoice Payment for supplier:                  <?php

                                                       $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE id ='".$invoice['supplier_id']."' ORDER BY id "  );
                                                       if(mysqli_num_rows($result))
                                                       {
                                                         while($supplier = mysqli_fetch_array($result))
                                                         {

                                                            echo $supplier['supplier_name'];

                                                         }
                                                       }
                                                   ?>

                                <div class="row">
                                <div class="col-lg-3 col-xs-12 form-group">
                                  <label>Unit_price</label>
                                   <input type="text"  class="select2 form-control stock_qtt"  value ="<?php echo $invoice['qtt'];?>" name="qtt" readonly>

                                  </div>
                                  <div class="col-lg-3 col-xs-12 form-group">
                                    <label>Quantity</label>
                                     <input type="text"  class="select2 form-control stock_qtt"  value ="<?php echo number_format($invoice['unit_price'],2) ;?>" name="qtt" readonly>

                                    </div>
                                    <div class="col-lg-3 col-xs-12 form-group">
                                      <label>Credit</label>
                                       <input type="text"  class="select2 form-control stock_qtt"  value ="<?php echo number_format($invoice['total'],2) ;?>" name="qtt" readonly>

                                      </div>
                                  </div>
                                   </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                      <form id="invoice-stock-payment-form-<?php echo $invoice['id'];?>" onsubmit="SubmitStockInvoice2('<?php echo $invoice['id'];?>');"
                                        class="mt-4" enctype="multipart/form-data">
                                        <input type="hidden" value ="<?php echo $invoice['qtt'];?>" >
                                        <input type="hidden" value ="<?php echo $invoice['unit_price'];?>" >
                                        <?php

                                                              $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE id ='".$invoice['supplier_id']."' ORDER BY id "  );
                                                              if(mysqli_num_rows($result))
                                                              {
                                                                while($supplier = mysqli_fetch_array($result))
                                                                {

                                                                  ?>
                                                         <input type="hidden" id="supplier_id-<?php echo $invoice['id'];?>" value ="<?php echo $supplier['id'];?>" >
                                                           <?php

                                                                }
                                                              }
                                                          ?>

                                          <div class="row">

                                        <div class="col-lg-6 col-xs-12 form-group">
                                          <label for="category"><span class="required">*</span>Payment Type</label>
                                          <select name="payment_type" id="payment_type-<?php echo $invoice['id'];?>" class="select2 form-control" data-placeholder="Select Payment Gateway">
                                              <?php
                                                $result = mysqli_query($dbc, "SELECT * FROM payment_gateway");
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                  ?>
                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['payment_type'];?></option>

                                                  <?php
                                                }
                                               ?>
                                          </select>
                                        </div>


                                        <div class="col-lg-6 col-xs-12 form-group">
                                          <label>Debit Amount</label>

                                                <input type="text" placeholder="Debit Amount" id="debit-<?php echo $invoice['id'];?>"
                                                value="" >

                                                </div>

                                      </div>
                                      <div class="row">

                                        <div class="col-lg-6 col-xs-12 form-group">
                                          <label>Transaction Id</label>


                                                <input type="text" placeholder="Transaction Code" id="transaction_id-<?php echo $invoice['id'];?>"
                                                value="" >

                                                </div>
                                        <!-- start row invoice files -->
                                          <div class="col-lg-6 col-xs-12 form-group">
                                              <label></span>Invoice document</label>
                                              <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-btn">
                                                      <span class="btn btn-primary btn-file project-file">
                                                          <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" id="invoice_doc-<?php echo $invoice['id'];?>"
                                                          class="form-control invoice_doc"  single>
                                                        </span>
                                                    </span>
                                                  </div>
                                                  <input type="text" class="form-control bg-white invoice-document-label" id="invoice-document-label-<?php echo $invoice['id'];?>" readonly>
                                                </div>
                                                <div class="row invoice-document-error" id="invoice-document-error-<?php echo $invoice['id'];?>"></div>

                                              </div>
                                        <!-- end row project files -->
                                          </div>



                                        <div class="pull-right mt-4">
                                          <small class="text-muted">Recorded by:- <?php echo $_SESSION['name'];?></small>
                                        </div>

                                              <!-- start row button -->

                                          <div class="col-md-12 text-center">
                                              <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">SUBMIT</button>
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


                        <!-- end invoice payment modal  -->


                        </td>

                        <td>
                          <?php

                               $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE id ='".$invoice['supplier_id']."' ORDER BY id "  );
                               if(mysqli_num_rows($result))
                               {
                                 while($supplier = mysqli_fetch_array($result))
                                 {

                                    echo $supplier['supplier_name'];

                                 }
                               }
                               ?>


                             </td>

                             <td><?php echo number_format($invoice['unit_price'],2) ;?></td>

                             <td><?php echo $invoice['qtt'];?></td>
                             <td><?php echo number_format($invoice['total'],2) ;?></td>
                             <td>

                               <?php

                               $debit_amt = mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE invoice_received_id ='".$invoice['id']."' ORDER BY id");
                               if(mysqli_num_rows($debit_amt) > 0)
                               {

                                    $result = mysqli_query($dbc, "SELECT sum(debit) as tot FROM invoice_received_payment WHERE invoice_received_id ='".$invoice['id']."' ORDER BY id"  );
                                 //   if(mysqli_num_rows($result))
                                   // {

                                      while($debit = mysqli_fetch_assoc($result))
                                      {
                                        ?>
                                        <a class="" href="#" data-toggle="modal" data-target="#debit-invoice-modal-<?php echo $invoice['id'];?>"
                                          title="Click on <?php echo number_format($debit['tot'],2) ;?> to view Invoices Paid">
                                        <span class="text-primary" style="cursor:pointer;"><?php echo number_format($debit['tot'],2) ;?></span>
                                        </a>

                                        <?php
                                      }
                                 //   }
                                    ?>
                          <?php
                        }
                        else {

                   ?>
                   <a class="" href="#" data-toggle="modal" data-target="#debit-invoice-modal-<?php echo $invoice['id'];?>"
                     title="Click to view Invoice payment">
                   <span class="text-primary" style="cursor:pointer;">0</span>
                   </a>

                   <?php

                 }
                 ?>

                               <!-- invoice payment Modal -->


                               <div class="modal fade" id="debit-invoice-modal-<?php echo $invoice['id'];?>" role="dialog">
                                 <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                   <div class="modal-content">
                                     <div class="modal-header">
                                       <h5 class="modal-title" id="exampleModalLongTitle">Invoice Payment for Supplier

                                           <?php

                                                $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE id ='".$invoice['supplier_id']."' ORDER BY id "  );
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
                                       <?php
                                    $sql_query =  mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE invoice_received_id ='".$invoice['id']."'");

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

                                           $invoice_row = mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE invoice_received_id ='".$invoice['id']."' ORDER BY id");

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

                        <?php

                        $debit_amt = mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE invoice_received_id ='".$invoice['id']."' ORDER BY id");

                             $result = mysqli_query($dbc, "SELECT sum(debit) as tot FROM invoice_received_payment WHERE invoice_received_id ='".$invoice['id']."' ORDER BY id"  );
                          //   if(mysqli_num_rows($result))
                            // {


                               while($debit = mysqli_fetch_assoc($result))
                               {
                            //  echo $debit['tot'];
                              $invoice1234 = $invoice['total'] - $debit['tot'];
                              echo number_format($invoice1234,2) ;
                               }
                          //   }
                             ?>

                      </td>

                      <td>29/05/2020</td>

                              <?php

                                      $invoiceR2 = mysqli_query($dbc,"SELECT * FROM invoice_received
                                         WHERE reference_no ='".$invoice['reference_no']."' ORDER BY id  ");

                                           $invoiceR = mysqli_fetch_assoc($invoiceR2);

                                         $sql_fully = mysqli_query($dbc,"SELECT sum(debit) as tot FROM invoice_received_payment WHERE
                                            invoice_received_id ='".$invoice['reference_no']."' ORDER BY id ");

                                            $rows = mysqli_fetch_assoc($sql_fully);

                                      if($rows['tot'] == $invoiceR['total'])
                                      {
                                        $status = "Fully Paid";
                                      }
                                      else if($rows['tot'] > $invoiceR['total'])
                                      {
                                        $status = "Overpayment";
                                      }
                                      else
                                      {
                                        $status = "Not Paid";
                                      }
                                   ?>




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
      <strong><i class="fa fa-info-circle"></i> No payment Recorded</strong>
    </div>

                <?php
              }
              ?>

            </div>
            </div>
