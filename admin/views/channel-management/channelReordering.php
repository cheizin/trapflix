
<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
//  $token = bin2hex(random_bytes(20));
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/


?>

<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Feeds Category Management</li>
     </ol>
</nav>

<link rel="stylesheet" type="text/css" href="bootstrap-darkly.min.css">
 
<div class="container" style="margin-top: 200px;">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <h1>Change bitrate</h1>
 
            <form method="POST" enctype="multipart/form-data" action="change-bitrate.php">
                <div class="form-group">
                    <label>Select video</label>
                    <input type="file" name="video" class="form-control" required="" accept="video/*">
                </div>
 
                <div class="form-group">
                    <label>Select bitrate</label>
                    <select name="bitrate" class="form-control">
                        <option value="350k">240p</option>
                        <option value="700k">360p</option>
                        <option value="1200k">480p</option>
                        <option value="2500k">720p</option>
                        <option value="5000k">1080p</option>
                    </select>
                </div>
 
                <input type="submit" name="change_bitrate" class="btn btn-info" value="Change bitrate">
            </form>
        </div>
    </div>
</div>