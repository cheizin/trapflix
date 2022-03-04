<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;


 $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
  $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));

    //$mail->isSMTP();                                            // Send using SMTP
    // generate a unique random token of length 100
  //  $token = bin2hex(random_bytes(50));
    // Send email to user with the token in a link they can click on
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

  //  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'pcheizin@gmail.com';                     // SMTP username
    $mail->Password   = '9000@Kenya';                               // SMTP password

    //Recipients
    $mail->setFrom('inventory@panoramaengineering.com', 'Trapflix Systsem Verification');
    $mail->addAddress("$Email");     // Add a recipient
     //$mail->addCC('pchege@students.uonbi.ac.ke');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //    $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');
//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient
$mail->isHTML(true);
$css.='<style type="text/css">
		.test{
			font-size:12px;
			color:green;
		}

				.test2{
			font-size:12px;
			color:red;
		}
		</style>';                              // Set email format to HTML
//$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg');
$mail->Subject = 'Trapflix Password Recovery';
$mail->Body    ='
<html>
<head>
  <title></title>
  '.$css.'
</head>
<body>

  <p  class="test">Dear <b>'.$Email.', </b>, <br/><br/><br/>

Click on this <a href="https://trapflix.panoramaengineering.com/new_password.php?token='.$token.'">link</a> to reset your password on our site

  <br/>
  <br/><br/>
  <p  class="test">  Please log in to <a href="https://career.panoramaengineering.com/">Career Portal</a> to view more Details.
  <br/><br/>
    <b>This is an automated message, please do not reply</b></p>

  <img src="https://trapflix.panoramaengineering.com/assets/img/potential.png" alt="Website Change Request" />
</body>
</html>
';

if(!$mail->send())
{
                      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
else {
  echo 'Message has been sent';
}

}
//END OF POST REQUEST

 ?>
