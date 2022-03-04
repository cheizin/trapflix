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
  
      <!-- Slider End -->
      <!-- MainContent -->
      <div class="main-content">

        <!-- popular videos list -->

                <?php
        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM feeds_categories");

        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

          <?php
          $no = 1;
           $sql2 = mysqli_query($dbc,"SELECT * FROM feeds_categories ORDER BY category_name ASC");
           while($row2 = mysqli_fetch_array($sql2)){

             $number = 1;

           ?>
                 <section id="iq-favorites"  class="iq-main-slider p-0">
                    <div class="container-fluid slide">
                       <div class="row">
                          <div class="col-sm-12 overflow-hidden"><br/> <br/>
                             <div class="iq-main-header d-flex align-items-center justify-content-between">
                                <h4 class="main-title"><?php echo $row2['category_name']; ?></h4>

                             </div>

                             <div class="favorites-contens">
                        <ul class="favorites-slider2 list-inline  row p-0 mb-0"> 
                              <!--    <ul class="favorites-slider list-inline  row p-0 mb-0"> -->

                                  <?php
                          $sql_query123 =  mysqli_query($dbc,"SELECT * FROM feeds WHERE category ='".$row2['category_name']."' ORDER BY id DESC");

                          $number = 1;
                          if($total_rows1 = mysqli_num_rows($sql_query123) > 0)
                          {?>
                                  <?php
                                  $no = 1;
                                   $sql3 = mysqli_query($dbc,"SELECT * FROM feeds WHERE category ='".$row2['category_name']."' ORDER BY id DESC" );
                                   while($row3 = mysqli_fetch_array($sql3)){
                                    //  $video_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row3['popular_video_id']."' ORDER BY id DESC"));
                                   ?>
                                   <li class="slide-item">

                                     <form method="post" action="movie-details.php">
                                       <input type="hidden" name="token" value="<?php echo $row3['token']; ?>">

                                              <a onclick="Selectvideos2('<?php echo $row3['token'];?>');">
                

                                         <div class="">
                                            <div class="img-box">
                                               <img src="images/favoriteCompressed/<?php echo $row3['thumbnail']; ?>" loading="lazy" width="100%" height="100%" alt="">
                                            </div>
                                            <div class="">
                                               <h4><?php echo $row3['feeds_header']; ?></h4>
                                            
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
