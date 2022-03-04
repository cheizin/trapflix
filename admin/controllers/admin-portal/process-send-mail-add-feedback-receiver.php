<?php
include("../../controllers/setup/connect.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $email = mysqli_real_escape_string($dbc,strip_tags($_POST['feedback_receiver'])); //feedback receiver

  $date_recorded = date("m/d/Y");
  $time_recorded = date("h:i:sa");

  $owners_email[] = $email.','.$_SESSION['email'];
  $to = implode(", ", $owners_email);

  $user = mysqli_fetch_array(mysqli_query($dbc,"SELECT Name FROM staff_users WHERE Email='".$email."'"));


      $subject = 'PPRMIS Feedback Receiver Notification';
      $specific_email = $to;
      $message =  " Dear " .$user['Name']. ", <br/><br/>

                  Your mail has been set to  receive PPRMIS Users Feedback by : ".$_SESSION['name']." <br/><br/>


                 <br/><br/><br/><br/><br/>
                <b>This is an automated message, please do not reply</b>";


      $send_mail = mail($specific_email,$subject,$message,$headers);
      if($send_mail)
      {
        $date_sent = date("d-m-Y h:i:sa");
        $message = mysqli_real_escape_string($dbc,$message);
        $store_sent_mail = mysqli_query($dbc,"INSERT INTO sent_mails (sent_from,sent_to,triggered_by,message_subject,message_body,date_sent)
                                                      VALUES
                                                      ('".$headers."','".$specific_email."','".$person."',
                                                        '".$subject."','".$message."','".$date_sent."')"
                                       ) or die (mysqli_error($dbc));
        exit ("success");
      }
      else
      {
        exit ("failed");
      }


}

?>
