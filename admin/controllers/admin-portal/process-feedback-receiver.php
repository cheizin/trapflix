<?php
include("../../controllers/setup/connect.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if($_SESSION['access_level']!='admin')
    {
        exit("unauthorized");
    }
    //collect form fields

    if(isset($_POST['add_feedback_user']))
    {
      $email = mysqli_real_escape_string($dbc,strip_tags($_POST['feedback_receiver']));

      $created_by = $_SESSION['name'];
      $date_recorded = date("m/d/Y");
      $time_recorded = date("h:i:sa");



      //check for duplicate programme name before inserting values to database
      $sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM feedback_receiver WHERE email='".$email."'"));
      if($sql_check > 0)
          {
          exit("duplicate");
          }
      $sql_statement = "INSERT INTO feedback_receiver

                          (email, added_by, date_added, time_added)

                        VALUES
                            ('".$email."', '".$created_by."', '".$date_recorded."','".$time_recorded."')
                              ";

      //check if query runs

      $action_reference = "Added a feedback receiver " . $email;
      $action_name = "New Feedback Receiver";
      $action_icon = "far fa-user-plus";
      $page_id = "user-feedback-link";
      $time_recorded = date('Y/m/d H:i:s');

      $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                      (email,action_name,action_reference,action_icon,page_id,time_recorded)
                          VALUES
                  ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                          '".$action_icon."','".$page_id."','".$time_recorded."')"
                   );

      if($insert_query = mysqli_query($dbc,$sql_statement) && $sql_log)
      {
          exit ("success");
      }
      else
      {
          exit ("failed");
      }
    }
    else
    {
        //delete feedback receiver
          mysqli_query($dbc,"START TRANSACTION");

          $delete = mysqli_query($dbc,"DELETE FROM feedback_receiver WHERE email='".$_POST['sid']."'");

          //log the action
          $action_reference = "Deleted a Feedback Receiver with the id ".$_POST['sid'];
          $action_name = "Feedback Receiver Deletion";
          $action_icon = "far fa-trash text-danger";
          $page_id = "user-feedback-link";
          $time_recorded = date('Y/m/d H:i:s');

          $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                                (email,action_name,action_reference,action_icon,page_id,time_recorded)
                                VALUES
                              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                              '".$action_icon."','".$page_id."','".$time_recorded."')"
                    );

          if($delete && $sql_log)
          {
            echo "success";
            mysqli_query($dbc,"COMMIT");
          }
          else
          {
            echo mysqli_error($dbc);
            mysqli_query($dbc,"ROLLBACK");
          }




    }

}

?>
