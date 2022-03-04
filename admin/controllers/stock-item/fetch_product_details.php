<?php
include("../setup/connect.php");
session_start();
if(!empty($_GET['subscription_type'])) {
  $subscription_type = $_GET["subscription_type"];
	$query ="SELECT * FROM phpc_dstv_subscription WHERE id = '".$subscription_type."'";
	$results = mysqli_query($dbc,$query) or die("failed");
?>
<?php
	foreach($results as $departmental_sub_objective_id) {
?>
	<option selected value="<?php echo $departmental_sub_objective_id['id']; ?>"><?php echo $departmental_sub_objective_id['amount']; ?></option>
<?php


}
}
else
{
    echo "Not available";
}
?>
