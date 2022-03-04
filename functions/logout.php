<?php
session_start();
require_once('../includes/connect.php');
$time_signed_out  = date('Y/m/d H:i:s');
$sql = "UPDATE sign_in_logs
            SET
            time_signed_out='".$time_signed_out."'
            WHERE email='".$_SESSION['email']."'
            && time_signed_in='".$_SESSION['time_signed_in']."'";

$query = mysqli_query($dbc,$sql);
if($query)
{

    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
      if(session_destroy())
      {
                //update logout time

        $cur_dir = explode('\\', getcwd());
        $current_directory =  $cur_dir[count($cur_dir)-2];
        if($current_directory == 'pages')
        {
          exit(header('Location: index.php'));
        }
        else
        {
          exit(header('Location: index.php'));
        }
      }
      else
      {
        echo("Try again");
      }

}
else
{
    echo mysqli_error($dbc);
}


?>
