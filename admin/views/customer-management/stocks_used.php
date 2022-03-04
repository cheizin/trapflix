<?php
session_start();
include("../../controllers/setup/connect.php");


if(!isset($_POST['id']))
{
  exit("Please select Stock used ");
}

$end_delivery = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$_POST['id']."'"));
?>
<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">

      Stocks Used to Manufacture <strong><?php echo $end_delivery['product_name'];?></strong>
        </div>
        <div class="card-body table-responsive">

      <?php
   $sql_query2 =  mysqli_query($dbc,"SELECT * FROM single_product WHERE product_name ='".$end_delivery['id']."'");

    // $no = 1;
   if($total_rows2 = mysqli_num_rows($sql_query2) > 0)
   {?>

      <div class="row">
          <div class="col-lg-4 col-xs-12 form-group">
            <label> Stock Cost:</label>
          <?php

            $debit_amt2 = mysqli_query($dbc,"SELECT * FROM single_product WHERE product_name ='".$end_delivery['id']."' ORDER BY id");
            if(mysqli_num_rows($debit_amt2) > 0)
            {

                 $result2 = mysqli_query($dbc, "SELECT sum(total) as tot FROM single_product WHERE product_name ='".$end_delivery['id']."' ORDER BY id"  );


                   while($stock_cost = mysqli_fetch_assoc($result2))
                   {
                      ?>


                     <input type="text"  class="select2 form-control new_stock_remaining"  value ="<?php echo number_format($stock_cost['tot'],2) ;?>" name="qtt" readonly>
                     <?php
                //    echo $stock_cost['tot'];
                  }
            }
                 ?>

            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label>Product Cost</label>

              <?php

              $debit_amt = mysqli_query($dbc,"SELECT * FROM end_product WHERE id ='".$end_delivery['id']."' ORDER BY id");
              if(mysqli_num_rows($debit_amt) > 0)
              {

                   $result = mysqli_query($dbc, "SELECT sum(total) as tot FROM end_product WHERE id ='".$end_delivery['id']."' ORDER BY id"  );

                     while($debit = mysqli_fetch_assoc($result))
                     {
                         ?>
                       <input type="text"  class="select2 form-control stock_qtt"  value ="<?php echo number_format($debit['tot'],2);?>" name="qtt" readonly>
                       <?php
                     }
              }
                   ?>

                </div>
              <div class="col-lg-4 col-xs-12 form-group">
                <label>Total
                  <?php
            $result2 = mysqli_query($dbc, "SELECT sum(total) as tot FROM single_product WHERE product_name ='".$end_delivery['id']."' ORDER BY id"  );

              while($stock_cost = mysqli_fetch_assoc($result2))
              {
            // echo $stock_cost['tot'];

             $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(total) as tot2 FROM end_product WHERE id ='".$end_delivery['id']."' ORDER BY id" ) );

               $profit = $result['tot2'] - $stock_cost['tot'];
               if($profit > 0)
               {
                 ?>
                <strong> Profit</strong>
               <?php
             }
             else
             {
               ?>
              <strong> Loss</strong>
             <?php
           }

             }
             ?>
                  </label>
                      <?php
                $result2 = mysqli_query($dbc, "SELECT sum(total) as tot FROM single_product WHERE product_name ='".$end_delivery['id']."' ORDER BY id"  );

                  while($stock_cost = mysqli_fetch_assoc($result2))
                  {
                // echo $stock_cost['tot'];

                 $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(total) as tot2 FROM end_product WHERE id ='".$end_delivery['id']."' ORDER BY id" ) );

                   ?>

                    <input type="text"  class="select2 form-control stock_qtt"  value ="<?php echo number_format($profit,2) ;?>" name="qtt" readonly>
                       <?php
                  //echo $profit;

               //    echo $stock_cost['tot'];
                 }
                 ?>
                      </div>

      </div>

      <?php
      }
      else
      {
            ?>

        <?php
      }
      ?>

    <?php
 $sql_query =  mysqli_query($dbc,"SELECT * FROM single_product WHERE product_name ='".$end_delivery['id']."'");

  // $no = 1;
 if($total_rows = mysqli_num_rows($sql_query) > 0)
 {?>

    <table class="table table-striped table-bordered table-hover stock-util-table" style="width:100%">

        <thead>
        <tr>
          <th>#</th>
          <th>Stock Name</th>
          <th>Quantity Used</th>
          <th>Unit Price</th>
          <th>Total</th>
          <th>Date requested</th>
          <th>Recorded By</th>
        </tr>
      </thead>
      <tbody>
        <?php
         $no = 1;
        $stock_row = mysqli_query($dbc,"SELECT * FROM single_product WHERE product_name ='".$end_delivery['id']."' ORDER BY id");

        while($stock_used = mysqli_fetch_array($stock_row))
        {
          ?>
         <tr style="cursor: pointer;">
            <td width="50px"> <?php echo $no++;?>.

            </td>
            <td>
              <?php

                   $result = mysqli_query($dbc, "SELECT * FROM stock_item WHERE reference_no ='".$stock_used['end_product_ref']."' ORDER BY id "  );
                   if(mysqli_num_rows($result))
                   {
                     while($stockist = mysqli_fetch_array($result))
                     {

                        echo $stockist['item_name'];

                     }
                   }
                   ?>

                 </td>
                 <td><?php echo $stock_used['qtt'];?></td>
                 <td><?php echo number_format($stock_used['unit_price'],2);?></td>
                  <td><?php echo number_format($stock_used['total'],2);?></td>

                  <td><?php echo $stock_used['time_recorded'];?></td>
                  <td><?php echo $stock_used['recorded_by'];?></td>


          </tr>
          <?php
        }
         ?>
      </tbody>


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
      <?php
      }
      else
      {
            ?>
          <br/>
<div class="alert alert-info">
<strong><i class="fa fa-info-circle"></i> No stocks Used In production</strong>
</div>

        <?php
      }
      ?>

    </div>
  </div>
</div>
