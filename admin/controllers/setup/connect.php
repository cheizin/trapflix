<?php
require_once("constants.php");

// Turn off error reporting
error_reporting(0);
ini_set('session.gc_maxlifetime', 86400); // 1 day to session expiry

$dbc = mysqli_connect(SERVER_NAME,DB_USER,DB_PASS,DB_NAME) or die ("Failed to Connect!");
date_default_timezone_set('Africa/Nairobi');


$current_quarter_and_year = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM years ORDER BY id DESC LIMIT 1"));

$last_quarter_and_year = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM years ORDER BY id DESC LIMIT 1,1"));

$specific_quarter_date = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM years ORDER by id DESC LIMIT 1"));
$specific_year = $specific_quarter_date['specific_year'];
$specific_month = $specific_quarter_date['specific_month'];
$specific_day = $specific_quarter_date['specific_day'];

$todays_date = date("Y-m-d");

$deadline_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM deadline ORDER BY id DESC LIMIT 1"));

$deadline = $deadline_row['deadline'];
$quarter = $deadline_row['quarter'];

preg_match('#\((.*?)\)#', $quarter, $match);
$quarter =  $match[1];

$date1=date_create($deadline);
$date2=date_create($todays_date);
$diff=date_diff($date2,$date1);

$days_remaining =  $diff->format("%a");

$days_remaining_with_prefix = $diff->format("%R%a");

$months_remaining = '';

//$deadline_message = $days_remaining . " days remaining to make " . $quarter . " updates";

if($days_remaining_with_prefix == 1)
{
  $deadline_message = "1 day remaining to make " . $quarter . " updates";
}

if($days_remaining_with_prefix == 0)
{
  $deadline_message = "Your " . $quarter . " submission is due TODAY";
}
if($days_remaining_with_prefix == -1)
{
  $deadline_message =  $quarter . " submission was due yesterday";
}
/*
if($days_remaining_with_prefix < 0 && $days_remaining_with_prefix != -1)
{
  $deadline_message = $quarter . " submission was due " . $days_remaining . " days ago";
}
*/


$deadline_message = "";

//echo $diff->format("%R%a days");


//ensuring the page is always https
/*
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
}
//this ensures that all contents are loaded via https
//The max-age value is in seconds. Use 31536000 for 12 months or 63072000 for 24 months.
header("Strict-Transport-Security:max-age=63072000");

//Protection against xss attacks
header("X-XSS-Protection: 1");
*/

?>
