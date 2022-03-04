<?php
include_once('oauth.php');
require_once('../database/connect.php');
$consumer_key="zYE7zR4ACoR5rxF3Mbi0kT0E6g3mbpYi";//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
$consumer_secret="OgBGmHKL/WJiH6A/9lLm3v6gxYQ=";// Use the secret from your test
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

   $sql = mysqli_query($dbc,"UPDATE donations SET
                                     pesapal_transaction_tracking_id='$pesapalTrackingId',
                                     pesapal_notification_type='$status',
                                     pesapal_merchant_reference='$pesapal_merchant_reference'

                              WHERE reference='$pesapal_merchant_reference'
                                     ");

   if($sql)
   {
      $resp="pesapal_notification_type=$status&pesapal_transaction_tracking_id=$pesapalTrackingId&pesapal_merchant_reference=$pesapal_merchant_reference";
      ob_start();
      echo $resp;
      ob_flush();

      $success = 'Thank You for Donating';

      header('Location:http://localhost/pesapal?success='.$success.'');
      exit;
   }
   else 
   {
      echo mysqli_error($dbc);
   }

}
?>