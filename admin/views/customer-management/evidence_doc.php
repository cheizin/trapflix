
<?php
session_start();
include("../../controllers/setup/connect.php");
if(!isset($_POST['id']))
{
  exit("ID NOT AVAILABLE");
}

$end_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$_POST['id']."'"));

$get_supplier = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM supplier WHERE id ='".$_POST['id']."'"));

?>
<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Evidence Documents
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#delivery-evidence-document-modal">
              <i class="fa fa-plus-circle"></i> Attach Evidence Documents for <strong><?php echo $end_product['product_name'] ;?> </strong>
      </button>
    </div>
    <div class="card-body table-responsive">

      <?php
    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM all_evidence_document WHERE reference_no ='".$end_product['id']."' ORDER BY id ");

    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>
     <table class="table table-striped table-bordered table-hover" id="invoice-received-table" style="width:100%">
         <tr>
           <td>#</td>
           <td>Purchase Order Document</td>
           <td>Delivery Note Document</td>
           <td>Invoice Document</td>
           <td>Customer Name</td>

       </tr>
       </thead>
       <?php

         $sql = mysqli_query($dbc,"SELECT * FROM all_evidence_document WHERE reference_no ='".$end_product['id']."' ORDER BY id");
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

                                    Cost Wise


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


<div class="modal fade" id="delivery-evidence-document-modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert alert-primary">

        <h5 class="modal-title">Attaching Supporting Documents for <strong><?php echo $end_product['product_name'] ;?> </strong>

           <span class="font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-delivery-evidence-document-form" class="mt-4" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">
          <input type="hidden" name="product_name" value="<?php echo $end_product['product_name'];?>">
          <input type="hidden" name="add-delivery-evidence-document" value="add-delivery-evidence-document">

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
              <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting6">SUBMIT</button>
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
