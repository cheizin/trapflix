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
       <li class="breadcrumb-item active" aria-current="page">End Product Mangement</li>
     </ol>
</nav>


<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      End Product
      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-end-product-modal">
              <i class="fa fa-plus-circle"></i> Add End Product For a Project
      </button>
    </div>
    <div class="card-body table-responsive">

      <?php
   $sql_query1 =  mysqli_query($dbc,"SELECT * FROM end_product  ORDER BY id ");

   $no = 1;
   if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
   {?>

     <table class="table table-striped table-bordered table-hover delivery-util-table2"  style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Product Name</td>
           <td>Unit Price</td>
           <td>Total Stock</td>
           <td>Delivered Stock</td>
           <td>Available Stock</td>
           <td>Total</td>
           <td>Start Date</td>
           <td>End Date</td>
           <td>Days Due</td>

            <td>Project Name</td>


         </tr>
       </thead>
       <?php
          $no = 1;
          $sql= mysqli_query($dbc,"SELECT * FROM end_product WHERE id!='13' ORDER BY id DESC");
          while($product = mysqli_fetch_array($sql))
          {
            ?>
            <tr style="cursor: pointer;">
              <td width="40px"><?php echo $no++ ;?>.

              </td>
                <td onclick="ViewDelivery('<?php echo $product['id'];?>');" title="Click <?php echo $product['product_name'] ;?> to view more Details">
                     <span class="text-primary" style="cursor:pointer;"><?php echo $product['product_name'] ;?></span>
                </td>


              <td><?php echo number_format($product['unit_price'],2);?></td>
                <td><?php echo $product['qtt'];?></td>
                <?php
                $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$product['id']."' ORDER BY id DESC LIMIT 1"));
                // $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id  DESC LIMIT 1"));

                                             $order_level2 = $product['qtt'];
                                             $order_level22 = $product['qtt']/2;
                                             $order_level23 = $single_product['qtt'];

                                             if($order_level2 < $order_level23)
                                             {

                                             $sql_statu2 = "four";

                                           }
                                           else if($order_level2 == $order_level23)
                                           {
                                             $sql_statu2 = "two";
                                         }
                                         else if($order_level22 < $order_level23)
                                         {
                                           $sql_statu2 = "four";
                                       }
                                         else {
                                           $sql_statu2 = "five";
                                         }

                                                ?>
              <td class="<?php echo $sql_statu2;?>" width="40px;">
                  <?php

                       $result = mysqli_query($dbc, "SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$product['id']."'
                       ORDER BY id DESC LIMIT 1"  );
                       if(mysqli_num_rows($result) > 0)
                       {
                         while($project= mysqli_fetch_array($result))
                         {

                            echo $project['qtt'];

                         }
                       }
                       else {
                         ?>
                        0
                          <?php
                       }
                       ?>
                        </td>
                          <td class="<?php echo $project['stock_remaining'];?>" width="40px;">
                          <?php

                               $result = mysqli_query($dbc, "SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$product['id']."'
                               ORDER BY id DESC LIMIT 1"  );
                               if(mysqli_num_rows($result) > 0)

                               {
                                 while($project= mysqli_fetch_array($result))
                                 {
                                    ?>


                                 <?php
                                    echo $project['stock_remaining'];

                                 }
                               }
                               else {
                             echo $product['qtt'];
                               }
                               ?>
                                </td>



              <td><?php echo number_format($product['total'],2);?></td>
              <td><?php echo $product['start_date'];?></td>
              <td><?php echo $product['end_date'];?></td>
              <td>
                <?php
                $todays_date = date('d-M-yy');

                $date1 = new DateTime($product['end_date']); //inclusive
               $date2 = new DateTime($todays_date); //exclusive
               $diff = $date2->diff($date1);
               echo $diff->format("%a");


                 ?>


              </td>

              <td>
                <?php

                     $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$product['customer_id']."' ORDER BY id "  );
                     if(mysqli_num_rows($result))
                     {
                       while($project= mysqli_fetch_array($result))
                       {

                          echo $project['customer_name'];

                       }
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
<strong><i class="fa fa-info-circle"></i> No End Product Recorded</strong>
</div>

       <?php
     }
     ?>



    </div>
  </div>
</div>


<!-- start add end product modal -->
<div class="modal fade" id="add-end-product-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">


        <h5 class="modal-title">Adding End Product for a Project
           <span class="font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-end-product-form">

          <input type="hidden" name="add-end-product" value="add-end-product">
          <!-- start of row -->
          <div class="row">


              <div class="col-lg-3 col-xs-12 form-group">
                  <label><span class="required">*</span>Product Name</label>
                  <input type="text" placeholder="Product Name" name="product_name">
              </div>
              <div class="col-lg-3 col-xs-12 form-group">
                  <label><span class="required">*</span>Unit Price</label>

                    <input type="number" autocomplete="off" class="select2 form-control stock_unit_price"   name="unit_price">
              </div>
              <div class="col-lg-3 col-xs-12 form-group">
                  <label><span class="required">*</span>Qtt</label>

                    <input type="number" autocomplete="off" class="select2 form-control stock_qtt"   name="qtt">
              </div>
              <div class="col-lg-3 col-xs-12 form-group">
                  <label><span class="required">*</span>Total</label>

                    <input type="number" autocomplete="off" class="select2 form-control stock_total"  name="total" readonly>
              </div>
              </div>
              <div class="row">
              <div class="col-lg-3 col-xs-12 form-group">
                <label> <span class="required">*</span> Start Date</label>
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                  </div>
                  <input type="text" class="form-control project_start_date" autocomplete="off" name="product_start_date" required>
                </div>
              </div>
              <div class="col-lg-3 col-xs-12 form-group">
                <label> <span class="required">*</span> End Date</label>
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                  </div>
                  <input type="text" class="form-control project_end_date" autocomplete="off" name="product_end_date" required>
                </div>
              </div>
              <div class="col-lg-3 col-xs-12 form-group">
                  <label><span class="required">*</span>Duration</label>


                  <input type="hidden" class="form-control project-duration-in-days" name="duration" readonly required>
                  <input type="text" class="form-control pull-right project-duration bg-grey" readonly required>
              </div>

              <div class="col-lg-3 col-xs-12 form-group">
                <label for="project"><span class="required">*</span>Project Name</label>
                <?php
                $result = mysqli_query($dbc, "SELECT * FROM customer");
                echo '
                <select name="customer_id" class="select2 form-control" data-placeholder="Select project Name" required>
                <option></option>';
                while($row = mysqli_fetch_array($result)) {
                  // we're sending the strategic objective id to the db
                    echo '<option value="'.$row['id'].'">'.$row['customer_name']."</option>";
                }
                echo '</select>';
                ?>
              </div>

          </div>
          <!-- end of row -->

          <div class="row text-center">
              <button type="submit" class="btn btn-primary btn-block submitting">SUBMIT</button>
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
