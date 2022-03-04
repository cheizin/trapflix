<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
 if(isset($_POST['add-subscribe']))
{
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    
    $channel_name = mysqli_real_escape_string($dbc,strip_tags($_POST['channel_name']));

   // $youtube_vid = mysqli_real_escape_string($dbc,strip_tags($_POST['youtube_vid']));
    //$comment_name = mysqli_real_escape_string($dbc,strip_tags($_POST['comment_name']));


  /* set autocommit to off */
  mysqli_autocommit($dbc, FALSE);

  $sql_insert= mysqli_query($dbc,"INSERT INTO channel_subscription
        (channel_name, subscriber_email)
          VALUES ('".$channel_name."', '".$email."')") or die (mysqli_error($dbc));


           if(mysqli_commit($dbc))
           {
             exit("success");
           }

           else
           {
           mysqli_rollback($dbc);
           exit("failed");
           }

}

 if(isset($_POST['remove-subscribe']))
{
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    
    $channel_name = mysqli_real_escape_string($dbc,strip_tags($_POST['channel_name']));
    
    $sql = mysqli_query($dbc,"DELETE FROM channel_subscription WHERE channel_name='".$channel_name."' && subscriber_email='".$email."'");
    
    if($sql)
    {
       exit("success");
    }


}

}

//END OF POST REQUEST


?>
