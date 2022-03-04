<?php
include("../../controllers/setup/connect.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $user_feedback_message = mysqli_real_escape_string($dbc,strip_tags($_POST['user_feedback_message']));

  if(isset($_SESSION['name']))
  {
    $person = $_SESSION['name'];
  }
  else
  {
    $person = "Anonymous";
  }

  $date_recorded = date("m/d/Y");
  $time_recorded = date("h:i:sa");



  $sql_feedback_receiver = mysqli_query($dbc,"SELECT * FROM feedback_receiver GROUP BY email") or die(mysqli_error($dbc));
  if(mysqli_num_rows($sql_feedback_receiver) > 0)
  {
    while ($owners = mysqli_fetch_array($sql_feedback_receiver))
    {
      $owners_email[] = $owners['email'];
    }
    $to = implode(", ", $owners_email);
  }

      $subject = 'TRAPFLIX Feedback Notification';
      $specific_email = $to;
      $message =  "
                  A feedback has been submitted by: ".$person." <br/><br/>

                  The feedback message is: <br/><br/>
                  ".$user_feedback_message."

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
