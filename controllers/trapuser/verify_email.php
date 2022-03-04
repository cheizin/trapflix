<?php
require_once('../../controllers/setup/connect.php');
require_once('../../phpmailer/PHPMailerAutoload.php');

if(isset($_GET['code']))
{
    $verification_code = mysqli_real_escape_string($dbc,strip_tags($_GET['code']));
    
    $sql = mysqli_query($dbc,"UPDATE users SET is_verified=1 WHERE email IN 
    
                               (SELECT email FROM user_verification_codes WHERE verification_code='".$verification_code."')
                             ");
                               
    
    if($sql)
    {
        header("Location: https://trapflix.com?verified=true");
    }
    
}
else 
{
//echo(md5(uniqid().mt_rand()));

$fName = mysqli_real_escape_string($dbc,strip_tags($_POST['fullName']));
$Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));

$verification_code = random_bytes(50);
$verification_code = bin2hex($verification_code);

$sql = mysqli_query($dbc,"INSERT INTO user_verification_codes
        (email,verification_code)
          VALUES ( '".$Email."','".$verification_code."')") or die (mysqli_error($dbc));
          

if(!$sql)
{
    exit('Please try again');
}



$mail = new phpmailer;
    //$mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

  //  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'pcheizin@gmail.com';                     // SMTP username
    $mail->Password   = '9000@Kenya';                               // SMTP password

    //Recipients
$mail->setFrom('info@trapflix.com', 'TRAPFLIX SYSTEM');

$mail->addAddress($Email, $fName);     // Add a recipient

$mail->isHTML(true);

$mail->Subject = 'Verify your Trapflix Account';
$mail->Body    = "<p>A Trapflix account has been created with this email</p>
                 <p><a href='https://trapflix.com/controllers/trapuser/verify_email.php?code=$verification_code'>Click here to verify your account</a> </p>
                 
                 <p>If you are not the one who requested this action, please ignore this email</p>


";


if(!$mail->send())
{
  echo 'Message not Sent';
}
else {
  echo 'Message has been sent';
}
}



 ?>
