<?php
require_once('../setup/connect.php');
require_once('../middleware/RequestIsPost.php');
require_once('../middleware/UserIsAuthenticated.php');

  //if its stock creation
  if(isset($_POST['add-category']))
  {
    $category_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category_name']));

      $order_id = mysqli_real_escape_string($dbc,strip_tags($_POST['order_id']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $sql_insert= mysqli_query($dbc,"INSERT INTO feeds_categories
            (user_id,order_id, category_name,recorded_by)
                                       VALUES
                                       ('".$_SESSION['id']."','".$order_id."','".$category_name."','".$recorded_by."') ") or die (mysqli_error($dbc));


  //log the action
  $action_reference = "Added a Channel " . $category_name;
  $action_name = "Stock Category Creation";
  $action_icon = "far fa-project-diagram text-success";
  $page_id = "stock-management-link";
  $time_recorded = date('Y/m/d H:i:s');

  $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                  (email,action_name,action_reference,action_icon,page_id,time_recorded)
                      VALUES
              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                      '".$action_icon."','".$page_id."','".$time_recorded."')"
               );

    if(mysqli_commit($dbc))
    {
        exit("success");
    }

  else
  {
    mysqli_rollback($dbc);
    exit("failed");
  }

    }



?>
