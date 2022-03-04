<?php
session_start();
include("../controllers/setup/connect.php");
include_once('OAuth.php');

$package_id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

$package_amount = mysqli_fetch_array(mysqli_query($dbc,"SELECT amount FROM subscription_packages WHERE id='".$package_id."'"));
$package_amount = $package_amount['amount'];
$duration = date('t'); //days
$user_id = $_SESSION['id'];

?>

<a href="https://trapflix.com/payments/securepayment.php">Back</a>




<?php



//pesapal params
$token = $params = NULL;

/*
PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and 
when you are ready to go live change to https://www.pesapal.com.
*/
$consumer_key = 'PBgcnmiOMmgFXngZam9Mz9a+CAjVFEOg';//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
$consumer_secret = 'ueHynAkLAJx1KZKwvMzjMT4Swys=';// Use the secret from your test
                   //account on demo.pesapal.com. When you are ready to go live make sure you 
                   //change the secret to the live account registered on www.pesapal.com!
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
$iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';//change to      
                   // when you are ready to go live!


        $desc = 'Trapflix Payments';
        $type = 'MERCHANT';
        $reference = microtime(true);
        $first_name = /*mysqli_real_escape_string($dbc,strip_tags($_POST['first_name']));*/  $_SESSION['name'];
      //  $last_name = /*mysqli_real_escape_string($dbc,strip_tags($_POST['last_name']));*/  $_SESSION['name'];
        $amount = $package_amount;
        $amount = number_format($amount, 2);
        $email = $_SESSION['email'];
        $phonenumber = /*mysqli_real_escape_string($dbc,strip_tags($_POST['phonenumber']));*/ '';
        $currency = "USD";


$callback_url = 'https://www.trapflix.com/payments/PaymentsIPN.php'; //redirect url, the page that will handle the response from pesapal.

$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Currency=\"".$currency."\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
$post_xml = htmlentities($post_xml);

$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

//post transaction to pesapal
$iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
$iframe_src->set_parameter("oauth_callback", $callback_url);
$iframe_src->set_parameter("pesapal_request_data", $post_xml);
$iframe_src->sign_request($signature_method, $consumer, $token);


//save the form details to the database

//check for duplicate reference number
$sql = mysqli_query($dbc,"SELECT reference FROM users_subscriptions WHERE reference='".$reference."'") or die (mysqli_error($dbc));
$count = mysqli_num_rows($sql);
if($count > 0)
{
    //do not insert
}
else 
{
        $sql = mysqli_query($dbc,"INSERT INTO users_subscriptions 
                            (`user_id`, `description`, `type`, `reference`, `first_name`, `last_name`, `amount`, `email`, `phonenumber`, `subscription_type`, `duration`)
                           VALUES 
                           ('".$user_id."','".$desc."','".$type."','".$reference."','".$first_name."','user','".$amount."','".$email."','".$phonenumber."',
                            '".$package_id."','".$duration."')
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
            echo mysqli_error($dbc);
        }

}








