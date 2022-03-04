<?php

session_start();
include("../../controllers/setup/connect.php");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

 if (!isset($_SESSION['email']))
 {
    exit("unauthenticated");
 }
  $row_start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_start_date']));
  $row_end_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_end_date']));

    $sql = mysqli_query($dbc,
                            "SELECT * FROM customer_end_delivery WHERE date_recorded BETWEEN '".$row_start_date."' AND '".$row_end_date."'GROUP BY end_product_ref "
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
          <table class="table table-striped table-bordered table-hover" id="stocks-in-production-report" style="width:100%">
            <thead>
              <tr>
                <td>#</td>
                <td>Product Name</td>
                <td>Product Price</td>
                <td>Total Stock</td>
                <td>Quantity Delivered</td>
                <td>Quantity Remaining</td>
                <td>Total</td>
                <td>Requested By</td>
                <td>Date requested</td>

                 <td>Project Name</td>

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
                <td>
                  <?php

                       $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$row['end_product_ref']."' "  );
                       if(mysqli_num_rows($result))
                       {
                         while($row_name = mysqli_fetch_array($result))
                         {

                            echo $row_name['product_name'];

                         }
                       }
                       ?>


                 </td>
                 <td>
                   <?php echo number_format($row['unit_price'],2) ;  ?>  </td>
                   <td>
                     <?php

                          $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$row['end_product_ref']."' "  );
                          if(mysqli_num_rows($result))
                          {
                            while($row_name = mysqli_fetch_array($result))
                            {

                               echo $row_name['qtt'];

                            }
                          }
                          ?>


                    </td>

                   <?php
                   $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$row['end_product_ref']."' ORDER BY id DESC LIMIT 1"));
                  $single_product67 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$row['end_product_ref']."' ORDER BY id"));
                   // $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$row['reference_no']."' ORDER BY id  DESC LIMIT 1"));

                                                $order_level2 = $product['qtt'];
                                                $order_level22 = $single_product67['qtt']/2;
                                                $order_level23 = $single_product['qtt'];

                                                if($order_level23 < $order_level22)
                                                {

                                                $sql_statu2 = "five";

                                              }
                                              else if($order_level2 == $order_level23)
                                              {
                                                $sql_statu2 = "two";
                                            }

                                            else {
                                              $sql_statu2 = "four";
                                            }

                                                   ?>
                 <td class="<?php echo $sql_statu2;?>" width="40px;">
                     <?php
                               echo $single_product['qtt'];

                          ?>
                           </td>
                <td> <?php
             $single_product45 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$row['end_product_ref']."' ORDER BY id DESC LIMIT 1"));
                           echo $single_product45['stock_remaining'];

                      ?></td>

                <td><?php echo number_format($row['total'],2) ;?></td>
                <td><?php echo $row['recorded_by'];?></td>
                <td><?php echo $row['time_recorded'];?></td>

                   <td>

                     <?php

                          $result = mysqli_query($dbc, "SELECT * FROM end_product WHERE id ='".$row['end_product_ref']."'  "  );
                          if(mysqli_num_rows($result))
                          {
                            while($row_name = mysqli_fetch_array($result))
                            {

                               $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$row_name['customer_id']."' "  );
                               if(mysqli_num_rows($result))
                               {
                                 while($project= mysqli_fetch_array($result))
                                 {

                                    echo $project['customer_name'];

                                 }
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
