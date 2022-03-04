<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>

 <table class="table table-bordered table-striped table-hover" id="dashboard-active-projects-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">End Product</th>
      <th scope="col">Customer Name</th>
      <th scope="col">Deivered Stocks</th>
      <th scope="col">Stocks Remaining</th>
      <th scope="col">Status</th>
      <th scope="col">Recorded By</th>
    </tr>
  </thead>
  <tbody>
    <?php
       $no = 1;
       $sql= mysqli_query($dbc,"SELECT * FROM customer_end_delivery' ORDER BY id DESC");
       while($product = mysqli_fetch_array($sql))
       {
         ?>
         <tr style="cursor: pointer;">
           <td width="40px"><?php echo $no++ ;?>.
            <td> <small>  <?php

                   $result = mysqli_query($dbc, "SELECT * FROM end_product  ORDER BY id "  );
                   if(mysqli_num_rows($result))
                   {
                     while($project= mysqli_fetch_array($result))
                     {

                        echo $project['product_name'];

                     }
                   }
                   ?></small></td>
            <td><small>
              <?php

                   $result = mysqli_query($dbc, "SELECT * FROM customer ORDER BY id "  );
                   if(mysqli_num_rows($result))
                   {
                     while($product_category = mysqli_fetch_array($result))
                     {

                        echo $product_category['customer_name'];

                     }
                   }
                   ?>
                 </small>

                 </td>
                 <td><?php echo $product['total'];?></td>
                 <td><?php echo $product['stock_remaining'];?></td>
                 <td> <small><?php echo $product['status'] ;?></small></td>
                    <td> <small><?php echo $product['recorded_by'] ;?></small></td>

          </tr>
          <?php
        }
     ?>
  </tbody>
</table>
<script>
$("[data-toggle=popover]").popover();
</script>
