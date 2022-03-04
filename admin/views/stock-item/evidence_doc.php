
<?php
session_start();
include("../../controllers/setup/connect.php");
$stock = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no='".$_POST['reference_no']."'"));
$invoice1 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no='".$_POST['reference_no']."'"));
$SingleProductID = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$_POST['reference_no']."'
  ORDER BY id DESC LIMIT 1"));

?>
<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Evidence Documents
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#evidence-document-modal">
              <i class="fa fa-paperclip"></i> Attach Evidence Documents for <strong><?php echo $stock['item_name'] ;?> </strong>
      </button>
    </div>
    <div class="card-body table-responsive">

      <?php
    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM all_evidence_document WHERE reference_no ='".$invoice1['reference_no']."' ORDER BY id ");

    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>
     <table class="table table-striped table-bordered table-hover" id="invoice-received-table" style="width:100%">
         <tr>
           <td>#</td>
           <td>Purchase Order Document</td>
           <td>Delivery Note Document</td>
           <td>Invoice Document</td>
           <td>Supplier Name</td>

       </tr>
       </thead>
       <?php

         $sql = mysqli_query($dbc,"SELECT * FROM all_evidence_document WHERE reference_no ='".$invoice1['reference_no']."' ORDER BY id");
           while($invoice = mysqli_fetch_array($sql))

           {?>
             <tr style="cursor: pointer;">
               <td><?php echo $number++;?>. </td>
               <td>    <a href="views/stock-item/documents/<?php echo $invoice['purchase_order_doc'];?>" target="_blank">

                            <?php echo $invoice['purchase_order_doc'];?>

                             </a>
                           </td>
               <td> <a href="views/stock-item/documents/<?php echo $invoice['delivery_note_doc'];?>" target="_blank">
                    <?php echo $invoice['delivery_note_doc'];?>
                             </a>
                           </td>
               <td><a href="views/stock-item/documents/<?php echo $invoice['invoice_doc'];?>" target="_blank">

                        <?php echo $invoice['invoice_doc'];?>
                             </a>
                             </td>
                             <td>
                               <?php echo $invoice1['supplier_id'];
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
<strong><i class="fa fa-info-circle"></i> No attached Evidence Document</strong>
</div>

       <?php
     }
     ?>



    </div>
  </div>
</div>
<!-- start evidence doc modal -->


<div class="modal fade" id="evidence-document-modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert alert-primary">

        <h5 class="modal-title">Attaching Supporting Documents for <strong><?php echo $stock['item_name'] ;?></strong>


           <span class="font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-evidence-document-form" class="mt-4" enctype="multipart/form-data">
          <input type="hidden" name="reference_no" value="<?php echo $stock['reference_no'];?>">
          <input type="hidden" name="item_name" value="<?php echo $stock['item_name'];?>">
          <input type="hidden" name="add-evidence-document" value="add-evidence-document">

          <div class="row border-bottom mx-2">

          <div class="col-lg-12 col-xs-12">
              <label>Purchase order Document</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file project-file">
                        <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" name="additional_file2" class="form-control purchase-order-document" single>
                    </span>
                </span>
              </div>
              <input type="text" class="form-control bg-white purchase-order-document-label" readonly>
            </div>
            <div class="row purchase-order-document-error"></div>

          </div>

          <div class="col-lg-12 col-xs-12">
              <label>Delivery Note Document</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file project-file">
                        <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" name="additional_file" class="form-control delivery-note-document" single>
                    </span>
                </span>
              </div>
              <input type="text" class="form-control bg-white delivery-note-document-label" readonly>
            </div>
            <div class="row delivery-note-document-error"></div>

          </div>

                        <div class="col-lg-12 col-xs-12">
                            <label>Invoice Document</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-btn">
                                  <span class="btn btn-primary btn-file project-file">
                                      <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" name="file" class="form-control invoice-document" single>
                                  </span>
                              </span>
                            </div>
                            <input type="text" class="form-control bg-white invoice-document-label" readonly>
                          </div>
                          <div class="row invoice-document-error"></div>

                        </div>

              <div class="pull-left mt-4">
                <small class="text-muted">Recorded by:- <?php echo $_SESSION['name'];?></small>
              </div>
                </div>



          <div class="row text-center">
              <button type="submit" class="btn btn-primary btn-block btn_submit_total submitting8">SUBMIT</button>
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
