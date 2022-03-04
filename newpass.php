
<?php

if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("controllers/setup/connect.php");
require_once("controllers/auth/auth.php");

$token = $_GET['token'];


if(!isset($_GET['token']))
{
  exit("Invalid Link. No security Token Exist");
}


$token_validation = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM password_resets  ORDER BY id DESC LIMIT 1"));

//$valid_token = $token_validation['token'];


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

<?php

if($token_validation['token'] == $token)
{?>

<section class="sign-in-page">
   <div class="container">
      <div class="row justify-content-center align-items-center height-self-center">
         <div class="col-lg-5 col-md-12 align-self-center">
            <div class="sign-user_card ">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Change Password</h3>
                               <form id="potential-change-password-form">
   <input type="hidden" name="email" value="<?php echo $token_validation['email']; ?>">
  <!-- form validation messages -->
                        <div class="form-group">

      <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
      title="Your Password" id=""></i></label>
    <input type="password" autocomplete="on" id="password" name="password" class="form-control" placeholder="Enter New Password">
                        </div>
                        
                        <div class="form-group">
                                <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
      title="Your Passwor" id="password_help"></i></label>
    <input type="password" name="new_pass_crtr" id="confirm" class="form-control" placeholder="Confirm New Password">
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
                     Don't have an account? <a href="signup.php" class="text-primary ml-2">Sign Up</a>
                  </div>
                  <div class="d-flex justify-content-center links">
                     <a href="reset-password.php" class="f-link">Forgot your password?</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- MainContent End-->

<?php
}
else
{
?>
<br/>
<div class="alert alert-info">
<strong><i class="fa fa-info-circle"></i>  <a href="enter_email.php" title="Expired Token. Click here to request Another" >The token has Expired. Click Here to send another request </a></strong>
</div>

<?php
}
?>

<!-- back-to-top End -->
<!-- jQuery, Popper JS -->
<script src="js/jquery-3.4.1.min.js"></script>
<script>
$('#confirm').on('keyup', function () {
  if ($('#password').val() == $('#confirm').val()) {
    $('#password_help').html(' Password Matched').css('color', 'blue');
  } else
    $('#password_help').html('Not Matching').css('color', 'red');
});

</script>
<script>
    
    // Change password portal
$(document).on("submit","#potential-change-password-form", function(e){
    e.preventDefault();
    var btn = document.querySelector('.submitting');

// Add the .disabled class
btn.classList.add('disabled');

// Store the original text to a data attribute
btn.setAttribute('data-original', btn.textContent);

// Update the button text
btn.textContent = 'Changing Password...';
    var form_data = $(this).serializeArray();
    var form_url = 'controllers/password-validation/password_change_controller.php';
    var form_method = 'POST';

    $.ajax({
        type: form_method,
        url: form_url,
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              Toast.fire({
                 icon: 'success',
                 title: 'Password Successfully Changed'
                });

  window.location.href = "index.php";

                $.ajax({
                //  location.replace("https://inventory.panoramaengineering.com/");
                  data : $('#potential-change-password-form').serializeArray(),
                  type: "post",
                  url: "controllers/password-validation/change_password_mailing.php",
                  success: function (data) {
                      console.log(data);
                  }
              });

                // Restore the button text
                btn.textContent = btn.getAttribute('data-original');

                // Remove the data attribute
          btn.removeAttribute('data-original');
          LoadDynamicNavbar();


            }

            else if(data == 'failed')
              {
                Toast.fire({
                   icon: 'error',
                   title: 'The User does Not Exist In the sytem. Kindly Confirm Your Email'
                  });
                  // Restore the button text
                  btn.textContent = btn.getAttribute('data-original');

                  // Remove the data attribute
            btn.removeAttribute('data-original');
              }
              else if(data == 'error-uploading')
                {
                  Toast.fire({
                     icon: 'error',
                     title: 'Failed uploading file. Please try again'
                    });
                }
                else if(data == 'invalid-file')
                  {
                    Toast.fire({
                       icon: 'error',
                       title: 'Invalid file type. Please try again'
                      });
                  }
              else
              {
                Toast.fire({
                   icon: 'error',
                   title: 'Failed. Please contact System Administrator'
                  });
              }
              console.log(data);
        },
        error: function(xhr)
        {
          $.unblockUI();
            Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
            });
        }
    });
});


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
$(document).on("submit", "#test-login-form", function(e){
    e.preventDefault();

    var process_url = 'controllers/auth/TestLoginController.php';
    var form_data = $('#test-login-form').serializeArray();
    var form_method = 'POST';


    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,

        success: function(data){

            if(data == "success")
            {

              window.location.href = "home.php";
                 alert("Login Successfully");
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
