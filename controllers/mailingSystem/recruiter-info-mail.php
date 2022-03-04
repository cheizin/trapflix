<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $access_level = mysqli_real_escape_string($dbc,strip_tags($_POST['recruiter']));
 $fName = mysqli_real_escape_string($dbc,strip_tags($_POST['cName']));
 $lName = mysqli_real_escape_string($dbc,strip_tags($_POST['industry_name']));
 $dob = mysqli_real_escape_string($dbc,strip_tags($_POST['dob']));
 $nationality= mysqli_real_escape_string($dbc,strip_tags($_POST['nationality']));
 //$gender = mysqli_real_escape_string($dbc,strip_tags($_POST['gender']));

 $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
 $location = mysqli_real_escape_string($dbc,strip_tags($_POST['location']));
   $contact= mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));

 $highestQualification = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_no']));
 $currentPosition = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_type']));
 $companyName = mysqli_real_escape_string($dbc,strip_tags($_POST['about_us_name']));

 $experience = mysqli_real_escape_string($dbc,strip_tags($_POST['web_url']));

 $passwordmd5 = md5($password);
$token2 = mysqli_real_escape_string($dbc,strip_tags($_POST['token2']));

 $date_recorded = date('d-M-yy');


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
    $mail->addAddress("$Email");     // Add a recipient
     //$mail->addCC('pchege@students.uonbi.ac.ke');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //    $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
//$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg');
$mail->Subject = 'Potential Staffing Recruiter Sign up';
$mail->Body    = '
<html>
<head>
  <title></title>
  '.$css.'
</head>
<body>

  <p  class="test">Dear <b>'.$fName.', '.$lName.' </b>, <br/><br/><br/>
You have Registered as a <b>Recruiter</b> in <b> Potential Staffing Career Portal </b>  as follows:- <br/> </p>
  <table>
    <tr>
      <th  class="test2">Company Name/th><th class="test2">Industry Name/th><th class="test2">Date of Registration</th><th class="test2">Email</th>
      <th class="test2">Location</th> <th class="test2">Contact</th> <th class="test2">Nationality</th> <th class="test2">Employees Number</th>
      <th class="test2">Company Type</th> <th class="test2">Company Website:</th>
    </tr>
    <tr>
      <td class="test">'.$fName.'</td><td class="test">'.$lName.'</td><td class="test">'.$dob.'</td><td class="test">'.$Email.'</td>
        <td class="test">'.$location.'</td><td class="test">'.$nationality.'</td><td class="test">'.$highestQualification.'</td><td class="test">'.$currentPosition.'</td>
      <td class="test">'.$experience.'</td>
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
