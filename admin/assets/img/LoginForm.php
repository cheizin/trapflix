
<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  require_once('../../controllers/setup/connect.php');
  $headers = apache_request_headers();
  if (!isset($headers['Authorization'])){
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: NTLM');
    exit;
  }
  $auth = $headers['Authorization'];
  if (substr($auth,0,5) == 'NTLM ') {
    $msg = base64_decode(substr($auth, 5));
    if (substr($msg, 0, 8) != "NTLMSSP\x00")
      die('error header not recognised');
    if ($msg[8] == "\x01") {
      $msg2 = "NTLMSSP\x00\x02\x00\x00\x00".
          "\x00\x00\x00\x00". // target name len/alloc
        "\x00\x00\x00\x00". // target name offset
        "\x01\x02\x81\x00". // flags
        "\x00\x00\x00\x00\x00\x00\x00\x00". // challenge
        "\x00\x00\x00\x00\x00\x00\x00\x00". // context
        "\x00\x00\x00\x00\x00\x00\x00\x00"; // target info len/alloc/offset
      header('HTTP/1.1 401 Unauthorized');
      header('WWW-Authenticate: NTLM '.trim(base64_encode($msg2)));
      exit;
    }
    else if ($msg[8] == "\x03") {
      function get_msg_str($msg, $start, $unicode = true) {
        $len = (ord($msg[$start+1]) * 256) + ord($msg[$start]);
        $off = (ord($msg[$start+5]) * 256) + ord($msg[$start+4]);
        if ($unicode)
          return str_replace("\0", '', substr($msg, $off, $len));
        else
          return substr($msg, $off, $len);
      }
      $user = get_msg_str($msg, 36);
      $domain = get_msg_str($msg, 28);
      $workstation = get_msg_str($msg, 44);

    }
  }
  ?>
<div class="row">

  <div class="lockscreen-wrapper animated slideInLeft card card-body">
  <div class="lockscreen-logo">
    <i class="fal fa-user-lock fa-lg text-success"></i>
    <small class="text-muted" style="font-size:12px;"> locked</small>
  </div>
  <!-- User name -->
  <div class="lockscreen-name mb-4 text-muted">
    <strong>CMAKE\<?php echo $user;?></strong>
  </div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <!--<img src="assets/img/avatar.png" alt="User Image">-->
      <i class="fal fa-user-lock fa-lg text-success"></i>
      <small class="text-muted" style="font-size:12px;"> locked</small>
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials border" id="login-form">
      <div class="input-group ">
        <input type="hidden" id="email" name="email" class="form-control"
                       maxlength="20" required  value="<?php echo $user;?>"
                       placeholder="Your Windows Username" readonly
                />
        <input type="password" name="password" id="password"
                maxlength="40" class="form-control pwd border-left"  required placeholder="Your Windows Password">

        <span class="input-group-append password-reveal-icon d-none">
            <button class="btn btn-default reveal" type="button"><i class="fa fa-eye"></i></button>
        </span>
        <div class="input-group-append">
          <button type="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center text-muted">
    <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
  </div>
  <div class="text-center">
    <a href="#" onclick="CannotLogin('<?php echo $user;?>');">Click here if you can't login</a>
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
