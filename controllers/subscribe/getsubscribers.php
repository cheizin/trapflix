<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    
    $channel_name = mysqli_real_escape_string($dbc,strip_tags($_POST['channel_name']));

   // $youtube_vid = mysqli_real_escape_string($dbc,strip_tags($_POST['youtube_vid']));
    //$comment_name = mysqli_real_escape_string($dbc,strip_tags($_POST['comment_name']));
    
    
    $sql = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM channel_subscription WHERE channel_name='".$channel_name."' && subscriber_email='".$email."' "));
    
    if($sql > 0)
    {
        //subscribed
        
        ?>
        <form id="subscribe-form" class="mt-4">
        	<input type="hidden" value="remove-subscribe" name="remove-subscribe">
        	<input type="hidden" name="channel_name" value="<?php echo $channel_name ;?>">
        	<input type="hidden" name="email" value="<?php echo $_SESSION['email'];?>">
        	<div class="hover-buttons subscription-status-button">
        		<button type="submit" class="btn btn-hover btn btn-danger btn-block font-weight-bold submitting" title="Click Here to Unsubscribe to this Channel"><i class="fa fa-play mr-1" aria-hidden="true"></i> Unsubscribe </button>
        	</div>
        </form>
        
        <?php
    }
    else 
    {
        //not subscribed
        ?>
        <form id="subscribe-form" class="mt-4">
	<input type="hidden" value="add-subscribe" name="add-subscribe">
	<input type="hidden" name="channel_name" value="<?php echo $channel_name ;?>">
    <input type="hidden" name="email" value="<?php echo $_SESSION['email'];?>">
	<div class="hover-buttons subscription-status-button">
		<button type="submit" class="btn btn-hover btn btn-primary btn-block font-weight-bold submitting" title="Click Here to Subscribe to this Channel"><i class="fa fa-play mr-1" aria-hidden="true"></i> Subscribe </button>
	</div>
</form>

<?php
    }



}

//END OF POST REQUEST


?>
