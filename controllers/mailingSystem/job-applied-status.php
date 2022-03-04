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
 $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));

   $status_name = mysqli_real_escape_string($dbc,strip_tags($_POST['status_name']));

      $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

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
    $mail->addAddress("$Email");     // Add a recipient
     $mail->addCC("$email");               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //    $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');
//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
//$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg');

if($_POST['status_name'] == "Testing" )
{
  ?>
<link href="../../assets/css/mail.css" rel="stylesheet" />
  <table width="100%" border="0" cellspacing="0" cellpadding="20" background="https://career.panoramaengineering.com/assets/img/slider_bg.jpg">
<tr>
    <td>
        <p>
          <?php
//$mail->AddEmbeddedImage('../../assets/img/slider_bg.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg
            $mail->Subject = 'Potential Staffing Assessment';
            $mail->Body    = '
            <html>
            <head>
              <title></title>
              '.$css.'
            </head>
            <body>

              <p  class="test">Dear <b>'.$fName.', '.$lName.' </b>, <br/><br/><br/>

                You have an Assessment Test for <b>'.$job_title.'</b> in which you Applied in <b> Potential Staffing Career Portal </b> <br/>
                You <b>MUST</b> be <b>logged in</b> to access the Assessment Below:- <br/>
                Click <a href="https://career.panoramaengineering.com/test_answer_management.php?id=' . $id . '">Assessment Test</a> to Undertake the Assessment

              <br/>
              You can check the Job Application progress status in the system
              <br/><br/>
              <p  class="test">  Please log in to <a href="https://career.panoramaengineering.com/">Career Portal</a> to view more Details.
              <br/><br/>
                <b>This is an automated message, please do not reply</b></p>
                Kind Regards,   <br/>

                Potential Staffing Africa   <br/>
                https:www.potentialstaffing.com   <br/>
                <br/><br/>
                <img src="https://career.panoramaengineering.com/assets/img/potential.png" alt="Website Change Request" />
            </body>
            </html>
            ';


          ?></p>
    </td>
</tr>
</table>
<?php


  if(!$mail->send())
  {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
  else {
    echo 'Message has been sent';
  }
}
else {
  $mail->Subject = 'Potentials Staffing Job Application Status Update';
  $mail->Body    = '
  <html>
  <head>
    <title></title>
    '.$css.'
  </head>
  <body>

    <p  class="test">Dear <b>'.$fName.', '.$lName.' </b>, <br/><br/><br/>

    The Job you Applied <b>'.$job_title.'</b> in <b> Potential Staffing Career Portal </b> <br/>
    Has been updated by the recruiter to <b>'.$status_name.' Stage</b> <br/> The Job details are: - <br/> </p>
    <table>
      <tr>
        <th  class="test2">Company Name</th><th class="test2">Experience Length</th><th class="test2">Employment Type</th><th class="test2">Job Location</th>
        <th class="test2">Country</th> <th class="test2">Deadline</th> <th class="test2">job description</th> <th class="test2">responsibility</th>
      </tr>
      <tr>
        <td class="test">'.$company_name.'</td><td class="test">'.$expLength.'</td><td class="test">'.$emp_type.'</td><td class="test">'.$job_location.'</td>
          <td class="test">'.$country.'</td><td class="test">'.$deadline.'</td><td class="test">'.$job_description.'</td><td class="test">'.$responsibility.'</td>
      </tr>

    </table>
    <br/>
    You can check the Job Application progress status in the system
    <br/><br/>
    <p  class="test">  Please log in to <a href="https://career.panoramaengineering.com/">Career Portal</a> to view more Details.
    <br/><br/>
      <b>This is an automated message, please do not reply</b></p>

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


}
//END OF POST REQUEST

 ?>
