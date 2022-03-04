<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
 $item_name = mysqli_real_escape_string($dbc,strip_tags($_POST['item_name']));
   $recorded_by = $_SESSION['name'];

   // Upload file

   $uploadDir = '../../views/stock-item/documents/';
   $uploadStatus = 1;
   $uploadedFile = '';
   if(!empty($_FILES["additional_file"]["name"])){

       // File path config
       $fileName = basename($_FILES["additional_file"]["name"]);
       $targetFilePath = $uploadDir . $fileName;
       $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
       $additional_file = $fileName;
   }
   else
   {
     $additional_file = '';
   }

   // Upload file

   $uploadDir = '../../views/stock-item/documents/';
   $uploadStatus = 1;
   $uploadedFile = '';
   if(!empty($_FILES["additional_file2"]["name"])){

       // File path config
       $fileName = basename($_FILES["additional_file2"]["name"]);
       $targetFilePath2 = $uploadDir . $fileName;
       $fileType = pathinfo($targetFilePath2, PATHINFO_EXTENSION);
        $additional_file2 = $fileName;

   }
   else
   {
     $additional_file2 = '';
   }



   $uploadDir = '../../views/stock-item/documents/';
   $uploadStatus = 1;
   $uploadedFile = '';
   if(!empty($_FILES["file"]["name"])){

       // File path config
       $fileName = basename($_FILES["file"]["name"]);
       $targetFilePath3 = $uploadDir . $fileName;
       $fileType = pathinfo($targetFilePath3, PATHINFO_EXTENSION);
         $uploadedFile = $fileName;
   }

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
 //   $mail->addAddress('danson@panoramaengineering.com');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
   //   $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');

  // Attachments
//  $mail->addAttachment('../../views/stock-item/documents/'.$additional_file);         // Add attachments
//  $mail->addAttachment('../../views/stock-item/documents/'.$additional_file2);    // Optional name
  //  $mail->addAttachment('../../views/stock-item/documents/'.$uploadedFile);    // Optional name

 $mail->addAttachment('$targetFilePath');    // Optional name
  $mail->addAttachment('$targetFilePath2');    // Optional name
   $mail->addAttachment('$targetFilePath3');    // Optional name

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML
$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg'); 
$mail->Subject = 'Panorama Inventory System';
$mail->Body    = "Dear <b>All</b>, <br/><br/><br/>

Stock Item <b>".$item_name."</b> Documents were Attached in the system and Recorded by <b>".$recorded_by."</b> <br/
<br/><br/><br/>
Please log in to <a href='https://inventory.panoramaengineering.com/'>Panorama Inventory</a> to view Details.
<br/><br/><br/><br/><br/>
<b>This is an automated message, please do not reply</b>";

if(!$mail->send())
{
  echo 'Message not Sent';
}
else {
  echo 'Message has been sent';
}

}
//END OF POST REQUEST

 ?>
