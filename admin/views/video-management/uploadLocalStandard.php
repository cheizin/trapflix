<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Local Video Management</li>
     </ol>
</nav>

<div class="row">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        My Local Videos NB: Videos appear public upon verification by trapflix

        <button class="btn btn-link" style="float:right;"
                data-toggle="modal" data-target="#add-video-modal">
                <i class="fa fa-plus-circle"></i> Add Local Video
        </button>
      </div>
      <div class="card-body table-responsive">
          <div class="row">
        <?php
          $sql = mysqli_query($dbc,"SELECT * FROM videos WHERE youtube_vid ='server' && email='".$_SESSION['email']."' ORDER BY id DESC");
          
          if(mysqli_num_rows($sql) == 0)
          {
                       ?>
                <div class="alert alert-danger" role="alert">
                  <h4 class="alert-heading">No Videos!</h4>
                  <p>You haven't uploaded any videos yet. Create a channel first then upload a new video by clicking<a href="#" class="btn btn-link" data-toggle="modal" data-target="#add-video-modal"> Add Video</a> </p>
                  <hr>
                </div>
              <?php
          }
          else 
          {
                        while($row = mysqli_fetch_array($sql)){
                $video_name = $row['textname'] ;
                $channel_name = $row['title'] ;
                $time_created = $row['time_created'];
          ?>
          
          <div class="card col-md-4 mr-1 text-white dark">
  <img class="card-img" src="https://trapflix.com/images/favoriteCompressed/<?php echo $row['thumbnail'];?>" alt="Video Thumbnail">
  <div class="card-img-overlay">
      <div class="card-content" style="background: rgba(0,0,0, 0.7);">
          
          <h5 class="m-3"><?php echo $video_name;?> <sup>Video</sup></h5>
          <h5 class="m-3"><?php echo $channel_name;?> <sup>Channel</sup></h5>
          <h5 class="m-3"><time class="timeago" datetime="<?php echo $time_created;?>"><?php echo $time_created;?></time> <sup>Time</sup></h5>
          
       <!--   <h5 class="m-3"><i class="far fa-play-circle"></i> <a class="btn btn-link" href="https://trapflix.com/videos/<?php echo $row['videoname'];?>"> Play</a></h5> -->
          <h5 class="m-3 text-danger"><i class="fas fa-trash"></i><a href="#" class="btn btn-link" onclick="Closevideo('<?php echo $row['id'];?>');"> Delete </a></h5>
      </div>

  </div>
</div>

         <?php
            }
          }

          ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php
//fetch last reference number from database and auto increment it
$reference_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT MAX(id) AS ref_no FROM videos "));
//auto increment the fetched record
$reference_row = $reference_row['ref_no'];
//add programm name prefix, plus the auto incremented value
$reference_row = $reference_row+1;
?>

<!-- add stock modal -->

<div class="modal fade" id="add-video-modal">
<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Add New Video</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form id="upload-vidz-form" class="mt-4" enctype="multipart/form-data">
        <input type="hidden" value="upload-video" name="upload-video">
        
           <input type="hidden" value="server" name="youtube_vid">
 
            <input type="hidden" name="token" value="<?php echo uniqid();?>">
        <input type="hidden" name="email" value="<?php echo $_SESSION['name'];?>">

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
                $result = mysqli_query($dbc, "SELECT * FROM main_categories WHERE id !='1' && recorded_by ='".$_SESSION['name']."'  ORDER BY category_name ASC");
                echo '
                <select name="title" data-tags="true" class="select2 form-control" data-placeholder="Select channel" required>
                <option></option>';
                while($row = mysqli_fetch_array($result)) {
                    echo '<option value="'.$row['category_name'].'">'.$row['category_name']."</option>";
                }
                echo '</select>';
                ?>
            </div>

            <?php

          //  $reference_row2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos DESC limit 1"));

            $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos ORDER BY id DESC LIMIT 1"));

            ?>
                                    <div class="col-lg-4 col-xs-12">
                                        <label>Upload Thumbnail (Thumbnail size should be 200px by 300 px) </label>
                                        <div class="col-lg-4 col-xs-12 form-group" style="position: relative;" >
                                        <span class="img-div">

                                                <img src="https://trapflix.com/admin/images/favorite/traphouse.jpg" width="100" height="100"class="img-circle" alt="Clear here to Upload Your Image" onClick="triggerClick()" id="profileDisplay">
                                              </span>
                                              <input type="file" name="thumbnail" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">

                                        </div>

                                    </div>
                                    <?php

                                  //  $reference_row2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos DESC limit 1"));

                                    $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos ORDER BY id DESC LIMIT 1"));

                                    ?>
           
                                                            
                                                                      <div class="col-lg-8 col-xs-12">
              <label>Upload Local Video</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file project-file">
                        <i class="fal fa-file-alt"></i>  Browse &hellip; <input type="file" name="videoname" class="form-control" single>
                    </span>
                </span>
              </div>
              <input type="text" class="form-control bg-white" readonly>
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

function triggerClick2(e) {
  document.querySelector('#profileImage2').click();
}
function displayImage2(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#profileDisplay2').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}
</script>
<script>
$('.timeago').timeago();
</script>


<!-- end o
<!-- end of add project modal -->