
<?php
session_start();
include("controllers/setup/connect.php");

if(!$_SERVER['REQUEST_METHOD'] == "GET")
{
  exit();
}

if(!isset($_GET['id']))
{
  exit("Select Video from the Home Page");
  ?>
<script>
  window.location.href = "home.php";
  </script>

  <?php

}

$video_post = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos WHERE channel ='".$_GET['id']."'"));

$channel_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM categories WHERE id ='".$_GET['id']."'"));
?>

<!doctype html>
<html lang="en-US">

<head>
   <!-- Required meta tags -->
   <?php
   include("views/header.php");
   ?>

</head>

<body>
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- Header -->
   <header id="main-header">
     <?php
     include("views/navigation.php");
     ?>

   </header>
   <!-- Header End -->

   <!-- MainContent -->
   <section id="music-part">
      <div class="container py-5">
         <div class="row">
            <div class="col-10">

               <div class="main-content">
                  <section id="iq-favorites">
                     <div class="container">
                        <div class="row">
                           <div class="col-sm-12 overflow-hidden">
                              <div class="iq-main-header d-flex align-items-center justify-content-between">
                                 <h4 class="main-title"><?php echo $channel_name['name']; ?> Channel </h4>
                              </div>
                              <div class="favorites-contens">

                                <div class="favorites-contens">
                           <!--   <ul class="favorites-slider2 list-inline  row p-0 mb-0"> -->
                                  <ul class="favorites-slider2 list-inline  row p-0 mb-0">
                                   <?php
                        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM videos WHERE channel ='".$_GET['id']."' ORDER BY id");

                        $number = 1;
                        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
                        {?>

                          <?php
                          $no = 1;
                           $sql = mysqli_query($dbc,"SELECT * FROM videos WHERE channel ='".$_GET['id']."' ORDER BY id");
                           while($row = mysqli_fetch_array($sql)){
                           ?>

                                    <li class="slide-item">

                                       <a onclick="Selectvideos2('<?php echo $row['token'];?>');">
                                          <div class="block-images position-relative">
                                             <div class="img-box">
                                                <img src="images/favorite/<?php echo $row['thumbnail']; ?>" class="img-fluid" alt="">
                                             </div>
                                             <div class="block-description">
                                               <h4><?php echo $row['textname']; ?></h4>

                                                <div class="hover-buttons">
                                                   <span class="btn btn-hover"><i class="fa fa-play mr-1" aria-hidden="true"></i>
                                                      <input type="submit" name="submit" class="btn btn-hover" value="Play Now" title="Click Here to Play Video">
                                                   </span>
                                                </div>
                                             </div>

                                          </div>
                                       </a>

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
                                        <strong><i class="fa fa-info-circle"></i> No Movie Exist in the <?php echo $channel_name['name']; ?> Channel</strong>
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

               </div>
               <!-- End Music Carousel -->
            </div>


         </div>
      </div>
   </section>

   <footer class="mb-0">
      <div class="container-fluid">
         <div class="block-space">
            <div class="row">
               <div class="col-lg-3 col-md-4">
                  <ul class="f-link list-unstyled mb-0">
                     <li><a href="#">About Us</a></li>
                     <li><a href="movie-category.html">Movies</a></li>
                     <li><a href="show-category.html">Tv Shows</a></li>
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
 function Selectvideos(token){
   location.href = "movie-details.php?token="+token;
 }
     </script>

     <script>
 function Selectvideos2(token){
 location.href = "Youtube.php?token="+token;
 }
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
   <script src="js/custom.js"></script>
   <!-- index js -->
   <script src="js/index.js"></script>
</body>

</html>
