<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$contact = '+254727014069,+245710257750';

$verification_code = "test";

$jisort_url = "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message=$verification_code";

$movetechurl= "https://sms.movesms.co.ke/api/compose?username=Trapflix&api_key=labMWLBbsHBHsTx4zVmJWa2Hlj3y6IMakmlmv9GGHviQD5edKm&sender=SMARTLINK&to=+254727014069,+245710257750&message=Hey&msgtype=5&dlr=0";
               $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => $movetechurl,  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 


?>

