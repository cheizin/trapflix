<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    require_once('../setup/connect.php');
session_start();

 require_once('../../phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

 $item_name = mysqli_real_escape_string($dbc,strip_tags($_POST['item_name']));
 $unit_price = mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
 $qtt = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
 $total= mysqli_real_escape_string($dbc,strip_tags($_POST['total']));
   $customer_id = mysqli_real_escape_string($dbc,strip_tags($_POST['customer_id']));
 $product_start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_start_date']));
 $product_end_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_end_date']));
 $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));


 $date_recorded = date('d-M-yy');

$recorded_by = $_SESSION['name'];


 $date_recorded = date('d-M-yy');

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
    $mail->addAddress('danson@panoramaengineering.com');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
      $mail->addCC('moffat1@panoramaengineering.com');
  //  $mail->addBCC('bcc@example.com');

//$mail->setFrom('pcheizin@gmail.com', 'Panorama');
//$mail->addAddress(".$stock_approver.", ".$recorded_by.");     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML
$mail->addAttachment('../../views/stock-item/documents/panoramaLogo.jpg'); 
$mail->Subject = 'Panorama Inventory System';
$mail->Body    = "Dear <b>All</b>, <br/><br/><br/>

The following Product Item was returned to production and recorded in the system by <b>".$recorded_by."</b> <br/
Product Returned <b>".$item_name."</b><br/>Quantity Returned <b>".$qtt."</b>
<br/>Unit Price<b>".$unit_price."</b>
<br/>Total Cost of Stock Returned <b>".$total_stock."</b><br/>
Unit price <b>".$unit_price."</b><br/>Quantity <b>".$qtt."</b><br/> Stocks Total Value<b>".$total."</b><br/>
 <br/>
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
