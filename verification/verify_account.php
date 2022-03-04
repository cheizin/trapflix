
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
<body>
<div id="loading">
   <div id="loading-center">
   </div>
</div>

    <section class="sign-in-page">
   <div class="container">
      <div class="row justify-content-center align-items-center height-self-center">
         <div class="col-lg-5 col-md-12 align-self-center">
            <div class="sign-user_card ">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Verify Account</h3>
                     <p class="text-body">Enter verification code</p>
                        <form id="verify_account_form">
                         <input type="hidden" name="verify">
                         <input type="hidden" name="token" value="<?php echo $_GET['code'] ;?>">
                                                <div class="form-group">
                           <input type="text" name="code" class="form-control mb-0"  placeholder="Verification Code" required>

                        </div>

                        <div class="sign-info">
                           <button type="submit" class="btn btn-hover">Verify</button>
                        </div>
                        
                        <div class="mt-3">
                  <div class="d-flex justify-content-center links">
                    <a href="https://trapflix.com/login.php" class="text-primary ml-2">Sign In</a>
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

<script>
$(document).on("submit","#verify_account_form", function(e){
    e.preventDefault();

    var process_url = 'verify.php';
    var form_data = $('#verify_account_form').serializeArray();
    var form_method = 'POST';
    
    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,

        success: function(data){
            if(data == "success")
            {

                   alert("Account Verified");
                          window.location.href = "https://trapflix.com/login.php?verified=true";
            }
            else if (data = 'invalid')
            {
                alert ('Invalid Verification Code');
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
