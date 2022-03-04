<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>
 <table class="table table-bordered table-striped table-hover" id="dashboard-active-stocks-table">
   <thead>
     <tr>
       <td>#</td>
       <td>Channel Name</td>
          <td>No of Subscribers</td>
          
          <td>No Of Videos</td>

          <td>Owner</td>

       <td>Approval Status</td>

     </tr>
   </thead>
     </tbody>
   <?php
      $no = 1;
      $sql= mysqli_query($dbc,"SELECT * FROM main_categories ORDER BY id DESC");
      while($channel_stats= mysqli_fetch_array($sql))
      {
        ?>
        <tr style="cursor: pointer;">
          <td width="40px"><?php echo $no++ ;?>.

          </td>
              <td> <small><?php echo $channel_stats['category_name'] ;?></small></td>

              <td> <small>
                <?php

                  $total_subscribers = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM channel_subscription WHERE channel_name  ='".$channel_stats['category_name']."' ORDER BY id"));


   echo $total_subscribers;

                     ?>
              </small></td>
                  <td> <small>
                    <?php

                      $total_videos2 = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM videos WHERE title  ='".$channel_stats['category_name']."' ORDER BY id"));


       echo $total_videos2;

                         ?>
                  </small></td>

                  <td> <small><?php echo $channel_stats['recorded_by'] ;?></small></td>
                    <td> <small>



                                  <?php


                                  if($channel_stats['approved']== 'yes')
{

                                        ?>

                                          <i class="fas fa-check text-success"> Approved</i>


                                         <?php
}
else
{
  ?>

    <i class="fas fa-times text-danger"> Pending Approval</i>


  <?php
}

                                         ?></small></td>

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



            </tr>
        </tfoot>
 </table>
<script>
$("[data-toggle=popover]").popover();
</script>
