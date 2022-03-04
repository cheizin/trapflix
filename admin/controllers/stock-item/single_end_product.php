<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{


  //if its project risk creation
  if(isset($_POST['add-single-end-product']))
  {
    $item_name = mysqli_real_escape_string($dbc,strip_tags($_POST['item_name']));
    $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
    $qtt = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
    $total_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['total_stock']));
    $stock_remaining = mysqli_real_escape_string($dbc,strip_tags($_POST['stock_remaining']));
    $unit_price = mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
    $total_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['total_stock']));


    $date_recorded = date('d-M-yy');

  $recorded_by = $_SESSION['name'];

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);



      $sql_insert_issue = mysqli_query($dbc,"INSERT INTO single_product
                            (end_product_ref,product_name, unit_price, qtt, total, stock_remaining,date_recorded, recorded_by)
                    VALUES
                            ('".$reference_no."', '".$product_name."','".$unit_price."','".$qtt."', '".$total_stock."','".$stock_remaining."','".$date_recorded."', '".$recorded_by."')
                   ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Stock ".$item_name. " was Requested For the End Product " .$product_name;
    $action_name = "Stock Item Request";
    $action_icon = "fad fa-pennant text-info";
    $page_id = "project-issue-logs-tab";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                    (email,action_name,action_reference,action_icon,page_id,time_recorded)
                        VALUES
                ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
                 );

                if( $stock_remaining < 0)
                 {
                     exit("below");
                 }
      else if(mysqli_commit($dbc))
      {
          exit("success");
      }

    else
    {
      mysqli_rollback($dbc);
      exit("failed");
    }



  }
  //end of add project risk

  //start of monitor project risk
  else if(isset($_POST['add-returns-end-product']))
  {
    $item_name = mysqli_real_escape_string($dbc,strip_tags($_POST['item_name']));
    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $reasons = mysqli_real_escape_string($dbc,strip_tags($_POST['reasons']));
    $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
    $qtt = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
    $qtt2 = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt2']));
    $total_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['total_stock']));
    $stock_remaining = mysqli_real_escape_string($dbc,strip_tags($_POST['stock_remaining']));
    $unit_price = mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
    $total_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['total_stock']));


    $date_recorded = date('d-M-yy');

  $recorded_by = $_SESSION['name'];

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      $sql_insert_issue = mysqli_query($dbc,"INSERT INTO stocks_returns
                            (end_product_ref,product_name, unit_price, qtt, total, stock_remaining, reason, date_recorded, recorded_by)
                    VALUES
                            ('".$reference_no."', '".$product_name."','".$unit_price."','".$qtt."', '".$total_stock."','".$stock_remaining."',
                              '".$reasons."','".$date_recorded."', '".$recorded_by."')
                   ") or die (mysqli_error($dbc));

                              $sql_statement = mysqli_query($dbc,"UPDATE single_product SET
                                                  qtt='".$qtt."', stock_remaining = '".$stock_remaining."',
                                                  total = '".$total_stock."'

                                                  WHERE id ='".$id."'") or die (mysqli_error($dbc));

//log the action
  $action_reference = "Stock ".$item_name. " was Returned to the store";
    $action_name = "Stock Item Request";
  $action_icon = "fad fa-pennant text-info";
    $page_id = "project-issue-logs-tab";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
      (email,action_name,action_reference,action_icon,page_id,time_recorded)
          VALUES
                                                              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                                                                      '".$action_icon."','".$page_id."','".$time_recorded."')"
                                                               );

                                                               if( $qtt2 < 0)
                                                                {
                                                                    exit("below");
                                                                }
      else if(mysqli_commit($dbc))
      {
          exit("success");
      }

    else
    {
      mysqli_rollback($dbc);
      exit("failed");
    }
  }

  //end of monitor project risk

  //start close project risk

  else if(isset($_POST['close_issue_log']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);


    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['sid']));
    $status = mysqli_real_escape_string($dbc,strip_tags($_POST['status']));

    $change_status = mysqli_query($dbc,"UPDATE pm_issue_logs SET status='$status' WHERE id='".$id."'");

    //log the action
    $action_reference = "Changed the status of the project issue log with the id " . $id. " to ".$status;
    $action_name = "Project Issue Log Status Change";
    $action_icon = "fal fa-exclamation-square text-info";
    $page_id = "project-issue-logs-tab";
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
  //end close project risk






}

//END OF POST REQUEST


?>
