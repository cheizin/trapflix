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
                          <a href="home.php">Home</a>
                       </li>


                                                                                            <li class="menu-item">
                          <a href="https://trapflix.risksys.co.ke/">Social Trapflix
                          <img src="images/new.jpg" loading="lazy" data-toggle="tooltip"  title="This is a premium verified Channel account" width="25" height="25" alt=""></a>
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
<?php echo $_SESSION['name'];?>
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
            <a href="https://trapflix.risksys.co.ke/admin" class="iq-sub-card setting-dropdown">
               <div class="media align-items-center">
                  <div class="right-icon">
                     <i class="ri-file-user-line text-primary"></i>
                  </div>
                  <div class="media-body ml-3">
                     <h6 class="mb-0 "> Admin Profile</h6>
                  </div>
               </div>
            </a>

            <a href="https://trapflix.risksys.co.ke/orderChannel.php" class="iq-sub-card setting-dropdown">
               <div class="media align-items-center">
                  <div class="right-icon">
                     <i class="ri-file-user-line text-primary"></i>
                  </div>
                  <div class="media-body ml-3">
                     <h6 class="mb-0 "> Channel/Video Order</h6>
                  </div>
               </div>
            </a>



<?php
}
?>
                             <a href="https://trapflix.risksys.co.ke/admin/" class="iq-sub-card setting-dropdown">
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
                             <a href="https://trapflix.risksys.co.ke/admin/" class="iq-sub-card setting-dropdown">
               <div class="media align-items-center">
                  <div class="right-icon">
                     <i class="ri-settings-4-line text-primary"></i>
                  </div>
                  <div class="media-body ml-3">
                     <h6 class="mb-0 ">Subscriptions</h6>
                  </div>
               </div>
            </a>
            <a href="https://trapflix.risksys.co.ke/admin/" class="iq-sub-card setting-dropdown">
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
                                 <a href="https://trapflix.risksys.co.ke/admin" class="iq-sub-card setting-dropdown">
                                    <div class="media align-items-center">
                                       <div class="right-icon">
                                          <i class="ri-file-user-line text-primary"></i>
                                       </div>
                                       <div class="media-body ml-3">
                                          <h6 class="mb-0 "> Admin Profile</h6>
                                       </div>
                                    </div>
                                 </a>

                                 <a href="https://trapflix.risksys.co.ke/orderChannel.php" class="iq-sub-card setting-dropdown">
                                    <div class="media align-items-center">
                                       <div class="right-icon">
                                          <i class="ri-file-user-line text-primary"></i>
                                       </div>
                                       <div class="media-body ml-3">
                                          <h6 class="mb-0 "> Channel/Video Order</h6>
                                       </div>
                                    </div>
                                 </a>



              <?php
             }
?>
                                                  <a href="https://trapflix.risksys.co.ke/admin/" class="iq-sub-card setting-dropdown">
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
                                                  <a href="https://trapflix.risksys.co.ke/admin/" class="iq-sub-card setting-dropdown">
                                    <div class="media align-items-center">
                                       <div class="right-icon">
                                          <i class="ri-settings-4-line text-primary"></i>
                                       </div>
                                       <div class="media-body ml-3">
                                          <h6 class="mb-0 ">Subscriptions</h6>
                                       </div>
                                    </div>
                                 </a>
                                 <a href="https://trapflix.risksys.co.ke/admin/" class="iq-sub-card setting-dropdown">
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
