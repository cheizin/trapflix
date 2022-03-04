<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">Sign In Logs</div>
            <div class="card-body table-responsive">
                <?php
               $sql_query = mysqli_query($dbc,"SELECT * FROM sign_in_logs ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="table table-hover" id="sign-in-logs-table">
                 <thead>
                   <tr>
                     <td>NO</td>
                     <td>Email</td>
                     <td>Name</td>
                     <td>Ip Address</td>
                     <td>Time Signed In</td>
                     <td>Time Signed Out</td>
                   </tr>
                 </thead>
                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr style="cursor: pointer;">
                   <td><?php echo $number++;?></td>
                   <td><?php echo $row['email'];?></td>
                   <td><?php echo $row['name'];?></td>
                   <td><?php echo $row['ip_address'];?></td>
                   <td><?php echo $row['time_signed_in'];?></td>
                   <td><?php echo $row['time_signed_out'];?></td>
                 </tr>
                 <?php
                 }
                 ?>
                 <tfoot>
                     <tr>
                         <th>NO</th>
                         <th>Email</th>
                         <th>Name</th>
                         <th>Ip Address</th>
                         <th>Time Signed In</th>
                         <th>Time Signed Out</th>
                     </tr>
                 </tfoot>
               </table>
               <?php
               }
               ?>
            </div>
        </div>
    </div>
</div>
