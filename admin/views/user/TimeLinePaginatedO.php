<?php
session_start();
include("../../controllers/setup/connect.php");

$limit = 5;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $limit;

$sql = "SELECT * FROM activity_logs WHERE Email='".$_SESSION['email']."' ORDER BY id DESC LIMIT $start_from, $limit";

$first_log_from_limit = mysqli_fetch_array(mysqli_query($dbc,$sql));
$rs_result = mysqli_query($dbc, $sql);

  ?>
  <!-- The timeline -->
  <div class="timeline timeline-inverse">
    <?php
      $last_log = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM activity_logs
                                          WHERE email='".$_SESSION['email']."'
                                           ORDER BY id  DESC LIMIT 1"));
     ?>
    <!-- timeline time label -->
    <div class="time-label">
      <span class="bg-success"><i class="far fa-user-clock"></i>
        <?php echo $last_log['time_recorded'];?>
      </span>

      <div class="input-group" style="float:right;width:350px;">
        <div class="input-group-prepend">
          <span class="input-group-text" id="search-addon"><i class="fad fa-search"></i></span>
        </div>
        <input type="text" class="form-control" name="search" id="search" placeholder="Search" aria-label="Search" aria-describedby="search-addon">
        <span id="search-loader"></span>
      </div>
    </div>
    <!-- /.timeline-label -->
    <!-- timeline item -->
    <?php
       while($logs= mysqli_fetch_array($rs_result)){
         $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT Name FROM staff_users WHERE Email='".$logs['email']."'"));
         if($logs['page_id'] =='login-link')
         {
           $page_id =  "#";
         }
         else
         {
          $page_id =  $logs['page_id'];
         }
           ?>
           <div>
             <i class="fas fa-clock bg-primary"></i>


             <div class="timeline-item hvr-overline-from-center" style="width: 90%;">
               <span class="time"><i class="far fa-clock"></i> <?php echo $logs['time_recorded'];?></span>

               <h3 class="timeline-header">
                  <a href="#"><?php echo $name['Name'];?></a>
                   <i class="far fa-dot-circle"></i>
                   <span class="<?php echo $page_id;?> text-primary" style="cursor: pointer;" ><u><?php echo $logs['action_name'];?></u></span></h3>

               <div class="timeline-body"><?php echo $logs['action_reference'];?></div>
               <div class="timeline-footer"><span class="<?php echo $logs['action_icon'];?>"></span>
               </div>
             </div>
           </div>
           <!-- END timeline item -->

           <?php
       }
?>
    <!-- END timeline item -->
    <!-- timeline time label -->

    <!-- /.timeline-label -->
