<?php
require_once('../setup/connect.php');
require_once('../middleware/RequestIsPost.php');
require_once('../middleware/UserIsAuthenticated.php');


  //if its stock creation
  if(isset($_POST['upload-video']))
  {
    $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));
    $item_name = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    $item_description = mysqli_real_escape_string($dbc,strip_tags($_POST['item_description']));
    $title = mysqli_real_escape_string($dbc,strip_tags($_POST['title']));
    $token = bin2hex(random_bytes(20));

    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));

    $date_recorded = date('d-M-yy');

    $recorded_by = $_SESSION['name'];


    // Upload file

    $uploadDir = '../../video/';
    $uploadStatus = 1;
    $uploadedFile = '';
    if(!empty($_FILES["additional_file"]["name"])){

        // File path config
        $fileName = basename($_FILES["additional_file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('avi','mp4',  'mov');
        if(in_array($fileType, $allowTypes)){
            // Upload file to the server
            if(move_uploaded_file($_FILES["additional_file"]["tmp_name"], $targetFilePath)){
                $additional_file = $fileName;
            }else{
                $uploadStatus = 0;
                exit('error-uploading');
            }
        }else{
            $uploadStatus = 0;
            exit('invalid-file');
        }
    }
    else
    {
      $additional_file = '';
    }

    // Upload file

    $uploadDir = '../../images/favoriteCompressed/';
    $uploadStatus = 1;
    $uploadedFile = '';
    if(!empty($_FILES["file"]["name"])){

        // File path config
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
          $allowTypes = array('pdf', 'jpg', 'jpeg', 'png', 'PNG', 'JPG');
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
      mysqli_autocommit($dbc, FALSE);
      $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
      $token = mysqli_real_escape_string($dbc,strip_tags($_POST['token']));
      $textname = mysqli_real_escape_string($dbc,strip_tags($_POST['textname']));
      $video_description= mysqli_real_escape_string($dbc,strip_tags($_POST['video_description']));
      $title = mysqli_real_escape_string($dbc,strip_tags($_POST['title']));

      $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
      $sql_insert= mysqli_query($dbc,"INSERT INTO videos
              (sort_id, email,token,title,textname,video_description, videoname,thumbnail)
                                         VALUES
                                         ('".$reference_no."','".$email."', '".$token."', '".$title."', '".$textname."','".$video_description."',
                                          '".$additional_file."', '".$file."') ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Added a Stock Item " . $item_name;
    $action_name = "Stock Creation";
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
    }


  else if(isset($_POST['add-evidence-document']))
  {

    $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
    $item_name = mysqli_real_escape_string($dbc,strip_tags($_POST['item_name']));
      $recorded_by = $_SESSION['name'];
    // Upload file

    $uploadDir = '../../views/stock-item/documents/';
    $uploadStatus = 1;
    $uploadedFile = '';
    if(!empty($_FILES["additional_file"]["name"])){

        // File path config
        $fileName = basename($_FILES["additional_file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('pdf', 'doc', 'docx');
        if(in_array($fileType, $allowTypes)){
            // Upload file to the server
            if(move_uploaded_file($_FILES["additional_file"]["tmp_name"], $targetFilePath)){
                $additional_file = $fileName;
            }else{
                $uploadStatus = 0;
                exit('error-uploading');
            }
        }else{
            $uploadStatus = 0;
            exit('invalid-file');
        }
    }
    else
    {
      $additional_file = '';
    }

    // Upload file

    $uploadDir = '../../views/stock-item/documents/';
    $uploadStatus = 1;
    $uploadedFile = '';
    if(!empty($_FILES["additional_file2"]["name"])){

        // File path config
        $fileName = basename($_FILES["additional_file2"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('pdf', 'doc', 'docx');
        if(in_array($fileType, $allowTypes)){
            // Upload file to the server
            if(move_uploaded_file($_FILES["additional_file2"]["tmp_name"], $targetFilePath)){
                $additional_file2 = $fileName;
            }else{
                $uploadStatus = 0;
                exit('error-uploading');
            }
        }else{
            $uploadStatus = 0;
            exit('invalid-file');
        }
    }
    else
    {
      $additional_file2 = '';
    }



    $uploadDir = '../../views/stock-item/documents/';
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
          //insert records to stock item


      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);


      $insert_documents = mysqli_query($dbc,"INSERT INTO all_evidence_document
        (reference_no, delivery_note_doc,purchase_order_doc, invoice_doc, recorded_by)
      VALUES ('".$reference_no."','".$additional_file."', '".$additional_file2."','".$uploadedFile."','".$recorded_by."')") or die (mysqli_error($dbc));



    //log the action
    $action_reference = "Attached Evidence Documents for the Stock " . $item_name;
    $action_name = "Documents Attachments";
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
    }


      else if(isset($_POST['add-delivery-evidence-document']))
      {

        $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
        $product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
          $recorded_by = $_SESSION['name'];
        // Upload file

        $uploadDir = '../../views/stock-item/documents/';
        $uploadStatus = 1;
        $uploadedFile = '';
        if(!empty($_FILES["additional_file"]["name"])){

            // File path config
            $fileName = basename($_FILES["additional_file"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf', 'doc', 'docx');
            if(in_array($fileType, $allowTypes)){
                // Upload file to the server
                if(move_uploaded_file($_FILES["additional_file"]["tmp_name"], $targetFilePath)){
                    $additional_file = $fileName;
                }else{
                    $uploadStatus = 0;
                    exit('error-uploading');
                }
            }else{
                $uploadStatus = 0;
                exit('invalid-file');
            }
        }
        else
        {
          $additional_file = '';
        }

        // Upload file

        $uploadDir = '../../views/stock-item/documents/';
        $uploadStatus = 1;
        $uploadedFile = '';
        if(!empty($_FILES["additional_file2"]["name"])){

            // File path config
            $fileName = basename($_FILES["additional_file2"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf', 'doc', 'docx');
            if(in_array($fileType, $allowTypes)){
                // Upload file to the server
                if(move_uploaded_file($_FILES["additional_file2"]["tmp_name"], $targetFilePath)){
                    $additional_file2 = $fileName;
                }else{
                    $uploadStatus = 0;
                    exit('error-uploading');
                }
            }else{
                $uploadStatus = 0;
                exit('invalid-file');
            }
        }
        else
        {
          $additional_file2 = '';
        }



        $uploadDir = '../../views/stock-item/documents/';
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
              //insert records to stock item


          /* set autocommit to off */
          mysqli_autocommit($dbc, FALSE);


          $insert_documents = mysqli_query($dbc,"INSERT INTO all_evidence_document
            (reference_no, delivery_note_doc,purchase_order_doc, invoice_doc, recorded_by)
          VALUES ('".$id."','".$additional_file."', '".$additional_file2."','".$uploadedFile."','".$recorded_by."')") or die (mysqli_error($dbc));



        //log the action
        $action_reference = "Attached Evidence Documents for the End Product " . $product_name;
        $action_name = "Documents Attachments";
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
        }

        //start of edit project
  else if(isset($_POST['add-product-category']))
  {
    $category_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category_name']));

    $recorded_by = $_SESSION['name'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $sql_insert= mysqli_query($dbc,"INSERT INTO product_category
            (category_name,recorded_by)
                                       VALUES
                                       ('".$category_name."','".$recorded_by."') ") or die (mysqli_error($dbc));


  //log the action
  $action_reference = "Added a Stock category " . $item_name;
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




?>
