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
    
    $mail->addAddress('prchege@gmail.com', 'Password change for Panorama Inventory System');     // Add a recipient
      // $mail->addCC('');
   $mail->addBCC('pchege@students.uonbi.ac.ke');
      //$mail->addCC('pitarcheizin@gmail.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient

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
$mail->Subject = 'Potential Staffing Email Marketing';
$mail->Body    ='
<html>
<head>
  <title></title>
  '.$css.'
</head>
<body>

  <p  class="test">Dear <b>Sir, </b>, <br/><br/><br/>


      You have scheduled a mail to be sent
 @ <b>'.$birthdaytime.'</b><b> The scheduled mail details is as below </b> <br/>
<p>'.$long_desc.'</p>
<br/>
 You will be notified once the Email has been delivered Successfully
  <br/>

  <img src="https://career.panoramaengineering.com/assets/img/potential.png" alt="Website Change Request" />
</body>
</html>
';




if(!$mail->send())
{
  echo 'Message not Sent';
}
else {
  echo 'Message has been sent';
}


//END OF POST REQUEST

 ?>
