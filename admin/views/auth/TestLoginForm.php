
<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  ?>
<div class="row">
  <div class="col-md-4 offset-md-4">

    <div class="card animated slideInLeft">
      <form id="test-login-form">
          <div class="card-header bg-light">
            Log in
          </div>
          <div class="card-body">
            <label for="email">Name
               <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
               title="Your Name associated to your Windows account, i.e MUser" id="name_help"></i></label>
            <input type="text" autocomplete="on" id="email" name="email" class="form-control" maxlength="20" required placeholder="input your Windows username">
            <div class="row">
              <br/>
            </div>
            <!--<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>-->
              <label for="password">Password
                <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                title="Your Password associated to your Windows account" id="password_help"></i></label>
              <div class="input-group add-on">
                <input type="password" name="password" id="password" maxlength="40" class="form-control pwd"  required placeholder="input your Windows password">
                <span class="input-group-btn">
                  <button class="btn btn-default reveal" type="button"><i class="fa fa-eye"></i></button>
                </span>
              </div>
              <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
             </div> <!-- form-group// -->
             <div class="card-footer text-right">
               <button type="submit" class="btn btn-primary btn-block"> Log in  </button>
             </div>
        </form>
      </div>

    </div>
  </div>
  </div>

  <?php
}
else
{
  exit("form not submitted");
}
 ?>
