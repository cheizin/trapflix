<?php

// function to generate token
/*
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
*/
//mi natumia hii code hapa ku generate access token ..run tuone..

function generateToken()
{
  $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  $credentials = base64_encode('vbTHENbHTmAA6Or5Q3eGsQHB7yYhSUAA:G3icNDCTrWj2nS5a');
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $curl_response = curl_exec($curl);

  $json_decode = json_decode($curl_response);
  $access_token = $json_decode->access_token;

  return $access_token;
}
// function o register confirmation and validation url
/*
function registerurl()
{
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer'.generateToken())); //setting custom header


    $curl_post_data = array(
      //Fill in the request parameters with valid values
      'ShortCode' => '600730',
      'ResponseType' => 'completed ',
      'ConfirmationURL' => 'https://wisegeneration.co.ke/mpesa/confrimation/',
      'ValidationURL' => 'https://wisegeneration.co.ke/mpesa/validation/'
    );

    $data_string = json_encode($curl_post_data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);

    //echo $curl_response;

    return $curl_response;
}
*/
//change the confirmation and validation url not to use the "mpesa " word..
function registerUrl()
{
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.generateToken()));


    $curl_post_data = array(
        //Fill in the request parameters with valid values
        'ShortCode' => '600730',
        'ResponseType' => 'completed ',
        'ConfirmationURL' => 'https://wisegeneration.co.ke/testApp/confrimation/',
        'ValidationURL' => 'https://wisegeneration.co.ke/testApp/validation/'
    );

    $data_string = json_encode($curl_post_data);


    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);

    return $curl_response;

}

// function to simulate C2B transaction

//function to simulate C2B transaction

function simulateC2B($amount,$phone)
{
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.generateToken())); //setting custom header


    $curl_post_data = array(
            //Fill in the request parameters with valid values
           'ShortCode' => '600730',
           'CommandID' => 'CustomerPayBillOnline',
           'Amount' => $amount,
           'Msisdn' => $phone,
           'BillRefNumber' => 'Pay Cheizin'
    );

    $data_string = json_encode($curl_post_data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);

    return $curl_response;
}

function LipaNaMpesa()
{

  $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.generateToken()));

  $BusinessShortCode = '174379';
  $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; //obtain this from safaricom daraja portal
  $Timestamp = date('YmdHis');
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
  $Amount = 1;
  $PartyA= '254710257750'; //switch to your phone number
  $PartyB = $BusinessShortCode;
  $PhoneNumber = $PartyA;
  $CallBackUrl = 'https://wisegeneration.co.ke/testApp/validation/'; //your call back url, same as the ones for validation url
  $AccountReference = 'Wise Generation';
  $TransactionDescription = 'Peters Payment';


  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackUrl,
    'AccountReference' => $AccountReference,
    'TransactionDesc' =>  $TransactionDescription
  );

  $data_string = json_encode($curl_post_data);

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

  $curl_response = curl_exec($curl);
  print_r($curl_response);

  return $curl_response;
}
?>
