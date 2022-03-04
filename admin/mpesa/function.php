<?php

// function to generate token
function generateToken()
{
  $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
$credentials = base64_encode('aVp3Ph9q01ZxULipQQ3MSfVehSMgZge7:t4em9IUubt85pCoG');
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$curl_response = curl_exec($curl);
$json_decode = json_decode($curl_response);
$access_token = $json_decode->access_token;
return $access_token;


}

// function o register confirmation and validation url
function registerurl()
{
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer'.generateToken())); //setting custom header


    $curl_post_data = array(
      //Fill in the request parameters with valid values
      'ShortCode' => '600000',
      'ResponseType' => 'completed ',
      'ConfirmationURL' => 'https://panorama.wisegeneration.co.ke/mpesa/confrimation/',
      'ValidationURL' => 'https://panorama.wisegeneration.co.ke/mpesa/validation/'
    );

    $data_string = json_encode($curl_post_data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);

    //echo $curl_response;

   return $curl_response;
}
?>
