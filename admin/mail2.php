

<?php
session_start();

    require_once('controllers/setup/connect.php');
session_start();

 require_once('phpmailer/PHPMailerAutoload.php');
 $mail = new phpmailer;

$task_update_id = mysqli_real_escape_string($dbc,strip_tags($_POST['task_update_id']));

//select last inserted task that matches the task update id

$task = mysqli_fetch_array(mysqli_query($dbc,"SELECT building_block_id, task_description, implementation_progress FROM tasks_updates WHERE changed='no' &&
                                                     task_update_id='".$task_update_id."' ORDER BY task_update_id DESC LIMIT 1"));


$task_description = $task['task_description'];
$implementation_progress = htmlspecialchars_decode(stripslashes($task['implementation_progress']));

$building_block_description = mysqli_fetch_array(mysqli_query($dbc,"SELECT building_block_description FROM building_blocks
                                                                           WHERE building_block_id='".$task['building_block_id']."'"));
$building_block_description = $building_block_description['building_block_description'];

          $sql = mysqli_query($dbc,"SELECT user_id FROM updates_subscriptions") or die(mysqli_error($dbc));
            $mail = new PHPMailer(true); // Instantiation and passing `true` enables exceptions
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'cmmpkenya@gmail.com';                     // SMTP username
            $mail->Password   = 'Masterplan254';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->setFrom('cmmpkenya@gmail.com', 'CMMP-KENYA');


                    //Recipients
                    $mail->AddAddress('pitarcheizin@gmail.com', 'Password change for Panorama Inventory System');
            
                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'CMMP Update!';
                    $mail->Body    = 'Dear '.$user['name'].' ,<br/><br/>
                                       We have a new update for the task <b>'.$task_description.'</b> that lies under the
                                       Building Block : <b>'.$building_block_description.'</b>.
                                       <br/> Please find the implementation progress below:<br/><br/>
                                       '.$implementation_progress.'<br/><br/>
                                       For more detailed information, please visit the <a href="#">CMMP Portal</a>';
                    $mail->AltBody = 'Dear '.$user['name'].' ,<br/><br/>
                                       We have a new update for the task <b>'.$task_description.'</b> that lies under the
                                       Building Block : <b>'.$building_block_description.'</b>.
                                       <br/> Please find the implementation progress below:<br/><br/>
                                       '.$implementation_progress.'<br/><br/>
                                       For more detailed information, please visit the <a href="#">CMMP Portal</a>';
                                       
                                       if(!$mail->send())
{
 echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
else {
  echo 'Message has been sent';
}



            ?>
