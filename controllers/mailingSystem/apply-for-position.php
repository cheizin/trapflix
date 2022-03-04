<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $job_posting_id = mysqli_real_escape_string($dbc,strip_tags($_POST['job_posting_id']));
$job_title = mysqli_real_escape_string($dbc,strip_tags($_POST['job_title']));
$company_name = mysqli_real_escape_string($dbc,strip_tags($_POST['company_name']));
$comp_type = mysqli_real_escape_string($dbc,strip_tags($_POST['comp_type']));
$expLength = mysqli_real_escape_string($dbc,strip_tags($_POST['expLength']));
$emp_type = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_type']));
//$gender = mysqli_real_escape_string($dbc,strip_tags($_POST['gender']));

$job_location = mysqli_real_escape_string($dbc,strip_tags($_POST['job_location']));
$country = mysqli_real_escape_string($dbc,strip_tags($_POST['country']));
  $deadline= mysqli_real_escape_string($dbc,strip_tags($_POST['deadline']));

$job_description = mysqli_real_escape_string($dbc,strip_tags($_POST['job_description']));
$responsibility = mysqli_real_escape_string($dbc,strip_tags($_POST['responsibility']));

$fName = mysqli_real_escape_string($dbc,strip_tags($_POST['fName']));
$lName = mysqli_real_escape_string($dbc,strip_tags($_POST['lName']));

 $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['applicant_email']));

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
$mail->Subject = 'Potential Staffing Job Application';
$mail->Body    = '
<html>
<head>
  <title></title>
  '.$css.'
</head>
<body>

  <p  class="test">Dear <b>'.$fName.', '.$lName.' </b>, <br/>
    Thank you for applying for the vacancy <b>'.$job_title.'</b> in <b> Potential Staffing Career  <br/></p>


  <br/>
  <p  class="test">
  We make it a point to review every CV. With that in mind, please note the following.   <br/>


  1. We do not <b> CHARGE ANY FEE </b>to interview you or REGISTER your CV in our database. Employers pay for the service.   <br/>
<b>
  2. Should you meet our clients requirements, our recruitment team will contact you for a one on one interview at our office. Come with your CV and certificates and latest/the last payslip.  <br/>

  3. To start receiving the latest jobs from our clients directly to your inbox. Register your CV with us. Its simple and free. Click <a href="https://career.panoramaengineering.com/">here</a>  <br/>

  4. Should you not hear from us within the next <b>20 days </b> please consider your application unsuccessful.   <br/>

  5. To avoid disappointment, we encourage you to apply for jobs where you meet at least<b> 70% </b>of the requirements. Focus on quality and not quantity.   <br/>

  Once again thank you and all the best in your career. Register your CV details here.  <br/>

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
