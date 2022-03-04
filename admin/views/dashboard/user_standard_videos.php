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
       <td>Video Name</td>
          <td>Video Type</td>
            <td>Number of Views</td>
          <td>Channel Name</td>

       <td>Owner</td>

     </tr>
   </thead>
     </tbody>
   <?php
      $no = 1;
      $sql= mysqli_query($dbc,"SELECT * FROM videos WHERE email ='".$_SESSION['email']."'ORDER BY youtube_vid DESC");
      while($video_stats= mysqli_fetch_array($sql))
      {
        ?>
        <tr style="cursor: pointer;">
          <td width="40px"><?php echo $no++ ;?>.

          </td>
              <td> <small><?php echo $video_stats['textname'] ;?></small></td>
                <td> <small><?php echo $video_stats['youtube_vid'] ;?></small></td>



                              <td> <strong>
                                <?php

                                  $total_views = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM video_views WHERE video_id  ='".$video_stats['id']."' ORDER BY id"));


                   echo $total_views; ?> Views


                   <?php

                                     ?>
                              </strong></td>
                  <td> <small><?php echo $video_stats['title'] ;?></small></td>
                      <td> <small>
                                   <?php

                                        $result = mysqli_query($dbc, "SELECT * FROM users WHERE email  ='".$video_stats['email']."' ORDER BY id DESC"  );
                                        if(mysqli_num_rows($result))
                                        {
                                          while($project= mysqli_fetch_array($result))
                                          {

                                             echo $project['name'];

                                          }
                                        }
                                        ?>
                                    </small></td>


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



            </tr>
        </tfoot>
 </table>
<script>
$("[data-toggle=popover]").popover();
</script>
