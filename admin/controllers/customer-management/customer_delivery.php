<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{


  //if its project risk creation
  if(isset($_POST['add-delivery-product']))
  {


      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      //$delivery_approver = mysqli_real_escape_string($dbc,strip_tags($_POST['delivery_approver']));
$product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
      $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
      $unit_price = mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
        $qtt = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
      $total_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['total_stock']));
      $stock_remaining = mysqli_real_escape_string($dbc,strip_tags($_POST['stock_remaining']));

      $payment_type = mysqli_real_escape_string($dbc,strip_tags($_POST['payment_type']));
      $invoice_issued_id = mysqli_real_escape_string($dbc,strip_tags($_POST['invoice_issued_id']));
    $delivery_approver = mysqli_real_escape_string($dbc,strip_tags($_POST['delivery_approver']));


      $date_recorded = date('d-M-yy');

    $recorded_by = $_SESSION['name'];


      $sql_insert_delivery = mysqli_query($dbc,"INSERT INTO customer_end_delivery
                            (end_product_ref, unit_price, qtt, total, payment_type, invoice_issued_id, stock_remaining, document, delivery_note_doc,purchase_order_doc,date_recorded, recorded_by)
                    VALUES
                            ('".$id."', '".$unit_price."','".$qtt."','".$total_stock."', '".$payment_type."','".$invoice_issued_id."','".$stock_remaining."', '".$uploadedFile."','".$additional_file."', '".$additional_file2."','".$date_recorded."', '".$recorded_by."')
                   ") or die (mysqli_error($dbc));


                           //start of pdo FOR Senior advisors
                           foreach ($_POST['delivery_approver'] as $selapprover)
                           {

                             $insert_approver = mysqli_query($dbc,"INSERT INTO delivery_approvers (delivery_approver,product_id, date_recorded, recorded_by)
                             VALUES ('".$selapprover."','".$id."','".$date_recorded."','".$recorded_by."')") or die (mysqli_error($dbc));


                           }


    //log the action
    $action_reference = "Delivered Product with name".$product_name;
    $action_name = "End product Delivery";
    $action_icon = "fad fa-pennant text-info";
    $page_id = "product-delivery-ta";
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

  //end of add project risk

  //start of monitor project risk
  else if(isset($_POST['returns-delivery-product']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    //$delivery_approver = mysqli_real_escape_string($dbc,strip_tags($_POST['delivery_approver']));
$product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $unit_price = mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
      $qtt = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
      $qtt2 = mysqli_real_escape_string($dbc,strip_tags($_POST['qtt2']));
    $total_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['total_stock']));
    $stock_remaining = mysqli_real_escape_string($dbc,strip_tags($_POST['stock_remaining']));
      $reasons = mysqli_real_escape_string($dbc,strip_tags($_POST['reasons']));

  $delivery_approver = mysqli_real_escape_string($dbc,strip_tags($_POST['delivery_approver']));


    $date_recorded = date('d-M-yy');

  $recorded_by = $_SESSION['name'];


      /* set autocommit to off */



            $sql_insert_delivery = mysqli_query($dbc,"INSERT INTO customer_end_returns
                                  (end_product_ref, unit_price, qtt, total, stock_remaining, reasons, date_recorded, recorded_by)
                          VALUES
                                  ('".$id."', '".$unit_price."','".$qtt."','".$total_stock."', '".$stock_remaining."', '".$reasons."','".$date_recorded."', '".$recorded_by."')
                         ") or die (mysqli_error($dbc));


                              $sql_statement2 = mysqli_query($dbc,"UPDATE customer_end_delivery SET
                                                  qtt='".$qtt."', stock_remaining = '".$stock_remaining."',
                                                  total = '".$total_stock."'
                                                  WHERE id ='".$id."'") or die (mysqli_error($dbc));
//log the action
  $action_reference = "Product ".$product_name. " was Returned to production";
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
