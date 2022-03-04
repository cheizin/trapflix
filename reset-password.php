
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
   <title>Trapflix </title>
   <!-- Favicon -->
  <link rel="icon" href="assets/img/house.jpg" type="image/x-icon" />
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
<div id="loading">
   <div id="loading-center">
   </div>
</div>

<?php
if(isset($_GET['code']))
{
    ?>
    <!-- Change Password Form -->
    <section class="sign-in-page">
   <div class="container">
      <div class="row justify-content-center align-items-center height-self-center">
         <div class="col-lg-5 col-md-12 align-self-center">
            <div class="sign-user_card ">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Reset Password</h3>
                     <p class="text-body">Enter a new password</p>
                        <form id="enter_email_form">
                         <input type="hidden" name="reset_password">
                         <input type="hidden" name="token" value="<?php echo $_GET['code'] ;?>">
                                                <div class="form-group">
                           <input type="password" name="password" class="form-control mb-0" id="password" placeholder="Password" required>
                           					  									<input class="form-check-input" type="checkbox" onclick="ShowPassword()" id="show-password"/>
																  <label class="form-check-label" for="show-password">
																    Show Password
																  </label>
                        </div>
                        <div class="form-group">    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                            title="Your Password associated to your Windows account" id="password_help"></i>
                           <input type="password" id="confirm" name="confirm" class="form-control mb-0" id="exampleInputPassword2" placeholder="Confirm Password" required>


                        </div>
                        <div class="sign-info">
                           <button type="submit" class="btn btn-hover">Reset</button>
                        </div>
                        
                        <div class="mt-3">
                  <div class="d-flex justify-content-center links">
                    <a href="index.php" class="text-primary ml-2">Sign In</a>
                  </div>
               </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Sign in END -->
      <!-- color-customizer -->
   </div>
</section>
    <!-- End Change Password Form -->
    <?php
}
else 
{
    ?>
    <!-- Send Email for Password Reset -->
<section class="sign-in-page">
   <div class="container">
      <div class="row justify-content-center align-items-center height-self-center">
         <div class="col-lg-5 col-md-12 align-self-center">
            <div class="sign-user_card ">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Reset Password</h3>
                     <p class="text-body">Enter your email address and we'll send you an email with instructions to reset your password.</p>
                        <form id="enter_email_form">
                         <input type="hidden" name="email_reset_password">
                        <div class="form-group">
                           <input type="email" class="form-control mb-0" id="email" name="email" placeholder="Enter email" autocomplete="off" required>
                        </div>
                        <div class="sign-info">
                           <button type="submit" class="btn btn-hover">Reset</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Sign in END -->
      <!-- color-customizer -->
   </div>
</section>
<!-- End Send Email For Password Reset-->
    <?php
}


?>







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

<script>
$(document).on("submit","#enter_email_form", function(e){
    e.preventDefault();

    var process_url = 'controllers/password-validation/password_change_controller.php';
    var form_data = $('#enter_email_form').serializeArray();
    var form_method = 'POST';
    
    console.log(form_data);


    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,

        success: function(data){
            if(data == "success")
            {

                   alert("Mail sent check your Email for Password Recovery instruction");
                          window.location.href = "index.php";
            }
            else if (data = 'password_changed')
            {
                alert ('Password Changed. You can now log in with your new password');
                          window.location.href = "index.php";
                
            }


                        else if(data == 'duplicate')
                          {

                            console.log(data);

                   alert("The user is not Registered in the system");
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
    function ShowPassword2() {
  var x = document.getElementById("password2");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
</body>
</html>
