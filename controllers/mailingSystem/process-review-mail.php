<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
$response_name = mysqli_real_escape_string($dbc,strip_tags($_POST['response_name']));
$email= mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
$marks = mysqli_real_escape_string($dbc,strip_tags($_POST['marks']));

$scheme_name = mysqli_real_escape_string($dbc,strip_tags($_POST['scheme_name']));

$test_name = mysqli_real_escape_string($dbc,strip_tags($_POST['test_name']));

$answer_name = mysqli_real_escape_string($dbc,strip_tags($_POST['answer_name']));


 $time_recorded = date('Y/m/d H:i:s');

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
     $mail->addCC('pchege@students.uonbi.ac.ke');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //    $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
//$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg');
$mail->Subject = 'Potential Staffing Assessement review Results';
$mail->Body    = '
<html>
<head>
  <title></title>
  '.$css.'
</head>
<body>

  <p  class="test">Dear <b>'.$email.', </b>, <br/><br/><br/>

  The Assessement <b>'.$test_name.'</b><br/> With your Response <b> '.$response_name.' </b><br/>
  Has been reviewed by an Acesssor with the Folowing Details:- <br/> </p>
  <table>
    <tr>
      <th  class="test2">Reviewer Correction</th><th class="test2">Marks</th><th class="test2">Rating</th>
    </tr>
    <tr>
      <td class="test">'.$answer_name.'</td><td class="test">'.$marks.'</td><td class="test">'.$scheme_name.'</td>
    </tr>

  </table>
  <br/>
  <br/><br/>
  <p  class="test">  Please log in to <a href="https://career.panoramaengineering.com/">Career Portal</a> to view more Details.
  <br/><br/>
  Kind Regards,   <br/>

  Potential Staffing Africa   <br/>
  https:www.potentialstaffing.com   <br/>
  <br/><br/>
  <img src="https://career.panoramaengineering.com/assets/img/potential.png" alt="Website Change Request" />
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
