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
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Admin Portal : Users</li>
     </ol>
</nav>

<ul class="nav nav-fill nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active database_users_tab" data-toggle="pill" href="#datatabase_users_tab" role="tab"  aria-selected="true">Database Users</a>
  </li>
  <li class="nav-item" style="cursor:pointer;">
    <a class="nav-link ldap_users_tab" data-toggle="modal" data-target="#user-password-modal" role="tab"  aria-selected="false">LDAP Active Users</a>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="database_users_tab" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                  <span style="float:right">
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#add-user-modal">
                      <i class="far fa-user-plus"></i> Add User
                    </button>
                  </span>
                </div>
                <div class="card-body table-responsive">
                                   <?php
                  $sql_query = mysqli_query($dbc,"SELECT * FROM staff_users ORDER BY Name ASC");
                  $number = 1;
                  if($total_rows = mysqli_num_rows($sql_query) > 0)
                  {?>
                  <table class="table table-striped table-hover" id="staff-users-table">
                    <thead>
                      <tr>
                        <td>NO</td>
                        <td>Employee No</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Department</td>
                        <td>Designation</td>
                        <td>Status</td>
                        <td>Access Level</td>
                      </tr>
                    </thead>
                    <?php
                    while($row = mysqli_fetch_array($sql_query))
                    {
                      if($row['status'] == 'suspended')
                      {
                        $class_suspended = 'bg-danger';
                      }
                      else
                      {
                        $class_suspended ='';
                      }

                      ?>
                    <tr style="cursor: pointer;" class="<?php echo $class_suspended;?>">
                      <td><?php echo $number++;?></td>
                      <td>
                        <button type="button" class="btn btn-link"
                                data-toggle="modal"
                                data-target="#edit-user-modal-<?php echo $row['EmpNo'];?>">

                                <?php echo $row['EmpNo'];?>
                        </button>
                      </td>
                      <td><?php echo $row['Name'];?></td>
                      <td><?php echo $row['Email'];?></td>
                      <td><?php echo $row['DepartmentCode'];?></td>
                      <td><?php echo $row['designation'];?></td>
                      <td><?php echo $row['status'];?></td>
                      <td><?php echo $row['access_level'];?></td>

                      <!-- start edit user Modal -->
                        <div class="modal fade" id="edit-user-modal-<?php echo $row['EmpNo']; ?>" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Edit User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                              <form id="update-user-settings2-form-<?php echo $row['EmpNo'];?>">
                                 <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                       <div class="row">
                                          <div class="col-lg-6 col-xs-12 form-group">
                                             <label for="emp_no">Employee No</label>
                                             <input type="text" class="form-control" name="emp_no" id="emp_no-<?php echo $row['EmpNo'];?>" value="<?php echo $row['EmpNo'];?>" readonly="true">
                                          </div>
                                          <div class="col-lg-6 col-xs-12 form-group">
                                             <label for="name">Name</label>
                                             <input type="text" class="form-control" id="name-<?php echo $row['EmpNo'];?>" name="name" value="<?php echo $row['Name'];?>" readonly="true">
                                          </div>
                                          <div class="col-lg-6 col-xs-12 form-group">
                                             <label for="email">Email</label>
                                             <input type="text" class="form-control" name="email" id="email-<?php echo $row['EmpNo'];?>" value="<?php echo $row['Email'];?>" readonly="true">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- /.end row programme status -->
                                 <!-- start row -->
                                 <div class="row">
                                    <div class="col-lg-6 col-xs-12 form-group">
                                       <label for="department">Department</label>
                                       <?php
                                          $result = mysqli_query($dbc, "SELECT * FROM departments ORDER BY department_name ASC");
                                          echo '
                                          <select name="department" id="department-'.$row['EmpNo'].'" class="select2 form-control">
                                          <option value="'.$row['DepartmentCode'].'" selected>'.$row['DepartmentCode'].'</option>';

                                          while($row_dep = mysqli_fetch_array($result)) {
                                              echo '<option value="'.$row_dep['department_id'].'">'.$row_dep['department_name']."</option>";
                                          }
                                          echo '</select>';
                                          ?>
                                    </div>
                                    <div class="col-lg-6 col-xs-12 form-group">
                                       <label for="designation">Designation</label>
                                       <input type="text" name="designation"
                                              id="designation-<?php echo $row['EmpNo'];?>" class="form-control" value="<?php echo $row['designation'];?>" required>
                                    </div>
                                    <div class="col-lg-6 col-xs-12 form-group">
                                       <label for="access_level">Access Level</label>
                                       <select class="select2 form-control" name="access_level" id="access_level-<?php echo $row['EmpNo'];?>">
                                          <option value="<?php echo $row['access_level'];?>" selected><?php echo $row['access_level'];?></option>
                                          <option value="superuser">SUPERUSER</option>
                                          <option value="standard">STANDARD</option>
                                          <option value="admin">ADMIN</option>
                                          <option value="director">DIRECTOR</option>
                                       </select>
                                    </div>
                                    <div class="col-lg-6 col-xs-12 form-group">
                                       <label for="status">Status</label>
                                       <select class="select2 form-control" name="status" id="status-<?php echo $row['EmpNo'];?>">
                                          <option value="<?php echo $row['status'];?>" selected><?php echo $row['status'];?></option>
                                          <option value="active">active</option>
                                          <option value="suspended">suspended</option>
                                       </select>
                                    </div>
                                 </div>
                                 <!-- end row  -->
                                 <!-- start row button -->
                                 <div class="row">
                                    <div class="col-md-12 text-center">
                                       <button type="submit" class="btn btn-primary btn-block" onclick="EditUser('<?php echo $row['EmpNo'];?>');">SUBMIT</button>
                                    </div>
                                 </div>
                                 <!-- end row button -->
                              </form>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- end of edit user modal -->
                    </tr>
                    <?php
                    }
                    ?>
                    <tfoot>
                        <tr>
                            <th>NO</th>
                            <th>Employee No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th>Access Level</th>
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
  </div>

  <div class="tab-pane fade" id="ldap_users_tab" role="tabpanel"></div>
</div>
<!-- User LDAP PASSWORD Modal -->
<div class="modal fade" id="user-password-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="submit-password-form">
           <div id="feedback"></div>
           <br/>
           <div class="form-group">
              <input type="hidden" name="sign_in_name" value="<?php echo $_SESSION['sign_in_name'];?>">
              <label for="pwd">Password:</label>
              <input type="password" name="password" class="form-control" id="password" required>
           </div>
           <div class="row">
              <div class="col-md-12 text-center">
                 <button type="submit" class="btn btn-primary btn-block" id="submit-password-btn">SUBMIT</button>
              </div>
           </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- start add user Modal -->
<div class="modal fade" id="add-user-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-new-user-form">
              <!-- start of row -->
              <div class="row">
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="emp_no">Employee No</label>
                        <input type="text" name="emp_no" class="form-control" required>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" class="form-control" required>
                </div>
                 <div class="col-lg-6 col-xs-12 form-group">
                        <label for="access_level">Access Level</label>
                        <select class="select2 form-control" name="access_level">
                                  <option value="" disabled>-- Please Select --</option>
                                  <option value="standard">STANDARD</option>
                                  <option value="superuser">SUPERUSER</option>
                                  <option value="director">DIRECTOR</option>
                                  <option value="admin">ADMIN</option>
                        </select>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="department">Department</label>
                          <?php
                            $result = mysqli_query($dbc, "SELECT * FROM departments WHERE department_name!='' ORDER BY department_name ASC");
                            echo '
                            <select name="department" class="select2 form-control">';

                            while($row = mysqli_fetch_array($result)) {
                                echo '<option value="'.$row['department_id'].'">'.$row['department_name']."</option>";
                            }
                            echo '</select>';
                            ?>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" class="form-control" required>
                </div>
              </div>
              <!-- end of row -->


              <!-- start row button -->
              <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                    </div>
              </div>

              <!-- end row button -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end of add user modal -->
