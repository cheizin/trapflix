<?php

session_start();
include("../../controllers/setup/connect.php");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

 if (!isset($_SESSION['email']))
 {
    exit("unauthenticated");
 }
  $product_start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_start_date']));
  $product_end_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_end_date']));

    $sql = mysqli_query($dbc,
                            "SELECT * FROM stock_item WHERE date_recorded BETWEEN '".$product_start_date."' AND '".$product_end_date."'ORDER BY id DESC"
                            );

/*  }*/

  if($sql)
  {
    $total_rows = mysqli_num_rows($sql);
    if($total_rows > 0)
    {

    ?>
    <div class="card">

     <!-- /.card-header -->
     <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="stock-list-computation-table2" style="width:100%">
            <thead>
              <tr>
                <td>#</td>
                <td>Stock Name</td>
                <td>Stock Description</td>
                <td>Category</td>
                <td>Supplier</td>
                 <td>Quantity Available</td>
                <td>Unit_price</td>
                  <td>Reorder Level</td>
                <td>Total Price</td>
              </tr>
            </thead>
            <?php
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
              <tr style="cursor: pointer;">
                <td width="50px"> <?php echo $number++;?>.

                </td>
                  <td><?php echo $row['item_name'] ;?></td>
                  <td><?php echo $row['item_description'] ;?></td>
                  <td>
                    <?php
                         $result = mysqli_query($dbc, "SELECT * FROM product_category WHERE id ='".$row['category_id']."' ORDER BY id "  );
                         if(mysqli_num_rows($result))
                         {
                           while($product_category = mysqli_fetch_array($result))
                           {

                              echo $product_category['category_name'];

                           }
                         }
                         ?>

                       </td>
                       <td>
                         <?php

                              $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE supplier_name='".$row['supplier_id']."' ORDER BY id "  );
                              if(mysqli_num_rows($result))
                              {
                                while($product_category = mysqli_fetch_array($result))
                                {

                                   echo $product_category['supplier_name'];

                                }
                              }
                              ?>


                            </td>
                            <td>


                                   <?php

                                   $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id");
                                   if(mysqli_num_rows($Proj_phase) > 0)
                                   {

                                     $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));

                                 echo $single_product['stock_remaining'];
                                   ?>

                                 <?php

                                     }

                                      else {

                                             $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id "  );
                                             if(mysqli_num_rows($result))
                                             {
                                               while($product_qtt = mysqli_fetch_array($result))
                                               {

                                                  echo $product_qtt['qtt'];

                                               }
                                             }
                                             ?>
                                        <?php

                                      }
                                      ?>

                                 </td>




                                 <td>
                                   <?php

                                        $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"  );
                                        if(mysqli_num_rows($result))
                                        {
                                          while($product_unit = mysqli_fetch_array($result))
                                          {
                                            ?>


                                            <?php echo number_format($product_unit['unit_price'],2) ;


                                          }
                                        }
                                        ?>


                                      </td>
                                      <?php


                                            $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id");
                                            if(mysqli_num_rows($Proj_phase) > 0)
                                            {

            $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));
             $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id  DESC LIMIT 1"));

                                         $order_level2 = $single_product23['stock_order_level'];
                                         $order_level23 = $single_product['stock_remaining'];

                                         if($order_level2 < $order_level23)
                                         {

                                         $sql_statu2 = "two";

                                       }
                                       else if($order_level2 == $order_level23)
                                       {
                                         $sql_statu2 = "four";
                                     }
                                     else {
                                       $sql_statu2 = "five";
                                     }


                                            ?>

                                          <?php

                                              }

                                               else {

                                     //   $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));
                                       $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id  DESC LIMIT 1"));

                                         $order_level2 = $single_product23['stock_order_level'];
                                                   $order_level23 = $single_product23['qtt'];

                       if($order_level2 < $order_level23)
                         {

                                   $sql_statu2 = "two";

                                   }
                         else if($order_level2 == $order_level23)
                           {
                             $sql_statu2 = "four";
                             }
                               else {
                                 $sql_statu2 = "five";
                           }

                              ?>
                                <?php

                           }

                                   //   $order_level1 = $result45['stock_order_level'];
                                      //$order_level2 = $result456['stock_remaining'];

                                  ?>

                                   <td class="<?php echo $sql_statu2;?>" width="40px;">
                                         <?php

                                              $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1 "  );
                                              if(mysqli_num_rows($result))
                                              {
                                                while($product_unit = mysqli_fetch_array($result))
                                                {

                                                   echo $product_unit['stock_order_level'];

                                                }
                                              }
                                              ?>



                                            </td>
                                      <td>
                                        <?php

                                             $result = mysqli_query($dbc, "SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1 "  );
                                             if(mysqli_num_rows($result))
                                             {
                                               while($product_total = mysqli_fetch_array($result))
                                               {


                                                   $Proj_phase = mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id");
                                                   if(mysqli_num_rows($Proj_phase) > 0)
                                                   {

                                                     $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$row['reference_no']."' ORDER BY id DESC LIMIT 1"));


                                                     $invoice1234 = $single_product['stock_remaining'] * $product_total['unit_price'];

                                                     echo number_format($invoice1234,2) ;
                                                     }
                                                     else {
                                                       $invoice12 = $product_total['qtt'] * $product_total['unit_price'];

                                                       echo number_format($invoice12,2) ;
                                                         //echo number_format($get_remaining['total'],2) ;
                                                     }



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

                               </tr>
                           </tfoot>
          </table>
        </div>
     </div>
     <!-- /.card-body -->
     <div class="card-footer">

     </div>
     <!-- card-footer -->
   </div>
   <!-- /.card -->
     <?php
   } // end num row
   else  //no rows
   {
     ?>
     <div class="alert alert-danger alert-dismissible">
       <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       <strong>No Records!<br/></strong> Sorry, no records found for the selected combination.
     </div>
     <?php
   }
  }
  else
  {
    exit(mysqli_error($dbc));
  }

}
else
{
  exit("NO data");
  ?>

 <?php
}
