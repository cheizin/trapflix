<?php
session_start();
include("controllers/setup/connect.php");

if(!isset($_SESSION['email']) && empty($_SESSION['email'])) {
   echo 'You must be Logged in to access the videos';

   ?>
   <script>
     window.location.href = "index.php";
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
      <!-- Slider Start -->
      <section id="home" class="iq-main-slider p-0">
         <div id="home-slider" class="slider m-0 p-0">
           <?php
$sql_query1 =  mysqli_query($dbc,"SELECT * FROM videos WHERE banner_vid ='yes' ORDER BY id");

$number = 1;
if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
{?>

  <?php
  $no = 1;
   $sql = mysqli_query($dbc,"SELECT * FROM videos WHERE banner_vid ='yes' ORDER BY id DESC");
   while($row = mysqli_fetch_array($sql)){
   ?>
            <div class="slide slick-bg s-bg-<?php echo $row['banner_css'] ;?>">
               <div class="container-fluid position-relative h-100">
                  <div class="slider-inner h-100">
                     <div class="row align-items-center  h-100">
                        <div class="col-xl-6 col-lg-12 col-md-12">
                           <a href="javascript:void(0);">

                           </a>
                           <h1 class="slider-text big-title title text-uppercase" data-animation-in="fadeInLeft"
                              data-delay-in="0.6"><?php echo $row['title'] ;?></h1>
                           <div class="d-flex align-items-center" data-animation-in="fadeInUp" data-delay-in="1">
                              <span class="badge badge-secondary p-2"></span>
                              <span class="ml-3"></span>
                           </div>
                           <p data-animation-in="fadeInUp" data-delay-in="1.2"><?php echo $row['textname'] ;?>
                           </p>
                           <div class="d-flex align-items-center r-mb-23" data-animation-in="fadeInUp" data-delay-in="1.2">
                             <form method="post" action="movie-details.php">
                               <input type="hidden" name="token" value="<?php echo $row['token']; ?>">

                              <input type="submit" name="submit" class="btn btn-hover" value="Play Now" title="Click Here to Play Video"></h4>

                                             </form>
                           </div>
                        </div>
                     </div>
                     <div class="trailor-video">
                        <a href="video/<?php echo $row['videoname'] ;?>" class="video-open playbtn">
                           <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                              x="0px" y="0px" width="80px" height="80px" viewBox="0 0 213.7 213.7"
                              enable-background="new 0 0 213.7 213.7" xml:space="preserve">
                              <polygon class='triangle' fill="none" stroke-width="7" stroke-linecap="round"
                                 stroke-linejoin="round" stroke-miterlimit="10"
                                 points="73.5,62.5 148.5,105.8 73.5,149.1 " />
                              <circle class='circle' fill="none" stroke-width="7" stroke-linecap="round"
                                 stroke-linejoin="round" stroke-miterlimit="10" cx="106.8" cy="106.8" r="103.3" />
                           </svg>
                           <span class="w-trailor">Watch Trailer</span>
                        </a>
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
         <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44px" height="44px" id="circle"
               fill="none" stroke="currentColor">
               <circle r="20" cy="22" cx="22" id="test"></circle>
            </symbol>
         </svg>
      </section>
      <!-- Slider End -->
      <!-- MainContent -->
      <div class="main-content">

        <?php
$sql_query1 =  mysqli_query($dbc,"SELECT * FROM main_categories ORDER BY id");

$number = 1;
if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
{?>

  <?php
  $no = 1;
   $sql2 = mysqli_query($dbc,"SELECT * FROM main_categories ORDER BY category_name ASC");
   while($row2 = mysqli_fetch_array($sql2)){
   ?>
         <section id="iq-favorites">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-12 overflow-hidden">
                     <div class="iq-main-header d-flex align-items-center justify-content-between">
                        <h4 class="main-title"><?php echo $row2['category_name']; ?></h4>
                        <a href="#" class="text-primary">View all</a>
                     </div>

                     <div class="favorites-contens">
                        <ul class="favorites-slider list-inline  row p-0 mb-0">

                          <?php
                  $sql_query123 =  mysqli_query($dbc,"SELECT * FROM videos WHERE title ='".$row2['category_name']."'");

                  $number = 1;
                  if($total_rows1 = mysqli_num_rows($sql_query123) > 0)
                  {?>

                          <?php
                          $no = 1;
                           $sql3 = mysqli_query($dbc,"SELECT * FROM videos WHERE title ='".$row2['category_name']."'" );
                           while($row3 = mysqli_fetch_array($sql3)){
                           ?>
                           <li class="slide-item">
                             <form method="post" action="movie-details.php">
                               <input type="hidden" name="token" value="<?php echo $row3['token']; ?>">

                              <a href="movie-details.php">
                                 <div class="block-images position-relative">
                                    <div class="img-box">
                                       <img src="images/favorite/<?php echo $row3['thumbnail']; ?>" class="img-fluid" alt="">
                                    </div>
                                    <div class="block-description">
                                       <h6><?php echo $row3['textname']; ?></h6>
                                       <div class="movie-time d-flex align-items-center my-2">
                                          <div class="badge badge-secondary p-1 mr-2"><?php echo $row3['age']; ?></div>
                                          <span class="text-white">2h 30m</span>
                                       </div>
                                       <div class="hover-buttons">
                                          <span class="btn btn-hover">
                                          <i class="fa fa-play mr-1" aria-hidden="true"></i>
                                            <input type="submit" name="submit" class="btn btn-hover" value="Play Now" title="Click Here to Play Video">
                                          </span>
                                       </div>
                                    </div>
                                    <div class="block-social-info">
                                       <ul class="list-inline p-0 m-0 music-play-lists">
                                          <li><span><i class="ri-volume-mute-fill"></i></span></li>
                                          <li><span><i class="ri-heart-fill"></i></span></li>
                                          <li><span><i class="ri-add-line"></i></span></li>
                                       </ul>
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
          else
          {
          ?>
          <br/>
          <div class="alert alert-info">
          <strong><i class="fa fa-info-circle"></i> No Movie in the selected Category</strong>
          </div>

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
?>

         <?php
 }
 else
 {
 ?>
 <br/>
 <div class="alert alert-info">
 <strong><i class="fa fa-info-circle"></i> No Movie Category Exist</strong>
 </div>

 <?php
 }
 ?>

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
      <!-- jQuery, Popper JS -->
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
      <script src="js/custom.js"></script>

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
