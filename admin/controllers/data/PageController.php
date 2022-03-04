<?php
session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{


  if(isset($_SESSION['email']) == false)
    {
      exit("unauthorised");
    }

    $page_id = mysqli_real_escape_string($dbc,strip_tags($_POST['page_id']));
    $page_name = mysqli_real_escape_string($dbc,strip_tags($_POST['page_name']));
    $user_id = $_SESSION['email'];
    $user_type = 'testuser';

    if(isset($_SESSION['test_user']) == true)
    {
      $sql_insert = mysqli_query($dbc,"INSERT INTO page_requests
                                            (page_id,page_name,requested_by,user_type)
                                            VALUES
                                            ('".$page_id."','".$page_name."','".$user_id."','".$user_typer."')"
                                  );
    }
    else
    {
      $sql_insert = mysqli_query($dbc,"INSERT INTO page_requests
                                            (page_id,page_name,requested_by)
                                            VALUES
                                            ('".$page_id."','".$page_name."','".$user_id."')"
                                  );
    }





}

?>
