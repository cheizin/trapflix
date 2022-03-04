<?php
session_start();
include("../../controllers/setup/connect.php");

if(isset($_POST['search_value']) && $_POST['search_value']!='')
{
  $search = mysqli_real_escape_string($dbc,strip_tags($_POST['search_value']));

  if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };

  $sql = "SELECT * FROM activity_logs WHERE Email='".$_SESSION['email']."'
                   && (action_name LIKE '%$search%' || action_reference LIKE '%$search%') LIMIT 5";

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

      </div>
      <!-- /.timeline-label -->
      <!-- timeline item -->
      <?php
      if(mysqli_num_rows($rs_result) > 0){
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
       }
         else
         {
           ?>
           <div>
             <div class="timeline-item" style="width: 90%;">
               <div class="timeline-body">
                  <?php echo "Your search <strong> " .$search.  " </strong> did not match any Activity Logs";?>
                </div>
               <div class="timeline-footer"><i class="fal fa-ban text-danger"></i></span>
               </div>
             </div>
           </div>
           <!-- END timeline item -->
           <?php
         }
}
else
{

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
}

?>
