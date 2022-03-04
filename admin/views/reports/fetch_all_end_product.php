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
                            "SELECT * FROM end_product WHERE date_recorded BETWEEN '".$row_start_date."' AND '".$row_end_date."' ORDER BY id "
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
          <table class="table table-striped table-bordered table-hover" id="all_end_products_reports" style="width:100%">
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
            $number = 1;
            while($row = mysqli_fetch_array($sql))
            {
              ?>
              <tr style="cursor: pointer;">
                <td width="50px"> <?php echo $number++;?>.

                </td>
                  <td><?php echo $row['product_name'] ;?></td>
                  <td><?php echo number_format($row['unit_price'],2);?></td>
                    <td><?php echo $row['qtt'];?></td>
                    <td>
                      <?php

                           $result = mysqli_query($dbc, "SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$row['id']."'
                           ORDER BY id DESC LIMIT 1"  );
                           if(mysqli_num_rows($result))
                           {
                             while($project= mysqli_fetch_array($result))
                             {

                                echo $project['qtt'];

                             }
                           }
                           ?>
                            </td>
                              <td class="<?php echo $project['stock_remaining'];?>" width="40px;">
                              <?php

                                   $result = mysqli_query($dbc, "SELECT * FROM customer_end_delivery WHERE end_product_ref ='".$row['id']."'
                                   ORDER BY id DESC LIMIT 1"  );
                                   if(mysqli_num_rows($result))
                                   {
                                     while($project= mysqli_fetch_array($result))
                                     {
                                        ?>


                                     <?php
                                        echo $project['stock_remaining'];

                                     }
                                   }
                                   else {
                                 echo $row['qtt'];
                                   }
                                   ?>

                                    </td>



                  <td><?php echo number_format($row['total'],2);?></td>
                  <td><?php echo $row['start_date'];?></td>
                  <td><?php echo $row['end_date'];?></td>
                  <td>
                    <?php
                    $todays_date = date('d-M-yy');

                    $date1 = new DateTime($row['end_date']); //inclusive
                   $date2 = new DateTime($todays_date); //exclusive
                   $diff = $date2->diff($date1);
                   echo $diff->format("%a");


                     ?>


                  </td>

                  <td>
                    <?php

                         $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$row['customer_id']."' ORDER BY id "  );
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
