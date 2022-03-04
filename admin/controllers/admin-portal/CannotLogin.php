<?php
include("../../controllers/setup/connect.php");
require_once("../../assets/libs/BrowserDetection/lib/BrowserDetection.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $user = mysqli_real_escape_string($dbc,strip_tags($_POST['user']));

  $ip_address = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDE??D_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
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

      $browser = new Wolfcast\BrowserDetection();
      $browser_name = $browser->getName();
      $platform_name = $browser->getPlatformVersion();


      $subject = 'PPRMIS Login Request Notification';
      $specific_email = $to;
      $message =  "A Login Request has been sent by ".$user.", IP Address ".$ip_address.", using ".$browser_name." on ".$platform_name." <br/><br/>

                  Please review the request <br/><br/>


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
