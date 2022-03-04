<?php

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  require_once('../setup/connect.php');
  session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $customer_name = mysqli_real_escape_string($dbc,strip_tags($_POST['customer_name']));
 $contact = mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));
 $email2 = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
 $sector= mysqli_real_escape_string($dbc,strip_tags($_POST['sector']));

$recorded_by = $_SESSION['name'];

$email = $_SESSION['email'];

//$mail->isSMTP();                                            // Send using SMTP
$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

//  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'pcheizin@gmail.com';                     // SMTP username
$mail->Password   = '9000@Kenya';                               // SMTP password

    //Recipients
    $mail->setFrom('nonreply@panorama.com', 'Panorama Inventory System');
    $mail->addAddress("$email", 'Panorama Inventory System');     // Add a recipient
  //  $mail->addAddress('danson@panoramaengineering.com');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
     // $mail->addCC('moffat1@panoramaengineering.com');

    //$mail->addAddress('ellen@example.com');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //  $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');

    // Attachments
  //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg'); 
    $mail->Subject = 'Panorama Inventory System';
    $mail->Body    = "Dear <b>All</b>, <br/><br/><br/>

    Customer <b>".$supplier_name."</b><br/>Contact <b>".$contact."</b><br/>Email <b>".$email2."</b>
<br/> has been added in the system by <b>".$recorded_by."</b>
    <br/><br/><br/>
    Please log in to <a href='https://inventory.panoramaengineering.com/'>Panorama Inventory</a> to view Details.
    <br/><br/><br/><br/><br/>
    <b>This is an automated message, please do not reply</b>";


    if(!$mail->send())
    {
      echo 'Message has been sent';
    }
    else {
      echo 'Message not sent';
    }


}

//END OF POST REQUEST

 ?>
