<?php
session_start();
include("controllers/setup/connect.php");

if(!isset($_SESSION['email']))
{

      ?>
<script>
  window.location.href = "https://trapflix.com";
  </script>

  <?php
}
?>

<!doctype html>
<html lang="en-US">
   <head>


<?php
include("views/header.php");
?>

   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
      <!-- Header -->
      <header id="main-header">
        <?php
        include("views/navigation.php");
        ?>

      </header>
      <!-- Header End -->

      <section>
          <?php
            if(isset($_GET['payment_success']))
            {
                echo $_GET['payment_success'];
            }
          ?>
      </section>
      <section>
          <?php
                $user_pay = mysqli_fetch_array(mysqli_query($dbc,"SELECT date_recorded FROM users_subscriptions WHERE user_id='".$_SESSION['id']."' && pesapal_notification_type='COMPLETED' ORDER BY id DESC LIMIT 1"));
                $date_paid = $user_pay['date_recorded'];

                $earlier = new DateTime($date_paid);

                $current_date = date('Y-m-d');
                $later = new DateTime($current_date);

                $days_remaining = $later->diff($earlier)->format("%a"); //3




//check subscription
require_once('payments/checkusersubscription.php');
//end check subscription

    ?>
      </section>
      <!-- Slider Start -->
      <section class="iq-main-slider p-0">
         <div id="tvshows-slider">
           <?php
      $sql_query1 =  mysqli_query($dbc,"SELECT * FROM banner_videos ORDER BY id");

      $number = 1;
      if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
      {?>

      <?php
      $no = 1;
      $sql = mysqli_query($dbc,"SELECT * FROM banner_videos ORDER BY id DESC");
      while($row = mysqli_fetch_array($sql)){
      $video_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row['video_id']."'"));
      ?>
            <div>

                  <div class="shows-img">



                           <div class="row align-items-center  h-100">


                              <div class="col-xl-6 col-lg-12 col-md-12">
                                 <a href="javascript:void(0);">

                                 </a>
                                 <h3 class="slider-text big-title title text-uppercase" data-animation-in="fadeInLeft"
                                    data-delay-in="0.6"><?php echo $video_name['textname'] ;?></h3>
                                 <div class="d-flex align-items-center" data-animation-in="fadeInUp" data-delay-in="1">
                                    <span class="badge badge-secondary p-2"></span>
                                    <span class="ml-3"></span>
                                 </div>
                                 <p data-animation-in="fadeInUp" data-delay-in="1.2"><?php echo $video_name['textname'] ;?>
                                 </p>
                                 <div class="d-flex align-items-center r-mb-23" data-animation-in="fadeInUp" data-delay-in="1.2">

                                   <?php
                                                   //$row3 = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM popular_videos WHERE popular_video_id ='".$row['id']."'"));
                                    $youtube_name = mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row['video_id']."' && youtube_vid ='youtube'") or die(mysqli_error($dbc));
                                    if(mysqli_num_rows($youtube_name) > 0){

                                   ?>

      <a onclick="Selectbanner('<?php echo $video_name['token'];?>');" class="btn btn-hover" title="Click Here to Play Video">Play Now </a>

                                    <?php
                                           }
                                           else
                                   {
                                     ?>
      <a onclick="Selectbanner2('<?php echo $video_name['token'];?>');" class="btn btn-hover" title="Click Here to Play Video">Play Now </a>

                                     <?php
                                   }


                                      ?>

                                 </div>
                              </div>

                              <div class="col-xl-6 col-lg-12 col-md-12">
    <img src="images/favoriteCompressed/<?php echo $video_name['thumbnail'];?>" loading="lazy" width="500" height="500">
                              </div>
                           </div>



                  </div>

            </div>



                        <?php
                 }
               ?>

                        <?php
                }
                else
                {
                ?>
                <br/>
                <div class="alert alert-info">
                <strong><i class="fa fa-info-circle"></i> No Movie has been added</strong>
                </div>

                <?php
                }
                ?>

         </div>

      </section>
      <!-- Slider End -->
      <!-- MainContent -->
      <div class="main-content">

        <!-- popular videos list -->

                <?php
        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM main_categories WHERE category_name ='Popular On Trapflix' ");

        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

          <?php
          $no = 1;
           $sql2 = mysqli_query($dbc,"SELECT * FROM main_categories WHERE category_name ='Popular On Trapflix' ORDER BY category_name ASC");
           while($row2 = mysqli_fetch_array($sql2)){

             $number = 1;

           ?>
                 <section id="iq-favorites"  class="iq-main-slider p-0">
                    <div class="container-fluid slide">
                       <div class="row">
                          <div class="col-sm-12 overflow-hidden">
                             <div class="iq-main-header d-flex align-items-center justify-content-between">
                                <h4 class="main-title"><?php echo $row2['category_name']; ?></h4>

                             </div>

                             <div class="favorites-contens">
                        <!--   <ul class="favorites-slider2 list-inline  row p-0 mb-0"> -->
                               <ul class="favorites-slider list-inline  row p-0 mb-0">

                                  <?php
                          $sql_query123 =  mysqli_query($dbc,"SELECT * FROM popular_videos ORDER BY id DESC");

                          $number = 1;
                          if($total_rows1 = mysqli_num_rows($sql_query123) > 0)
                          {?>
                                  <?php
                                  $no = 1;
                                   $sql3 = mysqli_query($dbc,"SELECT * FROM popular_videos ORDER BY id DESC" );
                                   while($row3 = mysqli_fetch_array($sql3)){
                                      $video_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row3['popular_video_id']."' ORDER BY id DESC"));
                                   ?>
                                   <li class="slide-item">

                                     <form method="post" action="movie-details.php">
                                       <input type="hidden" name="token" value="<?php echo $video_name['token']; ?>">
                                       <?php
                                                       //$row3 = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM popular_videos WHERE popular_video_id ='".$row['id']."'"));
                                        $youtube_name = mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row3['popular_video_id']."' && youtube_vid ='youtube'") or die(mysqli_error($dbc));
                                        if(mysqli_num_rows($youtube_name) > 0){

                                       ?>

         <a onclick="Selectvideos2('<?php echo $video_name['token'];?>');">

                                        <?php
                                               }
                                               else
                                       {
                                         ?>
          <a onclick="Selectvideos('<?php echo $video_name['token'];?>');">

                                         <?php
                                       }


                                          ?>


                                         <div class="block-images position-relative">
                                            <div class="img-box">
                                               <img src="images/favoriteCompressed/<?php echo $video_name['thumbnail']; ?>" loading="lazy" width="100%" height="100%" alt="">
                                            </div>
                                            <div class="block-description">
                                               <h4><?php echo $video_name['textname']; ?></h4>
                                               <div class="hover-buttons">
                                                  <span class="btn btn-hover">
                                                     <i class="fa fa-play mr-1" aria-hidden="true"></i>
                                                     Play Now
                                                  </span>
                                            </div>

                                         </div>


                                      </a>

                                       </form>
                                   </li>

                          <?php
                   }
                 ?>

                          <?php
                  }

                  ?>


                                </ul>
                             </div>

                          </div>
                       </div>
                    </div>
         <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44px" height="44px" id="circle"
               fill="none" stroke="currentColor">
               <circle r="20" cy="22" cx="22" id="test"></circle>
            </symbol>
         </svg>
           </section>
                 <?php

          }

        ?>

                 <?php
         }

         ?>


<!-- end popular list -->

        <?php
$sql_query1 =  mysqli_query($dbc,"SELECT * FROM major_channels  ORDER BY id");

$number = 1;
if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
{?>

  <?php
  $no = 1;
   $sql2 = mysqli_query($dbc,"SELECT * FROM major_channels ORDER BY id DESC");
   while($row2 = mysqli_fetch_array($sql2)){

     $channel_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM main_categories WHERE id ='".$row2['category_id']."'   && approved ='yes'"));
     $sql_query1234 =  mysqli_query($dbc,"SELECT * FROM videos WHERE title ='".$channel_name['category_name']."' ORDER BY id DESC limit 12 ");

     $number = 1;
     if($total_rows11 = mysqli_num_rows($sql_query1234) > 0)
     {


   ?>
         <section id="iq-favorites">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-12 overflow-hidden">
                     <div class="iq-main-header d-flex align-items-center justify-content-between">

                       <form method="post" action="Cordering.php">
                         <input type="hidden" name="id" value="<?php echo $row2['id']; ?>">
                        <h4 class="main-title" ata-toggle="tooltip"  title="This is a premium verified Channel account" 
                        onclick="ChannelDetails('<?php echo $row2['id'];?>');">
                            <a href="#"><?php echo $channel_name['category_name']; ?></a>
                             <img src="images/favoriteCompressed/<?php echo $row2['verified']; ?>" loading="lazy" data-toggle="tooltip"  
                             title="This is a premium verified Channel account" width="25" height="25" alt=""></h4>

   </form>
                     </div>

                     <div class="favorites-contens  contentSlider">
                <!--   <ul class="favorites-slider2 list-inline  row p-0 mb-0"> -->
                       <ul class="favorites-slider list-inline  row p-0 mb-0">

                          <?php
                  $sql_query123 =  mysqli_query($dbc,"SELECT * FROM videos WHERE title ='".$channel_name['category_name']."' ORDER BY id DESC limit 8");

                  $number = 1;
                  if($total_rows1 = mysqli_num_rows($sql_query123) > 0)
                  {?>
                          <?php
                          $no = 1;
                           $sql3 = mysqli_query($dbc,"SELECT * FROM videos WHERE title ='".$channel_name['category_name']."' ORDER BY id DESC limit 8" );
                           while($row3 = mysqli_fetch_array($sql3)){
                           ?>
                           <li class="slide-item">
                             <form method="post" action="movie-details.php">
                               <input type="hidden" name="token" value="<?php echo $row3['token']; ?>">
                               <?php
      $youtube_name = mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row3['id']."' && youtube_vid ='youtube' ORDER BY id DESC limit 8") or die(mysqli_error($dbc));

                                               //$row3 = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM popular_videos WHERE popular_video_id ='".$row['id']."'"));

                                if(mysqli_num_rows($youtube_name) > 0){

                               ?>

 <a onclick="Selectvideos2('<?php echo $row3['token'];?>');">

                                <?php
                                       }
                                       else
                               {
                                 ?>
  <a onclick="Selectvideos('<?php echo $row3['token'];?>');">

                                 <?php
                               }


                                  ?>


                                 <div class="block-images position-relative">
                                    <div class="img-box">
                                       <img src="images/favoriteCompressed/<?php echo $row3['thumbnail']; ?>" loading="lazy" width="100%" height="100%" alt="">

                                    </div>
                                    <div class="block-description">
                                       <h4><?php echo $row3['textname']; ?></h4>
                                       <div class="hover-buttons">
                                          <span class="btn btn-hover">
                                             <i class="fa fa-play mr-1" aria-hidden="true"></i>
                                             Play Now
                                          </span>
                                    </div>
                                    </div>

                                 </div>
                              </a>

                               </form>
                           </li>

                  <?php
           }
         ?>

                  <?php
          }

          ?>


                        </ul>
                     </div>

                  </div>
               </div>
            </div>

   </section>
         <?php
       }
           else {
             // code...
           }
  }

?>

         <?php
 }

 ?>
<!-- end of top channels listing -->



      </div>
      <footer class="mb-0">
         <div class="container-fluid">
            <div class="block-space">
               <div class="row">
                  <div class="col-lg-3 col-md-4">
                     <ul class="f-link list-unstyled mb-0">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Movies</a></li>

                        <li><a href="#">Coporate Information</a></li>
                     </ul>
                  </div>
                  <div class="col-lg-3 col-md-4">
                     <ul class="f-link list-unstyled mb-0">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Help</a></li>
                     </ul>
                  </div>
                  <div class="col-lg-3 col-md-4">
                     <ul class="f-link list-unstyled mb-0">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact us</a></li>
                        <li><a href="#">Legal Notice</a></li>
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
      <script>
    function ContentPage(id){
        location.href = "movie-channel.php?id="+id;

    }
          </script>

          <script>
        function ChannelDetails(id){
            location.href = "Cordering.php?id="+id;
    
        }
              </script>


          <script>
        function ContentPage2(id){
            location.href = "youtube-channel.php?token="+token;
        }
              </script>

      <script>
function Selectvideos(token){
  location.href = "movie-details.php?token="+token;
}
    </script>

    <script>
function Selectvideos2(token){
location.href = "movies.php?token="+token;
}
  </script>



  <script>
function Selectbanner(token){
location.href = "movies.php?token="+token;
}
</script>

    <script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="aade7b13-df34-439e-8c2c-0e6fd7cb8c0d";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>

      <script>
      $crisp.push(["set", "user:nickname", ["<?php echo $_SESSION['name']; ?>"]]);
      </script>
      <script src="js/jquery-3.4.1.min.js"></script>
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
      <script src="js/custom.js?v=31"></script>

            <script type="text/javascript" src="js/lazy/jquery.lazy.min.js"></script>

  <script>
function ContentPage(id){
    location.href = "movie-channel.php?id="+id;
}

$(function() {
    $('.lazy').lazy({
        delay: 5000
    });
});

      </script>
      <!-- routes -->
<script src="controllers/routes.js?v41"></script>

<!-- custom -->
<script src="controllers/custom.js?v=55"></script>

<!-- skeleton -->
<script src="controllers/skeletons.js?v=22"></script>

<!-- validators -->
<script src="controllers/validators.js"></script>

<!-- forms -->
<script src="controllers/forms.js?v=69"></script>
   </body>
</html>
