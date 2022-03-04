<?php
session_start();

    require_once('controllers/setup/connect.php');
session_start();

 require_once('phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;




    //$mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

  //  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'pcheizin@gmail.com';                     // SMTP username
    $mail->Password   = '9000@Kenya';                               // SMTP password

    //Recipients
$mail->setFrom('inventory@panoramaengineering.com', 'HRMIS System');

    $mail->addAddress('pitarcheizin@gmail.com', 'Password change for Panorama Inventory System');     // Add a recipient
      // $mail->addCC('');
   $mail->addBCC('pchege@students.uonbi.ac.ke');
      $mail->addCC('pitarcheizin@gmail.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML
$mail->addAttachment('views/stock-item/documents/panoramaLogo.jpg');

$mail->Subject = 'Panorama Inventory System';
$mail->Body    = "Dear <b>Peter</b>, <br/><br/><br/>

inventory On Potential staffing sdafasfas grfdg the login page Click on forgot password<br/><br/> Use the following Link for Login
<b>https://inventory.panoramaengineering.com</b><br/><br/>

<br/><br/><br/><br/><br/>
<b>This is an automated message.<br/> Thank you</b>";


if(!$mail->send())
{
  echo 'Message not Sent';
}
else {
  echo 'Message has been sent';
}


//END OF POST REQUEST

 ?>
