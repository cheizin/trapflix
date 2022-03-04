<?php
session_start();
include("controllers/setup/connect.php");

if(!$_SERVER['REQUEST_METHOD'] == "GET")
{
  exit();
}

if(!isset($_GET['token']))
{
  exit("Select feeds from the Home Page");
  ?>
<script>
  window.location.href = "index.php";
  </script>

  <?php

}

//check existence of video token
$token = mysqli_real_escape_string($dbc,strip_tags($_GET['token']));
$check = mysqli_query($dbc,"SELECT token FROM feeds WHERE token='".$token."'");
if(mysqli_num_rows($check) < 1)
{
    header("Location:home.php");
}
$feeds_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM feeds WHERE token ='".$token."'"));

$feeds_id = $feeds_description['id'];

//get user ip

//$ip = file_get_contents('https://api.ipify.org');

$ip = $_SERVER['REMOTE_ADDR'];

/*select to see if the same user is viewing the same video again
$check = mysqli_query($dbc,"SELECT video_id,ip FROM video_views WHERE video_id='".$feeds_id."' && ip='".$ip."'");
if(mysqli_num_rows($check) < 1)
{
  $sql_insert_count = mysqli_query($dbc,"INSERT INTO video_views (video_id,ip) VALUES ('".$feeds_id."','".$ip."')");
}

*/

  $sql_insert_count = mysqli_query($dbc,"INSERT INTO video_views (video_id,ip) VALUES ('".$feeds_id."','".$ip."')");

$feeds_views = mysqli_num_rows(mysqli_query($dbc,"SELECT video_id FROM video_views WHERE video_id='".$feeds_id."'"));

?>
<!doctype html>
<html lang="en-US">

<head>

<?php
include("views/header.php");
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0, user-scalable=yes">
<style>
.embed-youtube {
   position: relative;
   padding-bottom: 56.25%; /* - 16:9 aspect ratio (most common) */
   /* padding-bottom: 62.5%; - 16:10 aspect ratio */
   /* padding-bottom: 75%; - 4:3 aspect ratio */
   padding-top: 30px;
   height: 0;
   overflow: hidden;
}

.embed-youtube iframe,
.embed-youtube object,
.embed-youtube embed {
   border: 0;
   position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
}
</style>
</head>

<body>
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- Header -->
   <!-- Header -->
   <header id="main-header">
     <?php
     include("views/navigation.php");
     ?>
   </header>
   <!-- Header End -->


         <!-- video details -->
         <div class="main-content movi">
            <section class="movie-detail container-fluid">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="trending-info g-border">
      
      <br/>  <br/>  <br/>  <br/>
      
      <div class="row">
          <div class="col-lg-3 col-xs-12 form-group">
             <a href="#" class="iq-user-dropdown search-toggle p-0 d-flex align-items-center" data-toggle="search-toggle">
                  <img src="images/favoriteFeeds/<?php echo $feeds_description['thumbnail']; ?>" loading="lazy" width="250" height="250" alt="">
              </a>
          </div>
      
      <div class="col-lg-9 col-xs-12 form-group">
        <strong>  <p class="trending-dec w-100 mb-0 text-white"><?php echo $feeds_description['feeds_header']; ?></p> </strong> 

        <br/>

         <p class="trending-dec w-100 mb-0 text-white"><?php echo $feeds_description['feeds_description']; ?></p> 
      </div>
      
      </div>
                                </br>
                                       <ul class="p-0 list-inline d-flex align-items-center movie-content">
                           <li class="text-white"><?php echo $feeds_views;?> Views</li>
                             <li class="text-white"><time class="timeago" datetime="<?php echo $feeds_description['time_created'];?>"><?php echo $feeds_description['time_created'];?></time></li>
      
                        </ul>
      
                     </div>
                  </div>
               </div>
            </section>
      
            </div>
      


                  <!-- Subscribe and comments section -->
   <div class="main-content movi">
      <section class="movie-detail container-fluid">
         <div class="row">
            <div class="col-lg-4">
               <div class="trending-info g-border">
                  <form id="comment-form" class="mt-4">
                    <input type="hidden" value="add-comment" name="add-comment">
                    <input type="hidden" name="youtube_vid" value="<?php echo $feeds_description['id'] ;?>" >
                    <input type="hidden" name="name" value="<?php echo $_SESSION['name'];?>">
                    <input type="hidden" name="token2" value="<?php echo $_GET['token'];?>">
                    <div class="row">
                        <div class="col-lg-1 col-xs-12 form-group">
                           <a href="#" class="iq-user-dropdown search-toggle p-0 d-flex align-items-center" data-toggle="search-toggle">
                                <img src="images/user/user.jpg" class="img-fluid avatar-40 rounded-circle" alt="user">
                            </a>
                        </div>

                    <div class="col-lg-11 col-xs-12 form-group">
                        <textarea name="comment_name" class="form-control" placeholder="Add a public comment" required></textarea>
                    </div>

             </div>
              <div class="row">
                  <div class="col-md-12 text-center">
                      <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">Comment</button>
                  </div>
            </div>
            </form>
               </div>
            </div>

              <div class="col-lg-8 video_comments">
                <?php
                $comments_no = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM videos_comments WHERE video_id ='".$feeds_description['id']."'"));
?>

 <strong><?php echo $comments_no;?> </strong> Comments

 <table id="example" class="table table-striped table-bordered video_comments-" style="width:100%">

   <thead>
     <tr>


    <!--   <td>Status</td> -->
     </tr>
   </thead>
                <?php
                $no = 1;
                 $sql33 = mysqli_query($dbc,"SELECT * FROM videos_comments WHERE video_id ='".$feeds_description['id']."' ORDER BY id DESC" );
                 while($row33 = mysqli_fetch_array($sql33)){
                 ?>

                 <tr style="cursor: pointer;">


  <td>
    <?php echo $row33['commentor_name']; ?>
  </td>

  <td>
<?php echo $row33['comment_name']; ?>
  </td>
<td>
<time class="timeago" datetime="<?php echo $row33['time_recorded'];?>"><?php echo $row33['time_recorded'];?></time>
</td>
</tr>
<?php
}
?>

</table>
              </div>
         </div>
      </section>

      </div>



   <footer class="mb-0">
      <div class="container-fluid">
         <div class="block-space">
            <div class="row">
               <div class="col-lg-3 col-md-4">
                  <ul class="f-link list-unstyled mb-0">
                     <li><a href="#">About Us</a></li>
                     <li><a href="#l">Movies</a></li>

                  </ul>
               </div>
               <div class="col-lg-3 col-md-4">
                  <ul class="f-link list-unstyled mb-0">
                     <li><a href="#">Privacy Policy</a></li>
                     <li><a href="#">Terms & Conditions</a></li>
                     <li><a href="#">Help</a></li>
                  </ul>
               </div>

               <div class="col-lg-3 col-md-12 r-mt-15">
                  <div class="d-flex">
                     <a href="#" class="s-icon">
                        <i class="ri-facebook-fill"></i>
                     </a>
                     <a href="#" class="s-icon">
                        <i class="ri-skype-fill"></i>
                     </a>
                     <a href="#" class="s-icon">
                        <i class="ri-linkedin-fill"></i>
                     </a>
                     <a href="#" class="s-icon">
                        <i class="ri-whatsapp-fill"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="copyright py-2">
         <div class="container-fluid">
            <p class="mb-0 text-center font-size-14 text-body">TRAPFLIX - 2021 All Rights Reserved</p>
         </div>
      </div>
   </footer>
   <!-- MainContent End-->
   <!-- back-to-top -->
   <div id="back-to-top">
      <a class="top" href="#top" id="top"> <i class="fa fa-angle-up"></i> </a>
   </div>
   <!-- back-to-top End -->
   <!-- jQuery, Popper JS -->
   <script src="js/jquery-3.4.1.min.js"></script>

   <script>
 function ContentPage(id){
     location.href = "movie-channel.php?id="+id;
 }
       </script>
       <script>
     function ContentPage2(id){
         location.href = "youtube-channel.php?token="+token;
     }
           </script>
           <script>
       function Selectvideos2(token){
       location.href = "Youtube.php?token="+token;
       }
         </script>

       <script>
 function Selectvideos(token){
   location.href = "movie-details.php?token="+token;
 }
     </script>

     <script>
     $(document).on("submit", "#comment-form", function(e){
         e.preventDefault();

         var process_url = 'controllers/comments/commentsController.php';
         var token = '<?php echo $_GET['token'];?>';
         var form_data = $('#comment-form').serializeArray();
         var form_method = 'POST';


         $.ajax({
             url:process_url,
             data:form_data,
             method:form_method,

             success: function(data){

                 if(data == "success")
                 {
          //alert("Video Comment Saved Successfully");
                  // window.location.href = "movie-details.php?token="+token;


                      //alert("Login Successfully");
                     //fetch dynamic navbar
                     $.ajax({
                       url: 'controllers/comments/getcomments.php',
                       method:"POST",
                       data: form_data,
                       success: function(video_comments){
                         $('.video_comments').html(video_comments);
                       },
                       error: function(){
                         $('.video_comments').html('please reload page');
                       }
                     });
                 }
                 else if(data == "invalid")
                 {
                 console.log(data);


               //  Toast.fire({
                     //icon: 'error',
                   //  title: 'Invalid Credentials'
                 //  });
                 }
                 else
                 {
                      alert("system Error");
                     console.log(data);
                         $('.toast').toast('show');

                 }

             },
             error: function(xhr)
             {
               $.unblockUI();
               Toast.fire({
                         icon: 'error',
                         title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
                 });
             }

         });
     });

     $(document).on("submit", "#subscribe-form", function(e){
        e.preventDefault();

        var process_url = 'controllers/subscribe/subscribeController.php';


                  var token = '<?php echo $_GET['token'];?>';
        var form_data = $('#subscribe-form').serializeArray();
        var form_method = 'POST';


        $.ajax({
            url:process_url,
            data:form_data,
            method:form_method,

            success: function(data){

                if(data == "success")
                {
                    $('subscribe-form').html('');
                    $.ajax({
                      url: 'controllers/subscribe/getsubscribers.php',
                      method:"POST",
                      data : form_data,
                      success: function(s_data){
                        $('.subscription-area').html(s_data);
                        console.log(returned_navbar_data);
                      },
                      error: function(){
                        $('.subscription-area').html('please reload page');
                      }
                    });
                }
                else if(data == "invalid")
                {
                console.log(data);


              //  Toast.fire({
                    //icon: 'error',
                  //  title: 'Invalid Credentials'
                //  });
                }
                else
                {
                     alert("system Error");
                    console.log(data);
                        $('.toast').toast('show');

                }

            },
            error: function(xhr)
            {
              $.unblockUI();
              Toast.fire({
                        icon: 'error',
                        title: '<i class"fal fa-wifi-slash"></i> Unstable Network. Please try again'
                });
            }

        });
    });

     </script>

         <script src="https://timeago.yarp.com/jquery.timeago.js"></script>
    <script>
$('.timeago').timeago();
</script>
   <script src="js/popper.min.js"></script>
   <!-- Bootstrap JS -->
   <script src="js/bootstrap.min.js"></script>
   <!-- Slick JS -->
   <script src="js/slick.min.js"></script>
   <!-- owl carousel Js -->
   <script src="js/owl.carousel.min.js"></script>
   <!-- select2 Js -->
   <script src="js/select2.min.js"></script>
   <!-- Magnific Popup-->
   <script src="js/jquery.magnific-popup.min.js"></script>
   <!-- Slick Animation-->
   <script src="js/slick-animation.min.js"></script>
   <!-- Custom JS-->
  <script src="js/custom.js?v=38"></script>
</body>

</html>
