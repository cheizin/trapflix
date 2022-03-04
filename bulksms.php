SAMPLE PHP CODE &  STRUCTURE
<?php
// Configurations
$username=’your_username’;
$password=’your_password’;
$client_id=’your_client_id’;
$client_secret=’your_client_secret’;
$ch = curl_init();
ini_set(‘display_errors’, 1);
//Login Procedure
//Check login
curl_setopt($ch, CURLOPT_URL, ‘https://my.jisort.com/registration/login/’);
curl_setopt($ch, CURLOPT_USERAGENT,’Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36′);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, “username=$username&password=$password“);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, ‘cookie’); //could be empty, but cause problems on some hosts
curl_setopt($ch, CURLOPT_COOKIEFILE, ‘cookie’); //could be empty, but cause problems on some hosts
$answer = curl_exec($ch);
if (curl_error($ch)) {
echo curl_error($ch);
}
//API Request Authentication
//request token
curl_setopt($ch, CURLOPT_URL, ‘https://my.jisort.com/o/token/’);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, “username=$username&password=$password&grant_type=password&client_id=$client_id&client_secret=$client_secret“);
$answer = curl_exec($ch);
$data = json_decode($answer);
if (curl_error($ch)) {
echo curl_error($ch);
}
// Sending SMS
//another request preserving the session
//send sms
curl_setopt($ch, CURLOPT_URL, ‘https://my.jisort.com/messenger/outbox/’);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Authorization: Bearer ‘.$data->access_token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, “recipients=urlencode(“+254701234567,+254701234568“)&message=”message””);
$answer = curl_exec($ch);
if (curl_error($ch)) {
echo curl_error($ch);
}
// Generate Delivery Report
//another request preserving the session
curl_setopt($ch, CURLOPT_URL, ‘https://my.jisort.com/messenger/outbox/’);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Authorization: Bearer ‘.$data->access_token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, “delivery=”.true.””);
$answer = curl_exec($ch);
if (curl_error($ch)) {
echo curl_error($ch);
}
//Check Balance
//another request preserving the session
curl_setopt($ch, CURLOPT_URL, ‘https://my.jisort.com/registration/organization/’);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Authorization: Bearer ‘.$data->access_token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$answer = curl_exec($ch);
if (curl_error($ch)) {
echo curl_error($ch);
// sms_units is the SMS account balance variable
}
 