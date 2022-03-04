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
                            "SELECT * FROM invoice_received WHERE date_recorded BETWEEN '".$row_start_date."' AND '".$row_end_date."' && id NOT IN

                                    (SELECT invoice_received_id from invoice_received_payment)
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
          <table class="table table-striped table-bordered table-hover" id="pending_invoices_report" style="width:100%">
            <thead>
              <tr>
                <td>#</td>
                <td>Stock Name</td>
                <td>Transaction Id</td>
                 <td>Supplier Name</td>
                   <td>Unit Price</td>
                   <td>Quantity</td>
                <td>Total Credit</td>
                <td>Total Debit</td>
                <td>Balance</td>

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

                       $result = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$row['reference_no']."' ORDER BY id "  );
                       if(mysqli_num_rows($result))
                       {
                         while($supplier = mysqli_fetch_array($result))
                         {

                            echo $supplier['item_name'];

                         }
                       }
                       ?>


                     </td>
                     <td><?php echo $row['invoice_received_id'];?></td>
                <td>
                  <?php

                       $result = mysqli_query($dbc, "SELECT * FROM supplier WHERE id ='".$row['supplier_id']."' ORDER BY id "  );
                       if(mysqli_num_rows($result))
                       {
                         while($supplier = mysqli_fetch_array($result))
                         {

                            echo $supplier['supplier_name'];

                         }
                       }
                       ?>


                     </td>

                     <td><?php echo number_format($row['unit_price'],2) ;?></td>

                     <td><?php echo $row['qtt'];?></td>
                     <td><?php echo number_format($row['total'],2) ;?></td>
                     <td>

                       <?php

                            $result = mysqli_query($dbc, "SELECT sum(debit) as tot FROM invoice_received_payment WHERE invoice_received_id ='".$row['id']."' ORDER BY id"  );
                         //   if(mysqli_num_rows($result))
                           // {

                              while($debit = mysqli_fetch_assoc($result))
                              {
                                 echo number_format($debit['tot'],2) ;
                              }
                         //   }
                            ?>
                            </td>
                            <td>

                              <?php

                              $debit_amt = mysqli_query($dbc,"SELECT * FROM invoice_received_payment WHERE invoice_received_id ='".$row['id']."' ORDER BY id");

                                   $result = mysqli_query($dbc, "SELECT sum(debit) as tot FROM invoice_received_payment WHERE invoice_received_id ='".$row['id']."' ORDER BY id"  );
                                //   if(mysqli_num_rows($result))
                                  // {


                                     while($debit = mysqli_fetch_assoc($result))
                                     {
                                  //  echo $debit['tot'];
                                    $row1234 = $row['total'] - $debit['tot'];
                                    echo number_format($row1234,2) ;
                                     }
                                //   }
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
