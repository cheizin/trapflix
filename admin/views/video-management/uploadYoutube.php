<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
  $token = bin2hex(random_bytes(20));

 //$token = rand(20);
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/

if(isset($_SESSION['access_level']))
{
    if($_SESSION['access_level'] == 'admin')
    {
        require_once('uploadYoutubeAdmin.php');
    }
    
    else if ($_SESSION['access_level'] == 'standard')
    {
        require_once('uploadYoutubeStandard.php');
    }
}
?>

