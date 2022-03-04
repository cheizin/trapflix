<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

    $access_level = mysqli_real_escape_string($dbc,strip_tags($_POST['standard']));
    $fName = mysqli_real_escape_string($dbc,strip_tags($_POST['fullName']));
      $contact= mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));
        $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));

$recorded_by = $_SESSION['fName'];

$email = $_SESSION['email'];


    //$mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

  //  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'pcheizin@gmail.com';                     // SMTP username
    $mail->Password   = '9000@Kenya';                               // SMTP password

    //Recipients
    $mail->setFrom('inventory@panoramaengineering.com', 'TRAPFLIX');
    $mail->addAddress("$Email");     // Add a recipient
     //$mail->addCC('pchege@students.uonbi.ac.ke');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //    $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
//$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg');
$mail->Subject = 'TRAPFLIX Sign up';
$mail->Body    = '
<html>
<head>
  <title></title>
  '.$css.'
</head>
<body>

  <p  class="test">Dear <b>'.$fName.',</b>, <br/><br/><br/>
You have Registered on <b>Trapflix</b> 

  <br/>
  <br/><br/>

  <br/><br/>
  Kind Regards,   <br/>

 Trapflix  <br/>
  <br/>
  <br/><br/>

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
