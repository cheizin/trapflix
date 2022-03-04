<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
//  $token = bin2hex(random_bytes(20));

 $token = rand(20);
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Banner Video Management</li>
     </ol>
</nav>

<div class="row">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
      Banner Videos List

      </div>
      <div class="card-body table-responsive">

        <?php
        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM banner_videos ORDER BY id ");

        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

          <table class="table table-striped table-hover" id="staff-users-table">
         <thead>
           <tr>
             <td>#</td>
             <td>Video Name</td>
             <td>Channel Name</td>
             <td>Owner</td>
                <td>Thumbnail</td>
             <td>Play Video</td>
              <td>Change Banner</td>

          <!--   <td>Status</td> -->
           </tr>
         </thead>
         <?php
         $no = 1;
          $sql = mysqli_query($dbc,"SELECT * FROM banner_videos ORDER BY id ");
          while($row = mysqli_fetch_array($sql)){
        $video_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row['video_id']."'"));

          ?>
         <tr style="cursor: pointer;">
           <td width="50px"> <?php echo $no++;?>.

           </td>

           <td><?php echo $video_name['textname'] ;?></td>
           <td><?php echo $video_name['title'] ;?></td>
           <td>
             <?php

                  $result = mysqli_query($dbc, "SELECT * FROM users WHERE email  ='".$video_name['email']."' ORDER BY id "  );
                  if(mysqli_num_rows($result))
                  {
                    while($project= mysqli_fetch_array($result))
                    {

                       echo $project['name'];

                    }
                  }
                  ?>
                </td>

                <td>    <a href="images/favorite/<?php echo $row['thumbnail'];?>" target="_blank">

               <?php echo $video_name['thumbnail'];?>

                </a>
              </td>

                <td>    <a href="https://www.youtube.com/watch?v=<?php echo $video_name['videoname'] ;?>" target="_blank">

               <?php echo $video_name['textname'];?>

                </a>
              </td>

              <td>
  <button type="button" class="btn btn-link" data-toggle="modal" data-target="#edit-banner-modal-<?php echo $row['id'];?>">
change
 </button>


 <!-- edit project modal -->
 <div class="modal fade" id="edit-banner-modal-<?php echo $row['id'];?>">
 <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
   <div class="modal-content">
     <div class="modal-header">
       <h5 class="modal-title">Changing Banner Video<strong>: <?php echo $video_name['textname'] ;?></strong></h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>
     <div class="modal-body">
       <form id="edit-banner-form-<?php echo $row['id'];?>" class="mt-4" onsubmit="ModifyBanner('<?php echo $row['id'];?>');">
         <input type="hidden" value="edit_banner" name="edit_banner">
         <input type="hidden" value="<?php echo $row['id'];?>" name="id">

             <div class="col-lg-12 col-xs-12 form-group">
               <label for="video_name"><span class="required">*</span>Video List</label>

               <select name="video_id" id="video_id-<?php echo $row['id'];?>" class="select2 form-control">
               <option value="<?php echo $row['id'];?>" selected>Select video</option>
               <?php
               $result = mysqli_query($dbc, "SELECT * FROM videos WHERE title !='Youtube Videos'");
               while($row_result = mysqli_fetch_array($result)) {
                   ?><option value="<?php echo $row_result['id'];?>"><?php echo $row_result['textname'];?></option><?php
               }
               ?>
               </select>
             </div>

         <!-- end row project owenerships -->
         <div class="row">
           <small class="status-project-user text-success"></small><br/>
         </div>

         <!-- start row related activity -->
         <div class="row  mb-4">


         </div>
         <!-- end row related activity -->

         <div class="pull-left mt-4">
           <small class="text-muted">Modified by:- <?php echo $_SESSION['name'];?></small>
         </div>

               <!-- start row button -->
         <div class="row">
           <div class="col-md-12 text-center">
               <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">Modify
               </button>
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
 <!-- end of edit project modal -->

</td>


         </tr>
         <?php
            }
          ?>

          <tfoot style="background:silver;">
                  <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                          <th></th>
                      <th></th>
                      <th></th>
                      <th></th>


                  </tr>
              </tfoot>
       </table>
      </div>
    </div>
  </div>
</div>

<?php
}
else
{
      ?>
    <br/>
<div class="alert alert-info">
<strong><i class="fa fa-info-circle"></i> No Banner videos</strong>
</div>

  <?php
}
?>

<?php
//fetch last reference number from database and auto increment it
$reference_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT MAX(id) AS ref_no FROM videos "));
//auto increment the fetched record
$reference_row = $reference_row['ref_no'];
//add programm name prefix, plus the auto incremented value
$reference_row = $reference_row+1;
?>

<!-- add stock modal -->

<div class="modal fade" id="add-youtube-video-modal">
<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">

      <h5 class="modal-title">Add New Youtube Video</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

        <form id="add-youtube-form" class="mt-4" enctype="multipart/form-data">

        <input type="hidden" name="add-youtube-list" value="add-youtube-list">
        <input type="hidden" name="reference_no" id="reference_no" class="form-control" value="<?php echo $reference_row;?>" readonly="readonly">


        <input type="hidden" name="email" value="<?php echo $_SESSION['name'];?>">

            <input type="hidden" name="token" value="<?php echo uniqid();?>">


        <div class="row border-bottom mx-3">


            <div class="col-lg-4 col-xs-12 form-group">
                <label for="textname"><span class="required">*</span>Video Name</label>

                  <textarea name="textname" class="form-control" required></textarea>

            </div>
            <div class="col-lg-4 col-xs-12 form-group">
                <label for="video_description"><span class="required">*</span>Video Description</label>

                  <textarea name="video_description" class="form-control" required></textarea>
            </div>

            <div class="col-lg-4 col-xs-12 form-group">
                <label><span class="required">*</span>Channel Name</label>
                <?php
                $result = mysqli_query($dbc, "SELECT * FROM categories WHERE channel_type ='youtube'  ORDER BY name ASC");
                echo '
                <select name="title" data-tags="true" class="select2 form-control" data-placeholder="Select channel" required>
                <option></option>';
                while($row = mysqli_fetch_array($result)) {
                    echo '<option value="'.$row['name'].'">'.$row['name']."</option>";
                }
                echo '</select>';
                ?>
            </div>




        </div>

        <div class="row border-bottom mx-3">


            <div class="col-lg-4 col-xs-12 form-group">
                <label for="textname"><span class="required">*</span>Channel Link</label>

                  <textarea name="videoname" class="form-control" required></textarea>

            </div>
            <?php

          //  $reference_row2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos DESC limit 1"));

            $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos ORDER BY id DESC LIMIT 1"));

            ?>
                                    <div class="col-lg-6 col-xs-12">
                                        <label>Click Thumbnail </label>
                                        <div class="col-lg-4 col-xs-12 form-group" style="position: relative;" >
                                        <span class="img-div">

                                                <img src="https://localhost/adflix/images/favorite/<?php echo $single_product['thumbnail'];?>" width="100" height="100"class="img-circle" alt="Clear here to Upload Your Image" onClick="triggerClick()" id="profileDisplay">
                                              </span>
                                              <input type="file" name="emp_photo" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">

                                        </div>

                                    </div>




          </div>
        <!-- start row project timelines -->


              <div class="row border-bottom mx-2">


                  <div class="pull-left mt-4">
                    <small class="text-muted">Recorded by:- <?php echo $_SESSION['name'];?></small>
                  </div>
                    </div>


        <!-- end row project timelines -->





              <!-- start row button -->
        <div class="row">
          <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">UPLOAD</button>
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


<script>
function triggerClick(e) {
  document.querySelector('#profileImage').click();
}
function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}
</script>
<!-- end of add project modal -->
