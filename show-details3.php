
<?php
session_start();
include("controllers/setup/connect.php");
?>
<!doctype html>
<html lang="en-US">

<head>
   <!-- Required meta tags -->
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Trapflix - African video streaming platform</title>
   <!-- Favicon -->
   <link rel="shortcut icon" href="images/favicon.ico" />
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css" />
   <!-- Typography CSS -->
   <link rel="stylesheet" href="css/typography.css">
   <!-- Style -->
   <link rel="stylesheet" href="css/style.css" />
   <!-- Responsive -->
   <link rel="stylesheet" href="css/responsive.css" />
</head>

<body>
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- Header -->
   <!-- Header -->
   <header id="main-header">
      <div class="main-header">
         <div class="container-fluid">
            <div class="row">
               <div class="col-sm-12">
                  <nav class="navbar navbar-expand-lg navbar-light p-0">
                     <a href="#" class="navbar-toggler c-toggler" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <div class="navbar-toggler-icon" data-toggle="collapse">
                           <span class="navbar-menu-icon navbar-menu-icon--top"></span>
                           <span class="navbar-menu-icon navbar-menu-icon--middle"></span>
                           <span class="navbar-menu-icon navbar-menu-icon--bottom"></span>
                        </div>
                     </a>
                     <a class="navbar-brand" href="index.php"> <img class="img-fluid logo" src="images/logo.png"
                        alt="streamit" /> </a>
                     <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="menu-main-menu-container">


                           <ul id="top-menu" class="navbar-nav ml-auto">
                             <li class="menu-item">
                                <a href="home.php">Home</a>
                             </li>
                             <?php
$no = 1;
$sql= mysqli_query($dbc,"SELECT * FROM categories");
while($row = mysqli_fetch_array($sql))
{


?>
<li class="menu-item">
<a href="#"><?php echo $row['name']; ?></a>
</li>
<?php
               }
               ?>

                           </ul>
                        </div>
                     </div>
                     <div class="mobile-more-menu">
                        <a href="javascript:void(0);" class="more-toggle" id="dropdownMenuButton"
                           data-toggle="more-toggle" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-more-line"></i>
                        </a>
                        <div class="more-menu" aria-labelledby="dropdownMenuButton">
                           <div class="navbar-right position-relative">
                              <ul class="d-flex align-items-center justify-content-end list-inline m-0">
                                 <li>
                                    <a href="#" class="search-toggle">
                                    <i class="ri-search-line"></i>
                                    </a>
                                    <div class="search-box iq-search-bar">
                                       <form action="#" class="searchbox">
                                          <div class="form-group position-relative">
                                             <input type="text" class="text search-input font-size-12"
                                                placeholder="type here to search...">
                                             <i class="search-link ri-search-line"></i>
                                          </div>
                                       </form>
                                    </div>
                                 </li>



                                 <li class="nav-item nav-icon">
                                    <a href="#" class="search-toggle position-relative">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22"
                                          height="22" class="noti-svg">
                                          <path fill="none" d="M0 0h24v24H0z" />
                                          <path
                                             d="M18 10a6 6 0 1 0-12 0v8h12v-8zm2 8.667l.4.533a.5.5 0 0 1-.4.8H4a.5.5 0 0 1-.4-.8l.4-.533V10a8 8 0 1 1 16 0v8.667zM9.5 21h5a2.5 2.5 0 1 1-5 0z" />
                                       </svg>
                                       <span class="bg-danger dots"></span>
                                    </a>
                                    <div class="iq-sub-dropdown">
                                       <div class="iq-card shadow-none m-0">
                                          <div class="iq-card-body">
                                             <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center">
                                                   <img src="images/notify/thumb-1.jpg" class="img-fluid mr-3"
                                                      alt="streamit" />
                                                   <div class="media-body">
                                                      <h6 class="mb-0 ">Boop Bitty</h6>
                                                      <small class="font-size-12"> just now</small>
                                                   </div>
                                                </div>
                                             </a>
                                             <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center">
                                                   <img src="images/notify/thumb-2.jpg" class="img-fluid mr-3"
                                                      alt="streamit" />
                                                   <div class="media-body">
                                                      <h6 class="mb-0 ">The Last Breath</h6>
                                                      <small class="font-size-12">15 minutes ago</small>
                                                   </div>
                                                </div>
                                             </a>
                                             <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center">
                                                   <img src="images/notify/thumb-3.jpg" class="img-fluid mr-3"
                                                      alt="streamit" />
                                                   <div class="media-body">
                                                      <h6 class="mb-0 ">The Hero Camp</h6>
                                                      <small class="font-size-12">1 hour ago</small>
                                                   </div>
                                                </div>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <a href="#" class="iq-user-dropdown search-toggle d-flex align-items-center">
                                    <img src="images/user/user.jpg" class="img-fluid avatar-40 rounded-circle"
                                       alt="user">
                                    </a>
                                    <div class="iq-sub-dropdown iq-user-dropdown">
                                       <div class="iq-card shadow-none m-0">
                                          <div class="iq-card-body p-0 pl-3 pr-3">
                                             <a href="#" class="iq-sub-card setting-dropdown">
                                                <div class="media align-items-center">
                                                   <div class="right-icon">
                                                      <i class="ri-file-user-line text-primary"></i>
                                                   </div>
                                                   <div class="media-body ml-3">
                                                      <h6 class="mb-0 ">Manage Profile</h6>
                                                   </div>
                                                </div>
                                             </a>
                                             <a href="#" class="iq-sub-card setting-dropdown">
                                                <div class="media align-items-center">
                                                   <div class="right-icon">
                                                      <i class="ri-settings-4-line text-primary"></i>
                                                   </div>
                                                   <div class="media-body ml-3">
                                                      <h6 class="mb-0 ">Settings</h6>
                                                   </div>
                                                </div>
                                             </a>
                                             <a href="#" class="iq-sub-card setting-dropdown">
                                                <div class="media align-items-center">
                                                   <div class="right-icon">
                                                      <i class="ri-settings-4-line text-primary"></i>
                                                   </div>
                                                   <div class="media-body ml-3">
                                                      <h6 class="mb-0 ">Pricing Plan</h6>
                                                   </div>
                                                </div>
                                             </a>
                                             <a href="login.php" class="iq-sub-card setting-dropdown">
                                                <div class="media align-items-center">
                                                   <div class="right-icon">
                                                      <i class="ri-logout-circle-line text-primary"></i>
                                                   </div>
                                                   <div class="media-body ml-3">
                                                      <h6 class="mb-0">Logout</h6>
                                                   </div>
                                                </div>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </li>

                            </ul>
                           </div>
                        </div>
                     </div>
                     <div class="navbar-right menu-right">
                        <ul class="d-flex align-items-center list-inline m-0">
                           <li class="nav-item nav-icon">
                              <a href="#" class="search-toggle device-search">
                              <i class="ri-search-line"></i>
                              </a>
                              <div class="search-box iq-search-bar d-search">
                                 <form action="#" class="searchbox">
                                    <div class="form-group position-relative">
                                       <input type="text" class="text search-input font-size-12"
                                          placeholder="type here to search...">
                                       <i class="search-link ri-search-line"></i>
                                    </div>
                                 </form>
                              </div>
                           </li>

                           <?php
//check authentication
if(isset($_SESSION['email']))
{

$profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE Email ='".$_SESSION['email']."'"));
//check role
/*if($_SESSION['access_level'] == "admin")
{
?>
*/
?>

                           <li class="nav-item nav-icon">
                              <a href="#" class="search-toggle" data-toggle="search-toggle">
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22"
                                    class="noti-svg">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path
                                       d="M18 10a6 6 0 1 0-12 0v8h12v-8zm2 8.667l.4.533a.5.5 0 0 1-.4.8H4a.5.5 0 0 1-.4-.8l.4-.533V10a8 8 0 1 1 16 0v8.667zM9.5 21h5a2.5 2.5 0 1 1-5 0z" />
                                 </svg>
                                 <span class="bg-danger dots"></span>
                              </a>
                              <div class="iq-sub-dropdown">
                                 <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body">
                                       <a href="#" class="iq-sub-card">
                                          <div class="media align-items-center">
                                             <img src="images/notify/thumb-1.jpg" class="img-fluid mr-3"
                                                alt="streamit" />
                                             <div class="media-body">
                                                <h6 class="mb-0 ">Boot Bitty</h6>
                                                <small class="font-size-12"> just now</small>
                                             </div>
                                          </div>
                                       </a>
                                       <a href="#" class="iq-sub-card">
                                          <div class="media align-items-center">
                                             <img src="images/notify/thumb-2.jpg" class="img-fluid mr-3"
                                                alt="streamit" />
                                             <div class="media-body">
                                                <h6 class="mb-0 ">The Last Breath</h6>
                                                <small class="font-size-12">15 minutes ago</small>
                                             </div>
                                          </div>
                                       </a>
                                       <a href="#" class="iq-sub-card">
                                          <div class="media align-items-center">
                                             <img src="images/notify/thumb-3.jpg" class="img-fluid mr-3"
                                                alt="streamit" />
                                             <div class="media-body">
                                                <h6 class="mb-0 ">The Hero Camp</h6>
                                                <small class="font-size-12">1 hour ago</small>
                                             </div>
                                          </div>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </li>
                               <li class="nav-item nav-icon">
<?php echo $_SESSION['name'];?>
                               </l1>
                           <li class="nav-item nav-icon">

                              <a href="#" class="iq-user-dropdown search-toggle p-0 d-flex align-items-center"
                                 data-toggle="search-toggle">
                              <img src="images/user/user.jpg" class="img-fluid avatar-40 rounded-circle" alt="user">
                              </a>
                              <div class="iq-sub-dropdown iq-user-dropdown">
                                 <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 pl-3 pr-3">
                                       <a href="manage-profile.php" class="iq-sub-card setting-dropdown">
                                          <div class="media align-items-center">
                                             <div class="right-icon">
                                                <i class="ri-file-user-line text-primary"></i>
                                             </div>
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 "> Manage Profilee</h6>
                                             </div>
                                          </div>
                                       </a>
                                       <a href="setting.php" class="iq-sub-card setting-dropdown">
                                          <div class="media align-items-center">
                                             <div class="right-icon">
                                                <i class="ri-settings-4-line text-primary"></i>
                                             </div>
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 ">Settings</h6>
                                             </div>
                                          </div>
                                       </a>
                                       <a href="pricing-plan.php" class="iq-sub-card setting-dropdown">
                                          <div class="media align-items-center">
                                             <div class="right-icon">
                                                <i class="ri-settings-4-line text-primary"></i>
                                             </div>
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 ">Pricing Plan</h6>
                                             </div>
                                          </div>
                                       </a>
                                       <a href="login.php" class="iq-sub-card setting-dropdown">
                                          <div class="media align-items-center">
                                             <div class="right-icon">
                                                <i class="ri-logout-circle-line text-primary"></i>
                                             </div>
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 ">Logout</h6>
                                             </div>
                                          </div>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </li>



                             <?php
                             }
                             else
                             {
                             ?>
                                 <ul class="nav navbar-nav navbar-right">
                                   <li class="br-right"><a class="btn-signup red-btn" href="login.php"><i class="login-icon ti-user"></i>Login</a></li>

                                   <li class="sign-up user-selection-link"><a class="btn-signup red-btn" href="signup.php"><span class="ti-briefcase"></span>Register</a></li>
                                 </ul>

                                 <?php
                             }


                             ?>
                        </ul>
                     </div>
                  </nav>
                  <div class="nav-overlay"></div>
               </div>
            </div>
         </div>
      </div>
   </header>
   <!-- Header End -->
   <!-- Banner Start -->
   <div class="video-container iq-main-slider">
      <video class="video d-block" controls autoplay>
         <source src="video/Shakira - Chantaje (Official Video) ft. Maluma.mp4" type="video/mp4">
      </video>
   </div>
   <!-- Banner End -->

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
</body>

</html>
