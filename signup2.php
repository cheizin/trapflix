

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
   <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/fonts/fontawesome-pro-5.12.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/font-awesome-animation.css">
  <!-- Ionicons 2.0.1-->
  <link rel="stylesheet" href="assets/css/ionicons.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- sweetalert -->
  <link rel="stylesheet" href="assets/css/sweetalert2@9.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="assets/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.css">
  <!-- Datatables-->
  <link rel="stylesheet" type="text/css" href="assets/libs/datatables/datatables.min.css"/>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui@3.2.0/material-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
  <!-- Hover-->
  <link rel="stylesheet"  href="assets/css/hover.css"/>
  	<!-- Animate-->
  <link rel="stylesheet"  href="assets/css/animate.css"/>
  <!--placeholder-->
  <link rel="stylesheet" href="assets/libs/placeholder/placeholder-loading.min.css">
</head>
<body>
<div id="loading">
   <div id="loading-center">
   </div>
</div>

<!-- MainContent -->
<section class="sign-in-page">
   <div class="container">
      <div class="row justify-content-center align-items-center height-self-center">
         <div class="col-lg-5 col-md-12 align-self-center">
            <div class="sign-user_card ">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Sign Up</h3>
                     <form id="add-job-seeker-form2" class="mt-4" enctype="multipart/form-data">
                       <input type="hidden" value="add-job-seeker" name="add-job-seeker">
                         <input type="hidden" value="standard" name="standard">

                        <div class="form-group">
                           <input type="text" name="fullName" class="form-control mb-0" id="exampleInputEmail2" placeholder="Enter Full Name" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                           <input type="text" name="contact" class="form-control mb-0" id="exampleInputEmail25" placeholder="Enter phone number without +" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                           <input type="email" name="Email" class="form-control mb-0" id="exampleInputEmail3" placeholder="Enter email" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                           <input type="password" name="password" class="form-control mb-0" id="exampleInputPassword2" placeholder="Password" required>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                           <input type="checkbox" class="custom-control-input" id="customCheck">
                           <label class="custom-control-label" for="customCheck">I accept <a href="#" class="text-primary"> Terms and Conditions</a></label>
                        </div>

                        <button type="submit" class="btn btn-hover">Sign Up</button>

                     </form>
                  </div>
               </div>
               <div class="mt-3">
                  <div class="d-flex justify-content-center links">
                     Already have an account? <a href="login.html" class="text-primary ml-2">Sign In</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- MainContent End-->
<!-- jQuery, Popper JS -->

<!--blockui ver 2.70-->
<script src="assets/js/jquery.blockUI.js"></script>
<!--sweetalert-->
<script src="assets/js/sweetalert2@9.js"></script>
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

<!-- validators -->
<script src="controllers/validators.js"></script>

<!-- forms -->
<script src="controllers/forms.js?v=69"></script>


<script>
$(document).on("submit", "#add-job-seeker-form2", function(e){
    e.preventDefault();

    var process_url = 'controllers/trapuser/registercontroller.php';
    var form_data = $('#add-job-seeker-form2').serializeArray();
    var form_method = 'POST';

    $.ajax({
        url:process_url,
        data:form_data,
        method:form_method,

        success: function(data){

            if(data == "success")
            {

              // Add the .disabled class
              btn.classList.add('disabled');

              // Store the original text to a data attribute
              btn.setAttribute('data-original', btn.textContent);

              // Update the button text
              btn.textContent = 'Submitting Details...';

                  console.log(data);
                          location.reload();
                  let timerInterval
                  Swal.fire({
                    title: 'Auto close alert!',
                    html: 'I will close in <b></b> milliseconds.',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                      Swal.showLoading()
                      timerInterval = setInterval(() => {
                        const content = Swal.getHtmlContainer()
                        if (content) {
                          const b = content.querySelector('b')
                          if (b) {
                            b.textContent = Swal.getTimerLeft()
                          }
                        }
                      }, 100)
                    },
                    willClose: () => {
                      clearInterval(timerInterval)
                    }
                  }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                      console.log('I was closed by the timer')
                    }
                  })
                  /*
              Toast.fire({
                 icon: 'success',
                 title: 'Personal Information Successfully Added'
                });
                */

 window.location.href = "login.php";
    // Restore the button text
    btn.textContent = btn.getAttribute('data-original');

    // Remove the data attribute
btn.removeAttribute('data-original');



            }
            else if(data == "invalid")
            {
            console.log(data);

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


</script>
</body>
</html>
