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
       <li class="breadcrumb-item active" aria-current="page">Admin Portal : Backup</li>
     </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light"> <i class="fad fa-database"></i> Database Backup</div>
            <div class="card-body">
                <form id="create-backups-form" class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <div id="loader"></div>
                      <select name="select-backup-location" class="select2 form-control" required>
                            <option value=""> -- Select Location -- </option>
                            <option value="C:\Backups\Version2\Database\">C:\Backups\Version2\Database</option>
                            <option value="C:\Apache24\htdocs\p\backups\database\">C:\Apache24\htdocs\p\backups\database</option>
                      </select>
                    </div>
                  </div>
                  <br/><br/>
                  <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" id="submit-backup-button" class="btn btn-primary btn-block"><i class="fas fa-cloud-upload"></i> SUBMIT</button>
                        </div>
                  </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light"> <i class="fal fa-laptop-code"></i> System Backup</div>
            <div class="card-body">
              <form id="system-backup-form" class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <select name="system-backup-location" class="select2 form-control" required>
                          <option value=""> -- Select Location -- </option>
                          <option value="C:\Backups\Version2\System\">C:\Backups\Version2\System\</option>
                    </select>
                  </div>
                </div>
                <br/><br/>
                <div class="row">
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-cloud-upload"></i> SUBMIT</button>
                      </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
  <div class="col-md-6">
      <div class="card">
          <div class="card-header bg-light"> <i class="fad fa-database text-success"></i> Available Database Backups</div>
          <div class="card-body">
            <table id="database-backup-table" class="table table-hover table-responsive table-bordered table-striped" width="100%">
              <thead>
              <tr>
                <th>No</th>
                <th>Backup Location</th>
                <th>Download</th>
              </tr>
              </thead>
              <tbody>
                <?php
                    $no = 1;
                    $sql = mysqli_query($dbc,"SELECT * FROM backups WHERE backup_type='database' ORDER BY id DESC");
                    while($row = mysqli_fetch_array($sql))
                    {?>
                      <tr>
                        <td><?php echo $no++ ;?></td>
                        <td>
                          <?php
                          if (file_exists(addslashes($row['backup_location'])))
                          {
                            ?>
                            <p class="text text-success" title="File Available">
                                <?php echo $row['backup_location'];?>
                            </p>
                              <?php
                          }else{
                                ?>
                                <p class="text-danger" title="File Unavailable">
                                    <del><?php echo $row['backup_location'];?></del>
                                </p>
                                <?php
                              }
                              ?>
                          </td>
                          <td>

                            <?php
                            if (file_exists(addslashes($row['backup_location'])))
                            {
                              ?>
                              <form method="post" target="_blank" action="controllers/admin-portal/DownLoadBackup.php">
                                <input type="hidden" name="download_id" value="<?php echo $row['id'];?>">

                                <button type="submit" class="btn btn-success" title="File Available. Click to Download">
                                    <i class="far fa-cloud-download-alt"></i>
                                </button>

                              </form>
                                <?php
                            }else{
                                  ?>
                                      <button type="submit" class="btn btn-danger" title="File Unavailable" disabled>
                                        <i class="far fa-cloud-download-alt"></i>
                                    </button>
                                  <?php
                                }
                                ?>

                          </td>
                      </tr>
                      <?php
                    }
                 ?>
              </tbody>
              <tfoot>
              <tr>
                <th>No</th>
                <th>Backup Location</th>
                <th>Download</th>
              </tr>
              </tfoot>
            </table>
          </div>
      </div>
  </div>


  <div class="col-md-6">
      <div class="card">
          <div class="card-header bg-light"> <i class="fal fa-laptop-code text-success"></i> Available System Backups</div>
          <div class="card-body">
            <table id="system-backup-table" class="table table-hover table-responsive table-bordered table-striped" width="100%">
              <thead>
              <tr>
                <th>No</th>
                <th>Backup Location</th>
                <th>Download</th>
              </tr>
              </thead>
              <tbody>
                <?php
                    $no = 1;
                    $sql = mysqli_query($dbc,"SELECT * FROM backups WHERE backup_type='system' ORDER BY id DESC");
                    while($row = mysqli_fetch_array($sql))
                    {?>
                      <tr>
                        <td><?php echo $no++ ;?></td>
                        <td>
                          <?php
                          if (file_exists(addslashes($row['backup_location'])))
                          {
                            ?>
                            <p class="text text-success" title="File Available">
                                <?php echo $row['backup_location'];?>
                            </p>
                              <?php
                          }else{
                                ?>
                                <p class="text-danger" title="File Removed">
                                    <del><?php echo $row['backup_location'];?></del>
                                </p>
                                <?php
                              }
                              ?>
                          </td>
                          <td>

                            <?php
                            if (file_exists(addslashes($row['backup_location'])))
                            {
                              ?>
                              <form method="post" target="_blank" action="controllers/admin-portal/DownLoadBackup.php">
                                <input type="hidden" name="download_id" value="<?php echo $row['id'];?>">

                                <button type="submit" class="btn btn-success" title="File Available. Click to Download">
                                    <i class="far fa-cloud-download-alt"></i>
                                </button>

                              </form>
                                <?php
                            }else{
                                  ?>
                                      <button type="submit" class="btn btn-danger" title="File Removed" disabled>
                                        <i class="far fa-cloud-download-alt"></i>
                                    </button>
                                  <?php
                                }
                                ?>

                          </td>
                      </tr>
                      <?php
                    }
                 ?>
              </tbody>
              <tfoot>
              <tr>
                <th>No</th>
                <th>Backup Location</th>
                <th>Download</th>
              </tr>
              </tfoot>
            </table>
          </div>
      </div>
  </div>
</div>
