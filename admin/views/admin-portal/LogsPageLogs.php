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
          <table id="admin-logs-navigations-table" class="table table-hover table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th>No</th>
              <th>Page Name</th>
              <th>Requested By</th>
              <th>Time Navigated</th>
            </tr>
            </thead>
            <tbody>
              <?php
                  $no = 1;
                  $sql = mysqli_query($dbc,"SELECT * FROM page_requests ORDER BY id DESC");
                  while($row = mysqli_fetch_array($sql))
                  {
                    $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT Name FROM staff_users WHERE Email='".$row['requested_by']."'"));
                    ?>
                    <tr>
                      <td><?php echo $no++ ;?></td>
                      <td class="<?php echo $row['page_id'];?>" style="cursor:pointer;"><?php echo $row['page_name'];?></td>
                      <td><?php echo $name['Name'];?></td>
                      <td><?php echo $row['time_requested'];?></td>
                    </tr>
                    <?php
                  }
               ?>
            </tbody>
            <tfoot>
            <tr>
              <th>No</th>
              <th>Name</th>
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
