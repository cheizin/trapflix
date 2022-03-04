<?php
require_once('../setup/connect.php');
require_once('../middleware/RequestIsPost.php');
require_once('../middleware/UserIsAuthenticated.php');

  //if its stock creation
  if(isset($_POST['add-channel']))
  {
    $category_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category_name']));

      $order_id = mysqli_real_escape_string($dbc,strip_tags($_POST['order_id']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $sql_insert= mysqli_query($dbc,"INSERT INTO main_categories
            (user_id,order_id, category_name,recorded_by)
                                       VALUES
                                       ('".$_SESSION['id']."','".$order_id."','".$category_name."','".$recorded_by."') ") or die (mysqli_error($dbc));


  //log the action
  $action_reference = "Added a Channel " . $category_name;
  $action_name = "Stock Category Creation";
  $action_icon = "far fa-project-diagram text-success";
  $page_id = "stock-management-link";
  $time_recorded = date('Y/m/d H:i:s');

  $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                  (email,action_name,action_reference,action_icon,page_id,time_recorded)
                      VALUES
              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                      '".$action_icon."','".$page_id."','".$time_recorded."')"
               );

    if(mysqli_commit($dbc))
    {
        $phone = mysqli_fetch_array(mysqli_query($dbc,"SELECT mobile FROM users WHERE access_level='admin' ORDER BY id DESC LIMIT 1"));
        $phone= $phone['mobile'];
        
       /* $message_admin = 'A&ensp;Channel_has_been_Created_on_Trapflix';
        
        
        
        start send sms notification to channel admin
              $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$phone}&message={$message_admin}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
        
        end send sms notification to channel admin
        */
        
        //start send sms notification to channel creator
        $channel_name = str_replace(' ', '%20', $category_name);
        $contact = $_SESSION['mobile'];
        $message= 'A%20Channel%20'.$channel_name.'%20has%20been%20Created%20on%20Trapflix';
              $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message={$message}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
        
        //end send sms notification to channel creator
        exit("success");
    }

  else
  {
    mysqli_rollback($dbc);
    exit("failed");
  }

    }



?>
