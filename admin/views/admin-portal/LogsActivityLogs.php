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
    <div class="col-12">
      <div class="card card-primary card-outline">
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table id="admin-logs-activity-table" class="table table-hover table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th>No</th>
              <th>User</th>
              <th>Action Name</th>
              <th>Action Reference</th>
              <th>Time Recorded</th>
            </tr>
            </thead>
            <tbody>
              <?php
                  $no = 1;
                  $sql = mysqli_query($dbc,"SELECT * FROM activity_logs ORDER BY id DESC");
                  while($row = mysqli_fetch_array($sql))
                  {
                    if($row['page_id'] =='login-link')
                    {
                      $page_id =  "#";
                    }
                    else
                    {
                     $page_id =  $row['page_id'];
                    }
                    $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT Name FROM staff_users WHERE Email='".$row['email']."'"));
                    ?>
                    <tr>
                      <td><?php echo $no++ ;?></td>
                      <td>
                        <?php
                        if($row['email'] == 'Automated Script' || $row['email'] == 'automated script' )
                        {
                          echo $row['email'];
                        }
                        else
                        {
                          echo $name['Name'];
                        }

                        ?>
                      </td>
                      <td class="<?php echo $page_id;?> text-primary" style="cursor:pointer;"><?php echo $row['action_name'];?></td>
                      <td><?php echo $row['action_reference'];?> <br/> <i class="<?php echo $row['action_icon'];?>"></i></td>
                        <td><?php echo $row['time_recorded'];?></td>
                    </tr>
                    <?php
                  }
               ?>
            </tbody>
            <tfoot>
            <tr>
              <th>No</th>
              <th>User</th>
              <th>Action Name</th>
              <th>Time Navigated</th>
            </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->
