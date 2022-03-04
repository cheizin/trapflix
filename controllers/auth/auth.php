<?php
//Include Google Client Library for PHP autoload file
require_once 'assets/libs/google-api-php-client-2.2.1/vendor/autoload.php';
require_once 'controllers/setup/connect.php';

//Make object of Google API Client for call Google API
//session_start();
$client = new Google_Client();

$clientID = '810238561421-giau2dhed9fhbhp2vpfmgvk5oulti36j.apps.googleusercontent.com';
$clientSecret = 'j0wyfP1XaKibsteVAK0w4k0q';
$redirectURL = 'https://career.panoramaengineering.com/';


//temporarily disable ssl
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
//Set the OAuth 2.0 Client ID
$client->setClientId($clientID);

//Set the OAuth 2.0 Client Secret key
$client->setClientSecret($clientSecret);

//Set the OAuth 2.0 Redirect URI
$client->setRedirectUri($redirectURL);

$client->setApplicationName("HRMIS");

//
$client->addScope('email');

$client->addScope('profile');

//Logout
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
    //$client->revokeToken();
    // Destroy entire session data
    session_destroy();
  header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL)); //redirect user back to page
}

if(isset($_GET['code']))
{

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if(isset($token['access_token']))
    {
        $client->setAccessToken($token['access_token']);
                //get profile information
        $google_oauth = new Google_Service_Oauth2($client);

        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->email;
        $name = $google_account_info->name;

        $_SESSION['fName'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['access_token'] = $client->getAccessToken();

        //check if the user exists in the database, if not, add the user
        $sql = mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Email='".$_SESSION['email']."'");
        if(mysqli_num_rows($sql) < 1)
        {
          //add user
          $name = $_SESSION['fName'] ;
          $email = $_SESSION['email'];
          $service = 'Google';
          $insert = mysqli_query($dbc,"INSERT INTO staff_users (fName,Email,auth_service) VALUES
                                              ('".$name."',
                                              '".$email."',
                                              '".$service."')"
                                  );

          if($insert)
          {
              echo "success";
          }
          else
          {
              echo "failed";
          }
        }

        $get_user_type = mysqli_fetch_array(mysqli_query($dbc,"SELECT id,access_level FROM staff_users WHERE Email='".$_SESSION['email']."'"));
        $user_type = $get_user_type['access_level'];
        $user_id = $get_user_type['id'];

        $_SESSION['access_level'] = $user_type;
        $_SESSION['id'] = $user_id;
        header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
    }
    else
    {
        //access token not set
        $AuthenticationURL = $client->createAuthUrl();
        $_SESSION['authetication_url'] = $AuthenticationURL;
    }

}
else
{
    //user not authenticated
    $AuthenticationURL = $client->createAuthUrl();
    $_SESSION['authetication_url'] = $AuthenticationURL;
}


?>
