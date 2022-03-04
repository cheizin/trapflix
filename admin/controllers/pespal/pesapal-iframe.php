<?php
include_once('OAuth.php');
require_once('../database/connect.php');

//pesapal params
$token = $params = NULL;

/*
PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and 
when you are ready to go live change to https://www.pesapal.com.
*/
$consumer_key = 'zYE7zR4ACoR5rxF3Mbi0kT0E6g3mbpYi';//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
$consumer_secret = 'OgBGmHKL/WJiH6A/9lLm3v6gxYQ=';// Use the secret from your test
                   //account on demo.pesapal.com. When you are ready to go live make sure you 
                   //change the secret to the live account registered on www.pesapal.com!
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
$iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';//change to      
                   // when you are ready to go live!



if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset($_POST['submit_donation_form']))
    {
        $desc = 'Donation';
        $type = 'MERCHANT';
        $reference = microtime(true);;
        $first_name = mysqli_real_escape_string($dbc,strip_tags($_POST['first_name']));
        $last_name = mysqli_real_escape_string($dbc,strip_tags($_POST['last_name']));
        $amount = mysqli_real_escape_string($dbc,strip_tags($_POST['amount']));
        $amount = number_format($amount, 2);
        $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
        $phonenumber = mysqli_real_escape_string($dbc,strip_tags($_POST['phonenumber']));;
    }
}


$callback_url = 'https://19fc1418a2bf.ngrok.io/pesapal/controllers/pesapal/pesapal-ipn-listener.php'; //redirect url, the page that will handle the response from pesapal.

$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
$post_xml = htmlentities($post_xml);

$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

//post transaction to pesapal
$iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
$iframe_src->set_parameter("oauth_callback", $callback_url);
$iframe_src->set_parameter("pesapal_request_data", $post_xml);
$iframe_src->sign_request($signature_method, $consumer, $token);


//save the form details to the database

//check for duplicate reference number
$sql = mysqli_query($dbc,"SELECT reference FROM donations WHERE reference='".$reference."'") or die (mysqli_error($dbc));
$count = mysqli_num_rows($sql);
if($count > 0)
{
    //do not insert
}
else 
{
    //insert
    $sql = mysqli_query($dbc,"INSERT INTO donations 
                            (description, type,reference, first_name,last_name,amount,email,phonenumber)
                           VALUES 
                           ('".$desc."','".$type."','".$reference."','".$first_name."','".$last_name."','".$amount."','".$email."','".$phonenumber."')
                    ");

    if($sql)
    {
        ?>
        <!-- only display pesapal - iframe and pass iframe_src when the form is saved-->
        <iframe src="<?php echo $iframe_src;?>" width="100%" height="700px"  scrolling="no" frameBorder="0">
            <p>Browser unable to load iFrame</p>
        </iframe>
        <?php
    }
    else 
    {
        echo "An Error Occured, Please retry";
    }
}



