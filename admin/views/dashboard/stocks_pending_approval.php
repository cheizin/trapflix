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
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Contact</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
     $sql = mysqli_query($dbc,"SELECT * FROM users  ORDER BY user_status DESC");
     while($row = mysqli_fetch_array($sql)){
     ?>
          <tr style="cursor: pointer;">
            <th scope="row"><?php echo $no++;?></th>
            <td> <small><?php echo $row['name'] ;?></small></td>
                <td> <small><?php echo $row['email'] ;?></small></td>
                    <td> <small><?php echo $row['mobile'] ;?></small></td>
                        <td> <small><?php echo $row['user_status'] ;?></small></td>



          </tr>
          <?php
        }
     ?>
  </tbody>
</table>
<script>
$("[data-toggle=popover]").popover();
</script>
