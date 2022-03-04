<?php
include("../controllers/setup/connect.php");

    $contact = $_SESSION['mobile'];
include_once('OAuth.php');
$consumer_key="PBgcnmiOMmgFXngZam9Mz9a+CAjVFEOg";//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
$consumer_secret="ueHynAkLAJx1KZKwvMzjMT4Swys=";// Use the secret from your test
                   //account on demo.pesapal.com. When you are ready to go live make sure you
                   //change the secret to the live account registered on www.pesapal.com!
$statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';//change to
                   //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!

// Parameters sent to you by PesaPal IPN
//$pesapalNotification=$_GET['pesapal_notification_type'];
$pesapalNotification = 'CHANGE';
$pesapalTrackingId=$_GET['pesapal_transaction_tracking_id'];
$pesapal_merchant_reference=$_GET['pesapal_merchant_reference'];
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

if($pesapalNotification=="CHANGE" && $pesapalTrackingId!='')
{
   $token = $params = NULL;
   $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

   //get transaction status
   $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
   $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
   $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
   $request_status->sign_request($signature_method, $consumer, $token);

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $request_status);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True')
   {
      $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
      curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
      curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
      curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
   }

   $response = curl_exec($ch);

   $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
   $raw_header  = substr($response, 0, $header_size - 4);
   $headerArray = explode("\r\n\r\n", $raw_header);
   $header      = $headerArray[count($headerArray) - 1];

   //transaction status
   $elements = preg_split("/=/",substr($response, $header_size));
   $status = $elements[1];


   curl_close ($ch);

   //update table with transaction status

   $sql = mysqli_query($dbc,"UPDATE users_subscriptions SET
                                     pesapal_transaction_tracking_id='$pesapalTrackingId',
                                     pesapal_notification_type='$status'

                              WHERE reference='$pesapal_merchant_reference'
                                     ");

   if($sql)
   {
       if ($status == "PENDING")
       {

         $messageContent= 'We%20are%20processing%20your%20trapflix%20payment%20We%20will%20notify%20you%20once%20the%20transaction%20has%20completed';
        
        //start sending verification sms
        $curl = curl_init(); 
        curl_setopt_array($curl, array(   CURLOPT_URL 
        => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message={$messageContent}",  
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
           ?>
           <p style="color:orange;">Payment Status : <b>PENDING</b></p>
            <a href='https://www.trapflix.com/payments/PaymentsIPN.php?pesapal_transaction_tracking_id=<?php echo $pesapalTrackingId ;?>&pesapal_merchant_reference=<?php echo $pesapal_merchant_reference ;?>'>Update Payment</a>
           <?php
       }
       else if($status == 'COMPLETED')
       {
          echo $success = 'Thank you for subscribing to Trapflix. Enjoy the shows';
          
           $messageContent2= 'Thank%20you%20for%20subscribing%20to%20trapflix%20TV%20Enjoy%20the%20shows';
          
          //start sending verification sms
          $curl = curl_init(); 
          curl_setopt_array($curl, array(   CURLOPT_URL 
          => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message={$messageContent2}",  
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
                ?>
          <script>
            window.location.href = "https://trapflix.com/home.php";
            </script>

            <?php

       }

      /*$resp="pesapal_notification_type=$status&pesapal_transaction_tracking_id=$pesapalTrackingId&pesapal_merchant_reference=$pesapal_merchant_reference";
      ob_start();
      echo $resp;
      ob_flush();

      */


      exit;
   }
   else
   {
      echo mysqli_error($dbc);
   }

}
?>
