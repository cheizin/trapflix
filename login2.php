
<?php
session_set_cookie_params(0);
session_start();
include("controllers/setup/connect.php");

if(isset($_SESSION['email'])) {

   ?>
   <script>
     window.location.href = "home.php";
     </script>

     <?php
}
 ?>
<!doctype html>
<html lang="en-US">
<head>
   <!-- Required meta tags -->
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Trapflix</title>
   <!-- Favicon -->
  <link rel="icon" href="../assets/img/house.jpg" type="image/x-icon" />
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="../css/bootstrap.min.css"/>
   <!-- Typography CSS -->
   <link rel="stylesheet" href="../css/typography.css">
   <!-- Style -->
   <link rel="stylesheet" href="../css/style.css"/>
   <!-- Responsive -->
   <link rel="stylesheet" href="../css/responsive.css"/>


</head>
<body style="background:url('../images/login/login2.jpg') ;  background-repeat: no-repeat;
  background-size: 100% 100%;">
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
                     <h3 class="mb-3 text-center"><img class="img-fluid logo" src="images/logo.png"
                  alt="streamit" />Sign in</h3>
                  <form id="test-login-form" class="mt-4" enctype="multipart/form-data">
                        <div class="form-group">
                           <input type="email" class="form-control mb-0" autocomplete="on" id="email" name="email" placeholder="Enter email"  required>
                        </div>
                        <div class="form-group">
                           <input type="password" class="form-control mb-0" name="password" id="password" autocomplete="off" placeholder="Password" required>
                        </div>

                           <div class="sign-info">
                              <button type="submit" class="btn btn-hover">Sign in</button>
                              <div class="custom-control custom-checkbox d-inline-block">
					  									<input class="form-check-input" type="checkbox" onclick="ShowPassword()" id="show-password"/>
																  <label class="form-check-label" for="show-password">
																    Show Password
																  </label>
                              </div>
                           </div>
                           
                           
                           
                     </form>
                  </div>
               </div>
               <div class="mt-3">
                  <div class="d-flex justify-content-center links">
                     Don't have an account? <a href="../signup.php" class="text-primary ml-2">Sign Up</a>
                  </div>
                  <div class="d-flex justify-content-center links">
                     <a href="../reset-password.php" class="f-link">Forgot your password?</a>
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
<script src="../js/jquery-3.4.1.min.js"></script>
<script src="../js/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="../js/bootstrap.min.js"></script>
<!-- Slick JS -->
<script src="../js/slick.min.js"></script>
<!-- owl carousel Js -->
<script src="../js/owl.carousel.min.js"></script>
<!-- select2 Js -->
<script src="../js/select2.min.js"></script>
<!-- Magnific Popup-->
<script src="../js/jquery.magnific-popup.min.js"></script>
<!-- Slick Animation-->
<script src="../js/slick-animation.min.js"></script>
<!-- Custom JS-->
<script src="../js/custom.js"></script>

<!-- routes -->
<script src="../controllers/routes.js?v41"></script>

<!--<script src="../controllers/custom.js?v=55"></script> -->

<!-- skeleton -->
<script src="../controllers/skeletons.js?v=22"></script>
<script src="http://code.jquery.com/jquery.js"></script>
   <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- validators -->
<script src="../controllers/validators.js"></script>

<!-- forms -->
<script src="../controllers/forms.js?v=69"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function ShowPassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
<script>
$(document).on("submit", "#test-login-form", function(e){
    e.preventDefault();

    var process_url = '../controllers/auth/TestLoginController.php';
    var form_data = $('#test-login-form').serializeArray();
    var form_method = 'POST';


    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,

        success: function(data){

            if(data == "success")
            {

              window.location.href = "https://trapflix.com/socialnetwork/";
              //   alert("Login Successfully");
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
            
                                    else if(data == "deactivated")
            {
            console.log(data);
            
               alert("Your Account is Deactivated. Contact Trapflix For Reactivation");

            }
            else
            {
                 alert("incorrect login credentials");
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

    <script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="aade7b13-df34-439e-8c2c-0e6fd7cb8c0d";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>

      <script>
      $crisp.push(["set", "user:nickname", ["<?php echo $_SESSION['name']; ?>"]]);
      </script>
</body>
</html>
