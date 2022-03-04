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
$item_description = mysqli_real_escape_string($dbc,strip_tags($_POST['item_description']));
$category = mysqli_real_escape_string($dbc,strip_tags($_POST['category']));

$payment_type = mysqli_real_escape_string($dbc,strip_tags($_POST['payment_type']));
$invoice_received_id = mysqli_real_escape_string($dbc,strip_tags($_POST['invoice_received_id']));
  $supplier_name= mysqli_real_escape_string($dbc,strip_tags($_POST['supplier_name']));
$unit_price= mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
$qtt= mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
$stock_order_level= mysqli_real_escape_string($dbc,strip_tags($_POST['stock_order_level']));
$stock_approver = mysqli_real_escape_string($dbc,strip_tags($_POST['stock_approver']));

$total = mysqli_real_escape_string($dbc,strip_tags($_POST['total']));


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

The following Stock Item was Added In the system by:- <b>".$recorded_by."</b> <br/>
Stock Item: <b>".$item_name."</b><br/>Description: <b>".$item_description."</b><br/>Category: <b>".$category."</b><br/>Payment Method: <b> Bank</b>
<br/>Invoice Number: <b>".$invoice_received_id."</b><br/>Supplier: <b>".$supplier_name."</b><br/>
Unit price: <b>".$unit_price."</b><br/>Quantity: <b>".$qtt."</b><br/> Re Order Level: <b>".$stock_order_level."</b><br/>Stocks Total Value: <b>".$total."</b><br/>
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
