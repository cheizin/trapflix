<?php
session_start();
include("controllers/setup/connect.php");

if(!$_SERVER['REQUEST_METHOD'] == "GET")
{
  exit();
}

if(!isset($_GET['id']))
{
  exit("Select id from homepage");
  ?>
<script>
  window.location.href = "home.php";
  </script>

  <?php

}

$selected_channel_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM major_channels WHERE id ='".$_GET['id']."'"));

$selected_channel_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM main_categories WHERE id ='".$selected_channel_id['category_id']."'"));


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
                    <a class="navbar-brand" href="home.php"> <img class="img-fluid logo" src="images/logo.png"
                       alt="streamit" /> </a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                       <div class="menu-main-menu-container">
     
     
                          <ul id="top-menu" class="navbar-nav ml-auto">
                            <li class="menu-item">
                               <a href="home.php">Homepage</a>
                            </li>

                            <li class="menu-item">
                               <a href="orderChannel.php">Back To Channel Ordering</a>
                            </li>
            
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
     
                                <?php
     //check authentication
     if(isset($_SESSION['email']))
     {
     
     $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM users WHERE email ='".$_SESSION['email']."'"));
     //check role
     /*if($_SESSION['access_level'] == "admin")
     {
     ?>
     */
     ?>
     
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
                                            <a href="https://trapflix.com/admin/" class="iq-sub-card setting-dropdown">
                                               <div class="media align-items-center">
                                                  <div class="right-icon">
                                                     <i class="ri-file-user-line text-primary"></i>
                                                  </div>
                                                  <div class="media-body ml-3">
                                                     <h6 class="mb-0 ">Manage Profile</h6>
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
                                            <a href="logout.php" class="iq-sub-card setting-dropdown">
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
     
     $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM users WHERE email ='".$_SESSION['email']."'"));
     //check role
     /*if($_SESSION['access_level'] == "admin")
     {
     ?>
     */
     ?>

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
                                     <?php
           if($_SESSION['access_level'] == 'admin')
           {
            ?>
                                      <a href="https://trapflix.com/admin" class="iq-sub-card setting-dropdown">
                                         <div class="media align-items-center">
                                            <div class="right-icon">
                                               <i class="ri-file-user-line text-primary"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                               <h6 class="mb-0 "> Admin Profile</h6>
                                            </div>
                                         </div>
                                      </a>
     
                                      
                   <?php
                  }
     ?>
                                                       <a href="https://trapflix.com/admin/" class="iq-sub-card setting-dropdown">
                                         <div class="media align-items-center">
                                            <div class="right-icon">
                                               <i class="ri-settings-4-line text-primary"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                               <h6 class="mb-0 ">My Channel</h6>
                                            </div>
                                         </div>
                                      </a>
                                                                </a>
                                                       <a href="https://trapflix.com/admin/" class="iq-sub-card setting-dropdown">
                                         <div class="media align-items-center">
                                            <div class="right-icon">
                                               <i class="ri-settings-4-line text-primary"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                               <h6 class="mb-0 ">Subscriptions</h6>
                                            </div>
                                         </div>
                                      </a>
                                      <a href="https://trapflix.com/admin/" class="iq-sub-card setting-dropdown">
                                         <div class="media align-items-center">
                                            <div class="right-icon">
                                               <i class="ri-settings-4-line text-primary"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                               <h6 class="mb-0 ">Settings</h6>
                                            </div>
                                         </div>
                                      </a>
           
                                      <a href="logout.php" class="iq-sub-card setting-dropdown">
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
   <!-- video details -->


   <div class="main-content movi">
   <section class="movie-detail container-fluid">
   <div class="row">
   <div class="col-lg-12">
      <div class="trending-info g-border">


        <form id="add-job-seeker-form2" class="mt-4" enctype="multipart/form-data">
          <input type="hidden" value="order-channel" name="order-channel">
        
             <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" >
<br/> <br/> 
 Selected Channel to be ordered:-  <strong> <?php echo $selected_channel_name['category_name'] ;?> </strong>
 <br/>
           <div class="form-group">

                  <input type="number" autocomplete="off" class="select2 form-control" name="contact" placeholder="Enter Order Number of <?php echo $selected_channel_name['category_name'] ;?>"  >
           </div>
  

           <button type="submit" class="btn btn-hover">Update Order Number</button>

        </form>
        

      </div>
   </div>

   </div>
   </section>

   </div>


   <!-- list of channels and their respective order -->
   <div class="main-content movi">
      <section class="movie-detail container-fluid">
         <div class="row">
           <div class="col-lg-12">


         <table id="example" class="table table-striped table-bordered" style="width:100%">

         <thead>
         <tr>
                <td>No</td>
           <td>Order Number</td>
            <td>Channel Name</td>
          
           <td>Owner</td>
         </tr>
         </thead>
         <?php
         $no = 1;
          $sql2 = mysqli_query($dbc,"SELECT * FROM major_channels ORDER BY order_id ASC");
          while($row2 = mysqli_fetch_array($sql2)){

            $channel_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM main_categories WHERE id ='".$row2['category_id']."'   && approved ='yes'"));
            $sql_query1234 =  mysqli_query($dbc,"SELECT * FROM videos WHERE title ='".$channel_name['category_name']."' ORDER BY id DESC limit 12 ");

                $count_videos = mysqli_fetch_array(mysqli_query($dbc,"SELECT email, count(*) FROM videos WHERE title ='".$channel_name['category_name']."'   && approved ='yes'"));

            $number = 1;


          ?>

              <tr style="cursor: pointer;">

                <td width="50px"> <?php echo $no++;?>.

                </td>
         <td>
         <?php echo $row2['order_id']; ?>
         </td>

         <td>
         <?php echo $channel_name['category_name']; ?>
         </td>


         <td>
         <?php echo $channel_name['recorded_by']; ?>
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
     $(document).on("submit", "#add-job-seeker-form2", function(e){
         e.preventDefault();

         var process_url = 'controllers/trapuser/registercontroller.php';
         var form_data = $('#add-job-seeker-form2').serializeArray();
         var form_method = 'POST';
         //  window.location.href = "login.php";

         $.ajax({
             url:process_url,
             data:form_data,
             method:form_method,

             success: function(data){

                 if(data == "success")
                 {
       
           window.location.href = "orderChannel.php";
           
//  alert("Ordered Successfully");
                 }
                 else if(data == "invalid")
                 {
                 console.log(data);

                  alert("System Error");

                 }

                 else if(data == 'duplicate')
       {

         console.log(data);

          alert("The user Is already Registered");
       }

       else if(data == 'changePass')
     {

     console.log(data);

      window.location.href = "changePassword.php";

     alert("For Security Reasons you need to update your Password");
     }

                 else
                 {
                     console.log(data);
                     /*Toast.fire({
                         icon: 'error',
                         title: 'An error occured. Please try again'
                       });
                       */
                 }

             },
             error: function(xhr)
             {

             }

         });
     });

     $(document).on("submit", "#channelOrderForm", function(e){
       e.preventDefault();

       var process_url = 'controllers/channelManagement/orderChannel.php';

       var form_data = $('#channelOrderForm').serializeArray();
       var form_method = 'POST';


       $.ajax({
           url:process_url,
           data:form_data,
           method:form_method,

           success: function(data){

               if(data == "success")
               {
        //alert("Video Comment Saved Successfully");
                 window.location.href = "channelOrder.php";


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


               }

           },
           error: function(xhr)
           {
          alert("Netowrk issue");
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
   <script src="js/custom.js"></script>
</body>

</html>
