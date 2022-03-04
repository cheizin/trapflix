<?php
session_start();
include("controllers/setup/connect.php");


?>

<!doctype html>
<html lang="en-US">
   <head>
     <!-- Required meta tags -->

     <title>Trapflix - Watch TV Shows Online, Watch Movies Online</title>

       <link rel="icon" href="assets/images/house.jpg" type="image/x-icon" />
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
         crossorigin="anonymous">
     <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
     <link rel="stylesheet" href="../assets2/css/style.css">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="css/bootstrap.min.css" />
     <!-- Typography CSS -->
     <link rel="stylesheet" href="css/typography.css">
     <!-- Style -->
     <link rel="stylesheet" href="css/style.css" />
     <!-- Responsive -->
     <link rel="stylesheet" href="css/responsive.css" />
     <!-- sweetalert -->


     <style>
 h1 {text-align: center;}
 p {text-align: center;}
 button {text-align: center;}

 </style>

 <!-- Required meta tags -->



   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
      <!-- Header -->
      <header style="background:url('images/login2.jpg') ;  background-repeat: no-repeat;
  background-size: 100% 100%;">

          <!-- Image and text -->
          <div class="container">
              <div class="row">
                  <div class="col-md-12 mt-5">
                      <nav class="navbar navbar-light">

                                    <div class="col text-center">
                          <a class="navbar-brand" href="#">
                              <img class="brand" src="assets/images/logo.png" class="d-inline-block align-top" alt="">
                          </a>
                        </div>

                      <div class="col text-center">
<br/>
                                                                                <button class="btn btn-danger sing-up" onclick="location.href='https://trapflix.risksys.co.ke/login.php';">
                                                                                    <a class="signin text-white btn-danger" href="https://trapflix.risksys.co.ke/login.php">Sign In  </a>
                                                                                  </button>

                        </div>



                      </nav>
  <br/> <br/> <br/> <br/>


                      <section class="text-white">
                          <h1 class="header__title">Unlimited movies and TV shows</h1>
                          <p class="header__subtitle">WATCH ANYWHERE. CANCEL ANYTIME.</p>


                      </section>


                </div>

                <!-- start of courousel   -->


                <!-- end of courousel --->


              </div>

          </div>

      <!-- Slider End -->
      <!-- MainContent -->
      <div class="main-content">



      </div>
      </br> </br>  </br> </br>  </br> </br>
 </header>
      <section id="tabs">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-md-12 ">



                              <div class="container">
                                  <div class="row features">
                                      <div class="col-md-12">
                                        <section class="text-white">
                                            <h1 class="header__title">Enjoy on your Devices</h1>
                                            <p class="header__subtitle">Watch on Smart TVs, Playstation, Xbox, Chromecast, Apple TV, Blu-ray players, and more..</p>


                                        </section>
                                      </div>


                                  </div>
                                  <div class="row gx-5 align-items-center">
                                        <div class="col-lg-4">
                                          <img src="assets/images/asset_TV_UI.png" loading="lazy" width="350PX" height="300PX" alt="">
                                          <h3>Watch on your TV</h3>
                                          <p>Smart TVs, PlayStation, Xbox, Chromecast, Apple TV, Blu-ray players and more.</p>
                                      </div>

                                        <div class="col-lg-4">
                                          <img src="assets/images/asset_mobile_tablet_UI_2.png" width="350PX" height="300PX" alt="">
                                          <h3>Watch instantly</h3>
                                          <p>Available on phone and tablet</p>
                                      </div>

                                            <div class="col-lg-4">
                                          <img src="assets/images/asset_website_UI.png"  width="350PX" height="300PX" alt="">
                                          <h3>Use any computer</h3>
                                          <p>Watch right on trapflix.com.</p>
                                      </div>
                                  </div>
                              </div>

                                  <hr style="solid"/>
   <br/>  <br/>
                              <div class="container">

                                <div class="row gx-5 align-items-center">
                                    <div class="col-lg-6">
                                        <section class="text-white">
                                            <h1 class="header__title">Create Video Channel</h1>
                                            <p class="header__subtitle">

                                              <ul>
    <li>Create Channel</li>
    <li>Upload Unlimited Videos </li>
    <li>Track video views </li>
    <li>Track Channel Subscription</li>
  </ul></p>


                                        </section>
                                      </div>



                                        <div class="col-lg-6">
                                          <video muted="muted" autoplay="true" loop="true" style="max-width: 100%; height: 100%">
                                            <source src="assets/images/addchannel.mp4" type="video/mp4" /></video>

                                      </div>

                                  </div>
                              </div>



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

                        <div class="row">
                  <div class="col-lg-3 col-md-4">

                      </div>

                           <div class="col-lg-3 col-md-4">
    <strong> <a href="#"  data-toggle="modal" data-target="#rating-user-modal" style="color:white;"> Copyright &copy; <?php echo date("Y");?>TRAPFLIX    All rights reserved.</a></strong>

          </div>
           <div class="col-lg-3 col-md-4">
           <b>Version</b> 1.0
            </div>
          </div>

    <div class="modal fade" id="rating-user-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-light">


            <h5 style="color:black;">TRAPFLIX Credits</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="video-type-details-modal-body" style="color:black;">
          Credit to Trapflix developers:-

          <ul>
  <li>prchege@gmail.com,</li>
  <li>marojillo@gmail.com</li>
  <li> JT The bigaa figga: filmoeafrica@gmail.com</li>
</ul>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="float-right d-none d-sm-inline-block">
      <button type="button" class="btn btn-link" data-toggle="modal" data-target="#submit-feedback-modal">
        <i class="fas fa-comment-alt-edit"></i>
      </button>

      <!-- User Feeback Modal -->
    <div class="modal fade" id="submit-feedback-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Please Let us know how you feel about the system</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <small class="text-muted mb-3">Your feedback will help us improve the system and serve you better</small><br/>
            <small class="text-muted mb-4"><i class="fad fa-user-secret"></i> (Your feedback will be anonymous)</small>
            <form id="user-feedback-form" class="mt-4">
              <div class="row">
                <div class="col-sm-12">
                    <textarea maxlength="1000" required class="form-control" name="user_feedback_message" placeholder="enter your feedback here"></textarea>
                </div>
              </div>
              <br/><br/>
              <div class="row">
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block" id="user-feedback-button">SUBMIT</button>
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
  location.href = "feedsDetails.php?token="+token;
}
    </script>




  <script>
function Selectbanner(token){
location.href = "movies.php?token="+token;
}
</script>

<script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="d391fa9b-9983-4dfc-a43c-fbdca0e5ed6b";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>

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
      <script src="js/custom.js?v=38"></script>

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
<script src="controllers/routes.js?v42"></script>

<!-- custom -->
<script src="controllers/custom.js?v=56"></script>

<!-- skeleton -->
<script src="controllers/skeletons.js?v=22"></script>

<!-- validators -->
<script src="controllers/validators.js"></script>

<!-- forms -->
<script src="controllers/forms.js?v=70"></script>
   </body>
</html>
