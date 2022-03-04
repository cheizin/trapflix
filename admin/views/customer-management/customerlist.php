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
      Customer List
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-customer-modal">
              <i class="fa fa-plus-circle"></i> Add Customer
      </button>
    </div>
    <div class="card-body table-responsive">

      <?php
   $sql_query1 =  mysqli_query($dbc,"SELECT * FROM customer ORDER BY id");

   $number = 1;
   if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
   {?>

     <table class="table table-striped table-bordered table-hover" id="end-product-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Customer Name</td>
           <td>Contact</td>
           <td>Email</td>
           <td>Sector</td>
           <td>Status</td>
           <td>Date recorded</td>
           <td>Recorded By</td>

        <!--   <td>Edit</td> -->

         </tr>
       </thead>
       <?php
          $no = 1;
          $sql= mysqli_query($dbc,"SELECT * FROM customer ORDER BY id DESC ");
          while($product = mysqli_fetch_array($sql))
          {
            ?>
            <tr style="cursor: pointer;">
              <td width="40px"><?php echo $no++ ;?>.

              </td>


              <td><?php echo $product['customer_name'];?></td>
              <td><?php echo $product['contact'];?></td>

              <td><?php echo $product['email'];?></td>
              <td>        <?php

                           $result = mysqli_query($dbc, "SELECT * FROM customer_sector WHERE id ='".$product['sector']."' ORDER BY id "  );
                           if(mysqli_num_rows($result))
                           {
                             while($stockist = mysqli_fetch_array($result))
                             {

                                echo $stockist['sector_name'];

                             }
                           }
                           ?>
              </td>
              <td>
        <?php
          if($product['status'] == 'Active')
          {
            ?>
            <span class="badge badge-success"  style="cursor: pointer;" title="Customer Active, Click to deactivate" onclick="ChangeCustomerStatus('<?php echo $product['id'];?>','not_active');">Active</span>
            <?php
          }
          else
          {
            ?>
            <span class="badge badge-danger"  style="cursor: pointer;"  title="Customer Not Active, Click to Activate" onclick="ChangeCustomerStatus('<?php echo $product['id'];?>','Active');">Not Active</span>
            <?php
          }

         ?>
      </td>
              <td><?php echo $product['time_recorded'];?></td>
              <td><?php echo $product['recorded_by'];?></td>


                            <!-- start edit project lesson learnt modal -->
                            <div class="modal fade" id="edit-project-lesson-modal-<?php echo $product['id'] ;?>" role="dialog">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Modifying Customer
                                       <span class="font-weight-bold"><?php echo $product['customer_name'];?></span> </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form id="edit_customer_list-form-<?php echo $product['id'];?>"
                                      onsubmit="Modify_customer_list('<?php echo $product['id'];?>');">
                                      <!-- start of row -->
                                      <div class="row">
                                          <div class="col-lg-4 col-xs-12 form-group">
                                              <label><span class="required">*</span>Customer Name</label>
                                              <textarea id="customer_name-<?php echo $product['id'];?>" class="form-control" required><?php echo $product['customer_name'];?></textarea>
                                          </div>

                                        <div class="col-lg-4 col-xs-12 form-group">
                                            <label><span class="required">*</span>Contact</label>
                                            <textarea id="contact-<?php echo $product['id'];?>" class="form-control" required><?php echo $product['contact'];?></textarea>
                                        </div>

                                          <div class="col-lg-4 col-xs-12 form-group">
                                              <label><span class="required">*</span>Email</label>
                                              <textarea id="email-<?php echo $product['id'];?>" class="form-control" required><?php echo $product['email'];?></textarea>
                                          </div>

                                      </div>
                                      <!-- end of row -->
                                      <div class="row text-center">
                                          <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                                      </div>
                                    </form>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- end edit project lesson learnt modal -->


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
