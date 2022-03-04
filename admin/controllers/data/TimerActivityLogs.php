<?php
session_start();
include("../../controllers/setup/connect.php");
?>
<div class="text-light pl-2 font-weight-bold"> TODAY : </div>
<ul class="ml-5 list-unstyled pl-3">
  <?php
     //select todays activity logs
      $today = date('Y/m/d');
      $sql_today = mysqli_query($dbc,"SELECT * FROM activity_logs
                                WHERE (SUBSTRING(time_recorded,1,10) = '".$today."')
                                && Email!='Automated Script' ORDER BY id DESC ");
      while($todays_logs = mysqli_fetch_array($sql_today))
      {
        $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT Name FROM staff_users WHERE Email='".$todays_logs['email']."'"))
        ?>
          <li class="text-light">
            <small><?php echo $name['Name'];?>
            <?php echo $todays_logs['action_reference'];?>
             (<i class="<?php echo $todays_logs['action_icon'] ;?>"></i>)
           </small>
           </li>
        <?php
      }
   ?>
 </ul>
