<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //if its stock creation
  if(isset($_POST['update-stock']))
  {
    $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $reference_no_stock = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no_stock']));
    $new_stock_remaining = mysqli_real_escape_string($dbc,strip_tags($_POST['new_stock_remaining']));
    $payment_type = mysqli_real_escape_string($dbc,strip_tags($_POST['payment_type']));

    $invoice_received_id = mysqli_real_escape_string($dbc,strip_tags($_POST['invoice_received_id']));
      $supplier_name= mysqli_real_escape_string($dbc,strip_tags($_POST['supplier_name']));
    $unit_price= mysqli_real_escape_string($dbc,strip_tags($_POST['unit_price']));
    $qtt= mysqli_real_escape_string($dbc,strip_tags($_POST['qtt']));
    $order_level = mysqli_real_escape_string($dbc,strip_tags($_POST['order_level']));
  $total = mysqli_real_escape_string($dbc,strip_tags($_POST['total']));

    $date_recorded = date('d-M-yy');

    $recorded_by = $_SESSION['name'];


        // Upload file
        $uploadDir = '../../views/stock-item/documents/';
        $uploadStatus = 1;
        $uploadedFile = '';
        if(!empty($_FILES["file"]["name"])){

            // File path config
            $fileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf', 'doc', 'DOCX');
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
          //insert records to stock item
      /* set autocommit to off */
      /* set autocommit to off */
     mysqli_query($dbc,"START TRANSACTION");

   $update = mysqli_query($dbc,"UPDATE single_product SET
                                     stock_remaining ='".$new_stock_remaining."' WHERE id = '".$reference_no."'");

      $insert_invoice = mysqli_query($dbc,"INSERT INTO invoice_received
        (reference_no, invoice_received_id, supplier_id, unit_price, qtt,order_level, total, document, payment_type, date_recorded, recorded_by)
      VALUES ('".$reference_no_stock."','".$invoice_received_id."','".$supplier_name."', '".$unit_price."', '".$qtt."', '".$order_level."','".$total."',
        '".$uploadedFile."', '".$payment_type."','".$date_recorded."','".$recorded_by."')") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Updated Stock for Item " . $reference_no_stock;
    $action_name = "Stock Update";
    $action_icon = "far fa-project-diagram text-success";
    $page_id = "stock-payment-link";
    $time_recorded = date('Y/m/d H:i:s');

    $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                    (email,action_name,action_reference,action_icon,page_id,time_recorded)
                        VALUES
                ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                        '".$action_icon."','".$page_id."','".$time_recorded."')"
                 );

                 if($update && $insert_invoice && $sql_log)
                 {
                   exit("success");
                   mysqli_query($dbc,"COMMIT");
                 }
                 else {
                   echo mysqli_error($dbc);
                   mysqli_query($dbc,"ROLLBACK");
                   exit("failed");
                 }


    }
    }

  //end of add stock

  //start of edit project
  else if(isset($_POST['edit_project']))
  {
    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $strategic_objective = mysqli_real_escape_string($dbc,strip_tags($_POST['strategic_objective']));
    $project_name = mysqli_real_escape_string($dbc,strip_tags($_POST['project_name']));
    $project_description = mysqli_real_escape_string($dbc,strip_tags($_POST['project_description']));
    $start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['project_start_date']));
    $end_date= mysqli_real_escape_string($dbc,strip_tags($_POST['project_end_date']));
    $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));
    $internal_currency = mysqli_real_escape_string($dbc,strip_tags($_POST['internal_currency']));
    $external_currency = mysqli_real_escape_string($dbc,strip_tags($_POST['external_currency']));
    $internal_budget = mysqli_real_escape_string($dbc,strip_tags($_POST['internal_budget']));
    $external_budget = mysqli_real_escape_string($dbc,strip_tags($_POST['external_budget']));
    $funding_agency = mysqli_real_escape_string($dbc,strip_tags($_POST['funding_agency']));
    $project_owner = mysqli_real_escape_string($dbc,strip_tags($_POST['project_owner']));
    $senior_user = mysqli_real_escape_string($dbc,strip_tags($_POST['senior_user']));
    $senior_contractor = mysqli_real_escape_string($dbc,strip_tags($_POST['senior_contractor']));
    $project_advisor = mysqli_real_escape_string($dbc,strip_tags($_POST['project_advisor']));
    $related_activity = mysqli_real_escape_string($dbc,strip_tags($_POST['related_workplan_activity']));

    $project_id_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id FROM pm_projects WHERE id='".$id."'"));
    $project_id = $project_id_row['project_id'];


    $recorded_by = $_SESSION['name'];




    // Upload file
    $uploadDir = '../../views/project-management/documents/';
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
      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      //insert records to sql
      $sql_insert = "UPDATE pm_projects SET
      strategic_objective_id='$strategic_objective',
      activity_id='$related_activity',
      project_name='$project_name',
      project_description='$project_description',
      start_date='$start_date',
      end_date='$end_date',
      duration='$duration',
      project_owner='$project_owner',
      senior_user='$senior_user',
      senior_contractor='$senior_contractor',
      project_advisor='$project_advisor',
      contract_document='$uploadedFile',
      recorded_by='$recorded_by'

      WHERE id='$id'
      ";

      $insert_project = mysqli_query($dbc,$sql_insert) or die (mysqli_error($dbc));


      //check if project contractor exists, if not, add contractor into contractors table
      $sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT contractor_name FROM pm_contractors WHERE contractor_name='".$senior_contractor."'"));
      if($sql_check < 1)
      {
          $insert_contractor = mysqli_query($dbc,"INSERT INTO pm_contractors (contractor_name,recorded_by) VALUES ('".$senior_contractor."','".$recorded_by."')");
      }

    //log the action
    $action_reference = "Modified the with the id " . $id;
    $action_name = "Project Modification";
    $action_icon = "far fa-project-diagram text-warning";
    $page_id = "monitor-projects-link";
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

  //end of edit project

  //start close project

  else if(isset($_POST['close_project']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);


    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['sid']));

    $close_project = mysqli_query($dbc,"UPDATE pm_projects SET status='closed' WHERE id='".$id."'");

    //log the action
    $action_reference = "Closed the project with the id " . $id;
    $action_name = "Project Closure";
    $action_icon = "fal fa-trash-alt text-danger";
    $page_id = "monitor-projects-link";
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
  //end close project

  //start phase update project

  else if(isset($_POST['add_project_phase']))
  {

    mysqli_autocommit($dbc, FALSE);

    $project_id = mysqli_real_escape_string($dbc,strip_tags($_POST['project_id']));
    $project_phase = mysqli_real_escape_string($dbc,strip_tags($_POST['project_phase']));

    $date_recorded = date('d-M-yy');
    $recorded_by = $_SESSION['name'];


    $insert_project_phase = mysqli_query($dbc,"INSERT INTO pm_projects_update
                                           (project_id,project_phase,date_recorded,recorded_by)
                                       VALUES
                                       ('".$project_id."','".$project_phase."','".$date_recorded."','".$recorded_by."')
                             ") or die (mysqli_error($dbc));


    //log the action
    $action_reference = "Updated Project Phase with the id " . $project_id;
    $action_name = "Project Phase update";
    $action_icon = "fal fa-trash-alt text-danger";
    $page_id = "monitor-projects-link";
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
  //end update project phase

  // add project status

  else if(isset($_POST['add_project_status']))
  {

    mysqli_autocommit($dbc, FALSE);

    $project_id = mysqli_real_escape_string($dbc,strip_tags($_POST['project_id']));
    $project_status = mysqli_real_escape_string($dbc,strip_tags($_POST['project_status']));

    $date_recorded = date('d-M-yy');
    $recorded_by = $_SESSION['name'];


    $insert_project_phase = mysqli_query($dbc,"INSERT INTO pm_projects_update_status
                                           (project_id,project_status,date_recorded,recorded_by)
                                       VALUES
                                       ('".$project_id."','".$project_status."','".$date_recorded."','".$recorded_by."')
                             ") or die (mysqli_error($dbc));


    //log the action
    $action_reference = "Updated Project Status with the id " . $project_id;
    $action_name = "Project Phase update";
    $action_icon = "fal fa-trash-alt text-danger";
    $page_id = "monitor-projects-link";
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
  //end update project status



}

//END OF POST REQUEST


?>
