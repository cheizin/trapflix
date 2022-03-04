<?php
include("controllers/setup/connect.php");
require_once("assets/libs/BrowserDetection/lib/BrowserDetection.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SignUp| Wisgen</title>

    <!-- Bootstrap core CSS -->
   <link href="bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
   <link rel="icon" href="dist/img/" sizes="16x16" type="image/png">
    <!-- Custom styles for this template -->
    <link href="dist/css/signin.css" rel="stylesheet">
    <link href="dist/css/loader.css" rel="stylesheet">

        <link rel="stylesheet" href="bower_components/bootstrap-hover/main.css">


        <link type="text/css" rel="stylesheet" href="bower_components/sweet/sweetalert.css"  media="screen,projection"/>



  </head>

  <body>
 <nav class="navbar navbar-default navbar-cma">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Wisegen System</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#" title="Wisegen System">Wisegen<span class="sr-only">(current)</span></a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Useful Links <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="https://chezma.com" target="_blank">chezmastore</a></li>

          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

    <div class="container">
      <div class="row">
              <div class="col-md-4"></div>
              <div class="col-md-4 col-xs-12">
                <div class="panel panel-default">
                  <div class="panel-heading text-center"><strong>Please Sign UP</strong></div>
                      <div class="panel-body">
                        <div id="loader"></div>
                        <div id="feedback_message" class="text-center"></div><br/>
                            <form id="signUP" method="POST" >


  <div class="row">
                                    <br/>
                                    </div>
                                  <label for="Name">Full Name
                                     <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                     title="Enter Your full ID name" id="name_help"></i></label>
                                  <input type="text" id="Name" name="Name" class="form-control" required placeholder="Enter Your full ID name">
                                  <div class="row">
                                    <br/>
                                    </div>
                                             <label for="contact">Contact
                                     <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                     title="Enter your phone number" id="name_help"></i></label>
                                  <input type="text" id="DepartmentCode" name="DepartmentCode" class="form-control" required placeholder="Enter your phone number">
                                  <div class="row">
                                    <br/>

                                  </div>
                                          <label for="contact">Registration Type
                                     <select class="select2 form-control" name="access_level">

                                <option value="student">Student</option>
                                <option value="dstv_viewer" selected>DSTV Viewer</option>
                                <option value="standard" selected>Standard</option>
                                 <option value="wifi_user">wifi user</option>


                      </select>
                                  <div class="row">
                                    <br/>

                                  </div>
                                                      <label for="contact">Email </label>
                                     <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                     title="Enter email address or preferred username" id="name_help"></i></label>
                                  <input type="email" id="Email" name="Email" class="form-control" required placeholder="Enter email address or preferred username">
                                  <div class="row">
                                    <br/>

                                  </div>
                                  <!--<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>-->
                                     <span class="text-info" id="caps-lock">CAPS LOCK IS ON!</span>
                                    <label for="password">Password
                                      <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                      title="Enter your unique password" id=""></i></label>
                                    <div class="input-group add-on">
                                      <input type="password" name="EmpNo" id="EmpNo" class="form-control pwd"  required placeholder="Enter your unique password">
                                      <span class="input-group-btn">
                                        <button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
                                      </span>
                                    </div>
                                    <div class="row">
                                    <br/>

                                  </div>

                                                  <label for="contact">Confirm Password
                                     <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                     title="Confirm your password" id="password_help"></i></label>
                                  <input type="password" id="confirm" name="confirm" class="form-control" required placeholder="Confirm your password">
                                  <div class="row">
                                    <br/>

                                  </div>

                              <label>
      <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
    </label>

    <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>


                              <div class="row"><br/>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign up</button>
                                </div>
                              </div>
                            </form>
                      </div>
                      <div class="panel-footer">Contact system administrator if you can't signup</div>
                  </div>
              </div>
              <div class="col-md-4"></div>
          <!--<div class="col-md-8 col-xs-12">
              <div class="jumbotron">
                  <h1>Projects</h1>
              </div>
          </div>-->
      </div>


    </div> <!-- /container -->
    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
    //begin of signup form
    $('#signUP').submit(function(e){
        e.preventDefault();

        var form_data = $(this).serializeArray();
        var form_url = 'functions/process-signUP.php';
        var form_method = 'POST';
        var loader =`<div class="loader text-center"></div>`;
        $('#loader').html(loader);

        $.ajax({
            data : form_data,
            url  : form_url,
            method : form_method,
            success:function(data){
                $('#loader').html('');
                if(data == 'success')
                {
                    swal({
                        title: "Wisegen System Sign Up",
                        text: "Successsfully Signed up",
                        type: "success",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        closeOnConfirm: false } ,
                        function(isConfirm){
                            if (isConfirm)
							{
						location.replace("index.php");
							}
                      });
                }
                else if(data == 'failed')
                {
                   var failed_message =  `<h4 class="alert alert-danger">
                        An error occurred
                    </h4>`;
                    $('#feedback_message').html(failed_message.data);
                }
                  else if(data == 'duplicate')
                {
                   var failed_message =  `<h4 class="alert alert-danger">
                        Passwords don NOT match
                    </h4>`;
                    $('#feedback_message').html(failed_message.data);
                }
                else if(data =='not-allowed')
                {
                    var invalid_file =  `<h4 class="alert alert-danger">
                        Only pdf,doc, and docx files allowed
                    </h4>`;
                    $('#feedback_message').html(invalid_file);
                }
                else
                {
                    $('#feedback_message').html("<span class='alert alert-danger text-center'>System Error. Try Again</span>");
                }
                console.log(data);
            }

        });
    });

    //end of signup form
    </script>
    <script>
        //make password visible
          $(".reveal").mousedown(function() {
              $(".pwd").replaceWith($('.pwd').clone().attr('type', 'text'));
          })
          .mouseup(function() {
            $(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
          })
          .mouseout(function() {
            $(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
          });

          //caps lock notifier
          var input = document.getElementById("password");
          var text = document.getElementById("caps-lock");
          input.addEventListener("keyup", function(event) {

          if (event.getModifierState("CapsLock")) {
              text.style.display = "block";
            } else {
              text.style.display = "none";
            }
          });

          $('#name_help').tooltip('enable');
          $('#password_help').tooltip('enable');
    </script>


    <script>
function triggerClick(e) {
  document.querySelector('#profileImage').click();
}
function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}
</script>


<script>
$('#confirm').on('keyup', function () {
  if ($('#EmpNo').val() == $('#confirm').val()) {
    $('#password_help').html(' Password Matched').css('color', 'blue');
  } else
    $('#password_help').html('Not Matching').css('color', 'red');
});

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="bower_components/sweet/sweetalert.min.js"></script>
  </body>
</html>
