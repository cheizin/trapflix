<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $posted_job = mysqli_real_escape_string($dbc,strip_tags($_POST['posted_job']));
 $hrs  = mysqli_real_escape_string($dbc,strip_tags($_POST['hrs']));
 $mins  = mysqli_real_escape_string($dbc,strip_tags($_POST['mins']));
  $email = $_SESSION['email'];


    //$mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

  //  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'pcheizin@gmail.com';                     // SMTP username
    $mail->Password   = '9000@Kenya';                               // SMTP password

    //Recipients
    $mail->setFrom('inventory@panoramaengineering.com', 'Potential Staffing Career Portal');
    $mail->addAddress("$email");     // Add a recipient
     //$mail->addCC('pchege@students.uonbi.ac.ke');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //    $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
//$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg');
$mail->Subject = 'Potential Staffing Aptitude review Results';
$mail->Body    = "Dear <b>".$email.",</b>, <br/><br/><br/>

You have Set Time for the Job PoST <b>".$posted_job."</b><br/> For  <b>".$hrs."</b>, <b>".$mins."</b>

<br/><br/><br/>
Kind Regards,   <br/>

Potential Staffing Africa   <br/>
https:www.potentialstaffing.com   <br/>
<br/><br/>
<img src="https://career.panoramaengineering.com/assets/img/potential.png" alt="Website Change Request" />

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
