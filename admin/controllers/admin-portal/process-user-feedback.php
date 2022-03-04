<?php
include("../../controllers/setup/connect.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //collect form fields
    $user_feedback_message = mysqli_real_escape_string($dbc,strip_tags($_POST['user_feedback_message']));
    if(isset($_SESSION['name']))
    {
      $person = $_SESSION['name'];
    }
    else
    {
      $person = "Anonymous";
    }

    $date_recorded = date("m/d/Y");
    $time_recorded = date("h:i:sa");
    
         $sql_statement = mysqli_query($dbc,"INSERT INTO user_feedback
                                            (feedback_message,feedback_person,date_submitted, time_submitted)
                                        VALUES
                                                ('".$user_feedback_message."','".$person."',
                          '".$date_recorded."', '".$time_recorded."')
                                       ") or die (mysqli_error($dbc));


    //check if query runs

    if($insert_query = mysqli_query($dbc,$sql_statement))
    {
        exit ("failed");
    }
    else
    {
        exit ("success");
    }
}

?>
