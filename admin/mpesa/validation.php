<?php 
header("Content-Type:application/json"); 
if (!isset($_GET["token"]))
{
echo "Technical error";
exit();
}
if ($_GET["token"]!='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919')
{
echo "Invalid authorization";
exit();
}
/* 
here you need to parse the json format 
and do your business logic e.g. 
you can use the Bill Reference number 
or mobile phone of a customer 
to search for a matching record on your database. 
*/ 
/* 
Reject an Mpesa transaction 
by replying with the below code 
*/ 
echo '{"ResultCode":1, "ResultDesc":"Failed", "ThirdPartyTransID": 0}'; 
/* 
Accept an Mpesa transaction 
by replying with the below code 
*/ 
echo '{"ResultCode":0, "ResultDesc":"Success", "ThirdPartyTransID": 0}';
 
?>