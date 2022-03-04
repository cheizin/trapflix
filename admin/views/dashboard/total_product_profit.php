<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>

 <table class="table table-striped table-bordered table-hover profit-loss-table" style="width:100%">
   <thead class="thead-light">
     <tr>
       <th scope="col">#</th>
       <th scope="col">Product Name</th>
       <th scope="col">Unit Price</th>
       <th scope="col">Delivered Qtt</th>
       <th scope="col">Product Cost</th>
       <th scope="col">Cost of Production</th>
       <th scope="col">Profit</th>

     </tr>
   </thead>
   <tbody>
     <?php
        $no = 1;
        $sql= mysqli_query($dbc,"SELECT * FROM end_product where id IN

                                              (SELECT end_product_ref from customer_end_delivery)
                                              Order by id");

        while($end_delivery = mysqli_fetch_array($sql))
        {
          ?>
          <tr style="cursor: pointer;">
            <td width="40px"><?php echo $no++ ;?>.

            </td>
       <td><?php echo $end_delivery['product_name'];?></td>
       <td><?php echo $end_delivery['unit_price'];?></td>
       <td>
         <?php
           $get_qtt2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$end_delivery['id']."'
           GROUP BY end_product_ref"));


                   echo ($get_qtt2['qtt']) ;

              ?>

         </td>

           <td>
             <?php
               $get_qtt2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt  FROM customer_end_delivery WHERE end_product_ref ='".$end_delivery['id']."'
               GROUP BY end_product_ref"));


                       $pro_cost = $end_delivery['unit_price'] * $get_qtt2['qtt'];
                       echo number_format($pro_cost,2) ;
                  ?>

             </td>

            <td>

              <?php
                $get_remaining = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as total FROM single_product WHERE product_name ='".$end_delivery['id']."'
                GROUP BY product_name"));


                        echo number_format($get_remaining['total'],2) ;

                   ?>

              </td>

            <td>
              <?php
                $get_remaining = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as total FROM single_product WHERE product_name ='".$end_delivery['id']."'
                GROUP BY product_name"));

                $get_qtt2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery WHERE end_product_ref ='".$end_delivery['id']."'
                GROUP BY end_product_ref"));


                        $pro_cost = $end_delivery['unit_price'] * $get_qtt2['qtt'];


                $Total123 = $pro_cost - $get_remaining['total']  ;
                echo number_format($Total123,2) ;

                ?>

            </td>

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

<script>
$("[data-toggle=popover]").popover();
</script>
