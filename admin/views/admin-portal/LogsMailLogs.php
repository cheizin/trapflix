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
            <div class="card-header bg-light">Mail Logs</div>
            <div class="card-body table-responsive">
                <?php
               $sql_query = mysqli_query($dbc,"SELECT * FROM sent_mails ORDER BY id DESC");
               $number = 1;
               if($total_rows = mysqli_num_rows($sql_query) > 0)
               {?>
               <table class="table table-hover table-striped" id="mail-logs-table">
                 <thead>
                   <tr>
                     <td>NO</td>
                     <td>Sent To</td>
                     <td>Triggered By</td>
                     <td>Subject</td>
                     <td>Body</td>
                     <td>Date Sent</td>
                   </tr>
                 </thead>
                 <?php
                 while($row = mysqli_fetch_array($sql_query))
                 {?>
                 <tr>
                   <td><?php echo $number++;?></td>
                   <td><?php echo $row['sent_to'];?></td>
                   <td><?php echo $row['triggered_by'];?></td>
                   <td><?php echo $row['message_subject'];?></td>
                   <td><?php echo $row['message_body'];?></td>
                   <td><?php echo $row['date_sent'];?></td>
                 </tr>
                 <?php
                 }
                 ?>
                 <tfoot>
                     <tr>
                       <th>NO</th>
                       <th>Sent To</th>
                       <th>Triggered By</th>
                       <th>Subject</th>
                       <th>Body</th>
                       <th>Date Sent</th>
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
