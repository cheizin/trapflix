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

  $sql= mysqli_query($dbc,"SELECT * FROM end_product WHERE date_recorded BETWEEN '".$row_start_date."' AND '".$row_end_date."' && id IN

                                        (SELECT end_product_ref from customer_end_delivery)
                                        Order by id");


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
          <table class="table table-striped table-bordered table-hover profit-loss-table2" style="width:100%">
            <thead>
              <tr>
                <td>#</td>
                <td>Product Name</td>
                <td>Unit Price</td>
                 <td>Delivered Qtt</td>
                   <td>Product Cost</td>
                   <td>Cost Of Production</td>
                <td>Profit</td>

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
                <td><?php echo $row['product_name'];?></td>
                <td><?php echo $row['unit_price'];?></td>
                <td>
                  <?php
                    $get_qtt2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$row['id']."'
                    GROUP BY end_product_ref"));


                            echo ($get_qtt2['qtt']) ;

                       ?>

                  </td>

                    <td>
                      <?php
                        $get_qtt2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt  FROM customer_end_delivery WHERE end_product_ref ='".$row['id']."'
                        GROUP BY end_product_ref"));


                                $pro_cost = $row['unit_price'] * $get_qtt2['qtt'];
                                echo number_format($pro_cost,2) ;
                           ?>

                      </td>

                     <td>

                       <?php
                         $get_remaining = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as total FROM single_product WHERE product_name ='".$row['id']."'
                         GROUP BY product_name"));


                                 echo number_format($get_remaining['total'],2) ;

                            ?>

                       </td>

                     <td>
                       <?php
                         $get_remaining = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as total FROM single_product WHERE product_name ='".$row['id']."'
                         GROUP BY product_name"));

                         $get_qtt2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$row['id']."'
                         GROUP BY end_product_ref"));


                                 $pro_cost = $row['unit_price'] * $get_qtt2['qtt'];


                         $Total123 = $pro_cost - $get_remaining['total']  ;
                         echo number_format($Total123,2) ;

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
