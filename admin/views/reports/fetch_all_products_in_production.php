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
                            "SELECT * FROM end_product WHERE date_recorded BETWEEN '".$row_start_date."' AND '".$row_end_date."' && ID NOT IN

                              (SELECT end_product_ref from customer_end_delivery)
                                ");

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
          <table class="table table-striped table-bordered table-hover" id="stocks-in-production-report2" style="width:100%">
            <thead>
              <tr>
                <td>#</td>
                <td>Product Name</td>
                <td>Stock Price</td>
                <td>Quantity</td>

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
                <td><?php echo $row['product_name'];?></td>
                 <td>
                   <?php echo number_format($row['unit_price'],2) ;  ?>  </td>

                <td><?php echo $row['qtt'];?></td>

                <td><?php echo number_format($row['total'],2) ;?></td>
                <td><?php echo $row['recorded_by'];?></td>
                <td><?php echo $row['time_recorded'];?></td>

                   <td>

                     <?php

                               $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$row['customer_id']."' "  );
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
