
<?php
session_set_cookie_params(0);
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
   <link rel="shortcut icon" href="images/favicon.ico"/>
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css"/>
   <!-- Typography CSS -->
   <link rel="stylesheet" href="css/typography.css">
   <!-- Style -->
   <link rel="stylesheet" href="css/style.css"/>
   <!-- Responsive -->
   <link rel="stylesheet" href="css/responsive.css"/>


</head>
<body>
<!-- loader Start -->
<!-- <div id="loading">
   <div id="loading-center">
   </div>
</div> -->
<!-- loader END -->
<!-- MainContent -->
<section class="sign-in-page">
   <div class="container">
      <div class="row justify-content-center align-items-center height-self-center">
         <div class="col-lg-5 col-md-12 align-self-center">
            <div class="sign-user_card ">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Password Change</h3>
         <form id="potential-change-password-form">
                    <div class="form-group">
                       <input type="email" class="form-control mb-0" autocomplete="on" id="email" name="email" placeholder="Enter email"  required>
                    </div>
                    <div class="form-group">
                       <input type="password" class="form-control mb-0" name="password1" id="password" autocomplete="off" placeholder="New Password" required>
                    </div>
                        <div class="form-group">
                           <input type="password" class="form-control mb-0" name="password" id="password2" autocomplete="off" placeholder="Confirm Password" required>
                        </div>

                           <div class="sign-info">
                              <button type="submit" class="btn btn-hover">Sign in</button>
                              <div class="custom-control custom-checkbox d-inline-block">
                                 <input type="checkbox" class="custom-control-input" id="customCheck">
                                 <label class="custom-control-label" for="customCheck">Remember Me</label>
                              </div>
                           </div>
                     </form>
                  </div>
               </div>
               <div class="mt-3">
                  <div class="d-flex justify-content-center links">
                     Don't have an account? <a href="sign-up.html" class="text-primary ml-2">Sign Up</a>
                  </div>
                  <div class="d-flex justify-content-center links">
                     <a href="reset-password.html" class="f-link">Forgot your password?</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- MainContent End-->

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

<!--<script src="controllers/custom.js?v=55"></script> -->

<!-- skeleton -->
<script src="controllers/skeletons.js?v=22"></script>
<script src="http://code.jquery.com/jquery.js"></script>
   <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- validators -->
<script src="controllers/validators.js"></script>

<!-- forms -->
<script src="controllers/forms.js?v=69"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).on("submit", "#potential-change-password-form", function(e){
    e.preventDefault();

    var process_url = 'controllers/auth/changePasscontroller.php';
    var form_data = $('#test-login-form').serializeArray();
    var form_method = 'POST';


    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,

        success: function(data){

            if(data == "success")
            {

              window.location.href = "login.php";
                 alert("Password changed Successfully");
                //fetch dynamic navbar
                $.ajax({
                  url: dynamic_navbar_url,
                  method:"POST",
                  success: function(returned_navbar_data){
                    $('#dynamic-navbar').html(returned_navbar_data);
                    console.log(returned_navbar_data);
                  },
                  error: function(){
                    $('#dynamic-navbar').html('please reload page');
                  }
                });
            }
            else if(data == "invalid")
            {
            console.log(data);

   alert("Invalid Login Credentials");
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
</body>
</html>
