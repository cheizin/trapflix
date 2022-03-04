<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //start of add job seeker
 if(isset($_POST['add-job-seeker']))
{
    $verification_code= rand(1111111,9999999);
    
            $verificationContent=   'Your%20Trapflix%20Verification%20Code%20is%20'. $verification_code;
    $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
  //check if the user exists in the database, if not, add the user
  $sql = mysqli_query($dbc,"SELECT email FROM users WHERE email='".$Email."'");
  if(mysqli_num_rows($sql) < 1)
  {
    //add user
    $access_level = mysqli_real_escape_string($dbc,strip_tags($_POST['standard']));
    $fName = mysqli_real_escape_string($dbc,strip_tags($_POST['fullName']));
      $contact= mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));
        $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
           $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));


    $password = mysqli_real_escape_string($dbc,strip_tags(md5($_POST['password'])));

    $passwordmd5 = md5($password);


    $date_recorded = date('d-M-yy');



    $time_recorded = date('Y/m/d H:i:s');

  /* set autocommit to off */
  mysqli_autocommit($dbc, FALSE);
  
  

    //insert into user verification codes
    
    $sql = mysqli_query($dbc,"INSERT INTO user_verification_codes (email,verification_code) VALUES ('".$Email."','".$verification_code."')");
  $sql_insert= mysqli_query($dbc,"INSERT INTO users
        (name, email,status, mobile,  password)
          VALUES ('".$fName."', '".$Email."','".$access_level."', '".$contact."','".$password."')") or die (mysqli_error($dbc));


            

           if(mysqli_commit($dbc))
           {
               
                $messageContent= 'A%20Channel%20has%20been%20Created%20on%20Trapflix';
               
               //start sending verification sms
               $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message={$verificationContent}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
               
               //end sending verification sms
               
               
               
             exit("success");
           }

           else
           {
           mysqli_rollback($dbc);
           exit("failed");
           }
  }

  else
  {
      exit("duplicate");
  }


}


//start of job recruiter
else if(isset($_POST['order-channel']))
{

    $contact = mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

  /*$sql_insert= mysqli_query($dbc,"INSERT INTO major_channels
        (category_id, ordering)
          VALUES ('".$contact."', '".$id."')") or die (mysqli_error($dbc));
          
          */
          
                          //  $sql_statement = "UPDATE major_channels SET
                                //                order_id = '".$contact."

                                //                WHERE id ='".$id."'";
                                
                                    //insert records to sql
    $sql_insert = "UPDATE major_channels SET

    order_id ='$contact'

    WHERE id ='$id'
    ";

    $insert_project = mysqli_query($dbc,$sql_insert) or die (mysqli_error($dbc));


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
    
    // start order videos order
    
    else if(isset($_POST['order-video']))
{

    $contact = mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));

    $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);


    $sql_insert = "UPDATE videos SET

    order_id ='$contact'

    WHERE token ='$token'
    ";

    $insert_project = mysqli_query($dbc,$sql_insert) or die (mysqli_error($dbc));


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

    // end order videos ordering
    
    // start order popular video
        else if(isset($_POST['order-popular-video-pop']))
{

    $contact = mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);


    $sql_insert = "UPDATE popular_videos SET

    order_id ='$contact'

    WHERE id ='$id'
    ";

    $insert_project = mysqli_query($dbc,$sql_insert) or die (mysqli_error($dbc));


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
    
    // end order popular video




}

//END OF POST REQUEST


?>
