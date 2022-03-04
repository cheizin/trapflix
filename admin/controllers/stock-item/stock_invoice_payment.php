<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  //if its milestone creation
  if(isset($_POST['add_stock_invoice_payment']))
  {

    mysqli_autocommit($dbc, FALSE);

      $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
      $supplier_id = mysqli_real_escape_string($dbc,strip_tags($_POST['supplier_id']));
      $payment_type = mysqli_real_escape_string($dbc,strip_tags($_POST['payment_type']));
      $debit = mysqli_real_escape_string($dbc,strip_tags($_POST['debit']));
      $transaction_id = mysqli_real_escape_string($dbc,strip_tags($_POST['transaction_id']));
        $date_recorded = date('d-M-yy');

      $recorded_by = $_SESSION['name'];

      // Upload file
    /*  $uploadDir = '../../views/project-management/documents/';
      $uploadStatus = 1;
      $uploadedFile = '';
      if(!empty($_FILES["file"]["name"])){

          // File path config
          $fileName = basename($_FILES["file"]["name"]);
          $targetFilePath = $uploadDir . $fileName;
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

          // Allow certain file formats
          $allowTypes = array('pdf', 'doc', 'docx');
          if(in_array($fileType, $allowTypes)){
              // Upload file to the server
              if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                  $uploadedFile = $fileName;
              }else{
                  $uploadStatus = 0;
                  exit('error-uploading');
              }
          }else{
              $uploadStatus = 0;
              exit('invalid-file');
          }
      }
      if($uploadStatus == 1)
      {
      */

      $sql_task_update = mysqli_query($dbc,"INSERT INTO invoice_received_payment
                                             (invoice_received_id,supplier_id,transaction_id,payment_type,debit,date_recorded, recorded_by)
                                         VALUES
                                         ('".$id."','".$supplier_id."','".$transaction_id."','".$payment_type."','".$debit."','".$date_recorded."','".$recorded_by."')
                               ") or die (mysqli_error($dbc));

                 //log the action
                 $action_reference = "Paid an invoice with transaction id of  " . $transaction_id;
                 $action_name = "Invoice Payment";
                 $action_icon = "fal fa-users-medical text-success";
                 $page_id = "project-payments-plan-tab";
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


}



//END OF POST REQUEST


?>
