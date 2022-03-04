<?php
require_once('../setup/connect.php');
require_once('../../phpmailer/PHPMailerAutoload.php');
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    
    if(isset($_POST['email_reset_password']))
    {
        $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
        $token = bin2hex(random_bytes(50));
        
        
        $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT name FROM users WHERE email='".$email."'"));
        $name = $name['name'];
        
        $sql= mysqli_query($dbc,"INSERT INTO password_resets
          (email, token)
            VALUES ('".$email."','".$token."')") or die (mysqli_error($dbc));
        
        //send email
        $mail = new phpmailer;
        $mail->Host= 'smtp.gmail.com';
        $mail->SMTPSecure = 'tls';
        $mail->Username   = 'pcheizin@gmail.com';
        $mail->Password   = '9000@Kenya';
        $mail->setFrom('info@trapflix.com', 'TRAPFLIX SYSTEM');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset for Trapflix Account';
        $mail->Body    = "<p>A password reset request has been sent for your Trapflix Account</p>
                     <p><a href='https://trapflix.com/reset-password.php?code=$token'>Click here to reset your password</a> </p>
                     
                     <p>If you are not the one who requested this action, please ignore this email</p>
    
    
    ";
    
        if(!$mail->send())
        {
          echo 'Message not Sent';
        }
        else
        {
            if($sql)
            {
                echo 'success';
            }
        }
            
            
            //end send email
        
        
    }
    
    if(isset($_POST['reset_password']))
    {
        $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));
        $password = mysqli_real_escape_string($dbc,strip_tags($_POST['password']));
        $password = md5($password);
        
        $email = mysqli_fetch_array(mysqli_query($dbc,"SELECT email FROM password_resets WHERE token='".$token."' ORDER BY id DESC LIMIT 1"));
        $email = $email['email'];
        
        
        $sql = mysqli_query($dbc,"UPDATE users SET password = '".$password."' WHERE email='".$email."'");
        
        
        if($sql)
        {
            echo 'password_changed';
        }
    }

}

?>
