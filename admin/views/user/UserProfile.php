<?php
session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  if(isset($_SESSION['email']))
  {
    ?>
<nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">  <button type="button" class="btn btn-link" data-toggle="modal" data-target="#change-password-modal">
            <i class="far fa-user-plus"></i> <b>Click Here To Change Password</b>
          </button> </li>

      </ol>
  </nav>
  <!-- start add user Modal -->
  <div class="modal fade" id="change-password-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="inventory-signup-form">
              <div class="card-header bg-light">
                Panorama Inventory Password Change 
              </div>
              <div class="card-body">
                <label for="email">Official Email address
                   <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                   title="Your Name associated to your Windows account, i.e MUser" id="name_help"></i></label>
                <input type="text" autocomplete="on" id="email" name="email" class="form-control" maxlength="70" value="<?php echo $_SESSION['email'];?>"readonly>
                <div class="row">
                  <br/>
                </div>
                <!--<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>-->
                  <label for="password">Enter Password
                    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                    title="Your Password associated to your Windows account" id=""></i></label>
                  <div class="input-group add-on">
                    <input type="password" name="password" id="password" maxlength="40" class="form-control pwd"  required placeholder="input your password">
                 

                  </div>
                  <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
                  <div class="row">

                  </div>
                  <label for="password">Confirm Password
                    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                    title="Your Password associated to your Windows account" id="password_help"></i></label>
                  <div class="input-group add-on">
                    <input type="password" id="confirm" name="confirm" maxlength="40" class="form-control pwd"  required placeholder="Confirm Your password">
                      

                  </div>
                  <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>

                 </div> <!-- form-group// -->
                 <div class="card-footer text-right">
                   <button type="submit" class="btn btn-primary btn-block submitting"> Change Password </button>
                 </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of add user modal -->
<div class="row">
    <!-- Profile Image -->
    <div class="card card-primary card-outline col-md-4 mr-4 ml-2">
      <div class="card-body box-profile">
        <div class="text-center">
          <?php

          $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE Email ='".$_SESSION['email']."'"));
          if(isset($_SESSION['profile_picture']))
          {
            ?>
            <img class="profile-user-img img-fluid img-circle"
                 src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['profile_picture']); ?>"
                 alt="User profile picture">
            <?php
          }
          else
          {
            ?>
            <img class="profile-user-img img-fluid img-circle"
                 src="assets/img/<?php echo $profile_pic['emp_photo']; ?>"
                 alt="User profile picture">
            <?php
          }


           ?>

        </div>

        <h3 class="profile-username text-center"><?php echo $_SESSION['name'] ;?><br/><small class="text-muted"><?php echo $_SESSION['email'] ;?></small></h3>


        <p class="text-muted text-center"><?php echo $_SESSION['designation'] ;?></p>

        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Employee No:</b> <a class="float-right"><?php echo $_SESSION['staff_id'] ;?></a>
          </li>
          <li class="list-group-item">
            <b>Department:</b> <a class="float-right"><?php echo $_SESSION['department'] ;?> | <?php echo $_SESSION['department_code'] ;?></a>
          </li>
          <li class="list-group-item">
            <b>Access Level:</b> <a class="float-right"><?php echo $_SESSION['access_level'] ;?></a>
          </li>
        </ul>

        <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <div class="col-md-7">
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab"><i class="fad fa-history"></i> My Activity Logs</a></li>
            <li class="nav-item"><a class="nav-link" href="#navigation" data-toggle="tab"><i class="fad fa-route"></i> My Recent Navigations</a></li>
            <li class="nav-item"><a class="nav-link" href="#access-logs" data-toggle="tab"><i class="fad fa-history"></i> My Login History</a></li>
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane show active" id="timeline">
              <div class="input-group mb-4">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white" id="search-addon"><i class="fad fa-search"></i></span>
                </div>
                <input type="text" class="form-control" name="search" id="search" placeholder="Search">
                <span id="search-loader"></span>
              </div>
              <div id="timeline-data">
              </div>
            <!--start pagination buttons -->
            <?php
            $limit = 5;
            $sql = "SELECT COUNT(id) FROM activity_logs WHERE Email='".$_SESSION['email']."' ORDER BY id DESC";
            $rs_result = mysqli_query($dbc, $sql);
            $row = mysqli_fetch_row($rs_result);
            $total_records = $row[0];
            $total_pages = ceil($total_records / $limit);
            ?>

            <nav aria-label="...">
            <ul class='pagination text-center pagination-sm table-responsive' id="pagination">
            <?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):
            			if($i == 1):?>
                        <li class='active' class="page-item"  id="<?php echo $i;?>">
                          <a class="page-link" href='TimeLinePaginated.php?page=<?php echo $i;?>'><?php echo $i;?></a>
                        </li>
            			<?php else:?>
            			<li class="page-item" id="<?php echo $i;?>"><a class="page-link" href='TimeLinePaginated.php?page=<?php echo $i;?>'><?php echo $i;?></a></li>
            		<?php endif;?>
            <?php endfor;endif;?>
          </ul>
        </nav>

            <!-- end pagination buttons -->
          </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="navigation">
              <div class="row">
                  <div class="col-12">
                    <div class="card card-primary card-outline">
                      <!-- /.card-header -->
                      <div class="card-body table-responsive p-0">
                        <table id="user-profile-navigations-table" class="table table-hover table-bordered table-striped" width="100%">
                          <thead>
                          <tr>
                            <th>No</th>
                            <th>Page Name</th>
                            <th>Time Navigated</th>
                          </tr>
                          </thead>
                          <tbody>
                            <?php
                                $no = 1;
                                $sql = mysqli_query($dbc,"SELECT * FROM page_requests WHERE requested_by='".$_SESSION['email']."' && user_type='default' ORDER BY id DESC");
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

                                  ?>
                                  <tr>
                                    <td><?php echo $no++ ;?></td>
                                    <td class="<?php echo $page_id;?> text-primary" style="cursor:pointer;"><?php echo $row['page_name'];?></td>
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
            </div>


            <div class="tab-pane" id="access-logs">
              <div class="row">
                  <div class="col-12">
                    <div class="card card-primary card-outline">
                      <!-- /.card-header -->
                      <div class="card-body table-responsive p-0">
                        <table id="user-profile-sign-in-logs-table" class="table table-bordered table-striped" width="100%">
                          <thead>
                          <tr>
                            <th>No</th>
                            <th>IP Address</th>
                            <th>Time Signed In</th>
                            <th>Time Signed Out</th>
                          </tr>
                          </thead>
                          <tbody>
                            <?php
                                $no = 1;
                                $sql = mysqli_query($dbc,"SELECT * FROM sign_in_logs WHERE email='".$_SESSION['email']."' && id > 4722 && user_type='default' ORDER BY id DESC");
                                while($row = mysqli_fetch_array($sql))
                                {
                                  ?>
                                  <tr>
                                    <td><?php echo $no++ ;?></td>
                                    <td><?php echo $row['ip_address'];?></td>
                                    <td><?php echo $row['time_signed_in'];?></td>
                                    <td><?php echo $row['time_signed_out'];?></td>
                                  </tr>
                                  <?php
                                }
                             ?>
                          </tbody>
                          <tfoot>
                          <tr>
                            <th>No</th>
                            <th>IP Address</th>
                            <th>Time Signed In</th>
                            <th>Time Signed Out</th>
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
            </div>
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->

<!-- /.card -->

<script>
$('#confirm').on('keyup', function () {
  if ($('#password').val() == $('#confirm').val()) {
    $('#password_help').html(' Password Matched').css('color', 'blue');
  } else
    $('#password_help').html('Not Matching').css('color', 'red');
});

</script>

</div>
    <?php
  }
  else
  {
    echo "unauthorised";
  }
}
else
{
  echo "form not submitted";
}


 ?>
