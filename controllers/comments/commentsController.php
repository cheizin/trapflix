<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //start of add job seeker
 if(isset($_POST['add-comment']))
{
    $name = mysqli_real_escape_string($dbc,strip_tags($_POST['name']));

    $youtube_vid = mysqli_real_escape_string($dbc,strip_tags($_POST['youtube_vid']));
    $comment_name = mysqli_real_escape_string($dbc,strip_tags($_POST['comment_name']));


  /* set autocommit to off */
  mysqli_autocommit($dbc, FALSE);

  $sql_insert= mysqli_query($dbc,"INSERT INTO videos_comments
        (video_id, commentor_name,comment_name)
          VALUES ('".$youtube_vid."', '".$name."','".$comment_name."')") or die (mysqli_error($dbc));


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


//start of job recruiter
else if(isset($_POST['add-recruiter']))
{

  $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
//check if the user exists in the database, if not, add the user
$sql = mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Email='".$Email."'");
if(mysqli_num_rows($sql) < 1)
{
  //add user
$access_level = mysqli_real_escape_string($dbc,strip_tags($_POST['recruiter']));
$fName = mysqli_real_escape_string($dbc,strip_tags($_POST['cName']));
$lName = mysqli_real_escape_string($dbc,strip_tags($_POST['industry_name']));
$dob = mysqli_real_escape_string($dbc,strip_tags($_POST['dob']));
$nationality= mysqli_real_escape_string($dbc,strip_tags($_POST['nationality']));
//$gender = mysqli_real_escape_string($dbc,strip_tags($_POST['gender']));

$Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
$location = mysqli_real_escape_string($dbc,strip_tags($_POST['location']));
  $contact= mysqli_real_escape_string($dbc,strip_tags($_POST['contact']));

$highestQualification = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_no']));
$currentPosition = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_type']));
$companyName = mysqli_real_escape_string($dbc,strip_tags($_POST['about_us_name']));

$experience = mysqli_real_escape_string($dbc,strip_tags($_POST['web_url']));
$password = mysqli_real_escape_string($dbc,strip_tags(md5($_POST['password'])));

$passwordmd5 = md5($password);

$refferred_token = mysqli_real_escape_string($dbc,strip_tags($_POST['refferred_token']));
$token2 = mysqli_real_escape_string($dbc,strip_tags($_POST['token2']));
$date_recorded = date('d-M-yy');


$time_recorded = date('Y/m/d H:i:s');

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

$sql_insert= mysqli_query($dbc,"INSERT INTO staff_users
    (fName, lName, dob, gender, Email, location,access_level, contact, nationality, highestQualification, currentPosition,companyName, experience,
      date_recorded,time_recorded,token,url,refferred_token, password)
      VALUES ('".$fName."','".$lName."','".$dob."', '".$gender."', '".$Email."','".$location."','".$access_level."', '".$contact."',
        '".$nationality."','".$highestQualification."', '".$currentPosition."','".$companyName."','".$experience."','".$date_recorded."',
        '".$time_recorded."','".$token2."','https://career.panoramaengineering.com/referralSignup.php?token=".$token2."','".$refferred_token."','".$password."')") or die (mysqli_error($dbc));

//log the action
$action_reference = "Added Company Details " . $fName;
$action_name = "Company Information Creation";
$action_icon = "far fa-project-diagram text-success";
$page_id = "Job Seeker Application Details";
$time_recorded = date('Y/m/d H:i:s');

$sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
          (email,action_name,action_reference,action_icon,page_id,time_recorded)
              VALUES
      ('".$Email."','".$action_name."','".$action_reference."',
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

else
{
    exit("duplicate");
}


}

  else if(isset($_POST['add-about-me']))
  {
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    $about_me = mysqli_real_escape_string($dbc,strip_tags($_POST['about_me']));



    $date_created = date('d-M-yy');


    $time_recorded = date('Y/m/d H:i:s');

  /* set autocommit to off */
  mysqli_autocommit($dbc, FALSE);

  $sql_insert= mysqli_query($dbc,"INSERT INTO about_me
        (email, about_me, date_created,time_recorded)
          VALUES ('".$email."','".$about_me."','".$date_created."',
            '".$time_recorded."')") or die (mysqli_error($dbc));


  //log the action
  $action_reference = "Added About me " . $about_me;
  $action_name = "Personal Information Creation";
  $action_icon = "far fa-project-diagram text-success";
  $page_id = "About Me personal Details";
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

    else if(isset($_POST['add-job-title']))
    {
      //$email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
      $job_title = mysqli_real_escape_string($dbc,strip_tags($_POST['job_title']));

        $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));


    //  $date_created = date('d-M-yy');


      $time_recorded = date('Y/m/d H:i:s');

        $recorded_by = $_SESSION['lName'];

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $sql_insert= mysqli_query($dbc,"INSERT INTO job_titles
          (title_name,grouping_name, recorded_by)
            VALUES ('".$job_title."','".$id."','".$recorded_by."')") or die (mysqli_error($dbc));


    //log the action
    $action_reference = "Added Job Title " . $job_title;
    $action_name = "Job title Creation";
    $action_icon = "far fa-project-diagram text-success";
    $page_id = "About Me personal Details";
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

      else if(isset($_POST['add-group-title']))
      {
        //$email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
        $grouping_name = mysqli_real_escape_string($dbc,strip_tags($_POST['grouping_name']));

      //  $date_created = date('d-M-yy');


        $time_recorded = date('Y/m/d H:i:s');

          $recorded_by = $_SESSION['lName'];

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      $sql_insert= mysqli_query($dbc,"INSERT INTO job_titles_grouping
            (grouping_name, recorded_by)
              VALUES ('".$grouping_name."','".$recorded_by."')") or die (mysqli_error($dbc));


      //log the action
      $action_reference = "Added Assessement Group " . $job_title;
      $action_name = "Job title Creation";
      $action_icon = "far fa-project-diagram text-success";
      $page_id = "About Me personal Details";
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


      // view test Details
      else if(isset($_POST['add-test-title']))
      {
        //$email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));

        $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
        $test_title = mysqli_real_escape_string($dbc,strip_tags($_POST['test_title']));

      //  $date_created = date('d-M-yy');

        $time_recorded = date('Y/m/d H:i:s');

          $recorded_by = $_SESSION['lName'];

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      $sql_insert= mysqli_query($dbc,"INSERT INTO job_test
            (reference_no, test_name, recorded_by)
              VALUES ('".$id."','".$test_title."','".$recorded_by."')") or die (mysqli_error($dbc));


      //log the action
      $action_reference = "Added Job Test " . $test_title;
      $action_name = "Job testCreation";
      $action_icon = "far fa-project-diagram text-success";
      $page_id = "About Me personal Details";
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



  else if(isset($_POST['add-employment-history']))
  {
      $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
    $comp_name= mysqli_real_escape_string($dbc,strip_tags($_POST['comp_name']));
    $industry = mysqli_real_escape_string($dbc,strip_tags($_POST['industry']));
    $job_title = mysqli_real_escape_string($dbc,strip_tags($_POST['job_title']));
    $country = mysqli_real_escape_string($dbc,strip_tags($_POST['country']));

    $work_type = mysqli_real_escape_string($dbc,strip_tags($_POST['work_type']));
    $monthly_salary = mysqli_real_escape_string($dbc,strip_tags($_POST['monthly_salary']));
      $job_level = mysqli_real_escape_string($dbc,strip_tags($_POST['job_level']));
    $start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['start_date']));
    $end_date = mysqli_real_escape_string($dbc,strip_tags($_POST['end_date']));
    $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));

    $experience = mysqli_real_escape_string($dbc,strip_tags($_POST['experience']));
    $job_responsibilities = mysqli_real_escape_string($dbc,strip_tags($_POST['job_responsibilities']));

    $date_recorded = date('d-M-yy');
    $time_recorded = date('Y/m/d H:i:s');

  /* set autocommit to off */
  mysqli_autocommit($dbc, FALSE);

  $sql_insert= mysqli_query($dbc,"INSERT INTO employment_history
        (email, comp_name, industry, job_title, country, work_type, monthly_salary, job_level, start_date, end_date, duration,
           job_responsibilities, date_recorded,time_recorded)
          VALUES ('".$email."','".$comp_name."','".$industry."', '".$job_title."', '".$country."','".$work_type."', '".$monthly_salary."',
            '".$job_level."','".$start_date."', '".$end_date."','".$duration."','".$job_responsibilities."',
            '".$date_recorded."','".$time_recorded."')") or die (mysqli_error($dbc));


  //log the action
  $action_reference = "Added WOrk History " . $comp_name;
  $action_name = "Personal Information Creation";
  $action_icon = "far fa-project-diagram text-success";
  $page_id = "Job Seeker Application Details";
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

    else if(isset($_POST['add-education-history']))
    {
      $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
        $school_name = mysqli_real_escape_string($dbc,strip_tags($_POST['school_name']));
      $qualification= mysqli_real_escape_string($dbc,strip_tags($_POST['qualification']));
      $qualification_name = mysqli_real_escape_string($dbc,strip_tags($_POST['qualification_name']));
      $start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['start_date']));
      $end_date = mysqli_real_escape_string($dbc,strip_tags($_POST['end_date']));
      $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));

      $description = mysqli_real_escape_string($dbc,strip_tags($_POST['description']));

      $date_recorded = date('d-M-yy');
      $time_recorded = date('Y/m/d H:i:s');

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $sql_insert= mysqli_query($dbc,"INSERT INTO education_history
          (email, school_name, qualification,qualification_name, start_date, end_date, duration,
             description, date_recorded,time_recorded)
            VALUES ('".$email."','".$school_name."','".$qualification."', '".$qualification_name."','".$start_date."', '".$end_date."','".$duration."','".$description."',
              '".$date_recorded."','".$time_recorded."')") or die (mysqli_error($dbc));


    //log the action
    $action_reference = "Added Education History " . $school_name;
    $action_name = "Personal Information Creation";
    $action_icon = "far fa-project-diagram text-success";
    $page_id = "Job Seeker Application Details";
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

      else if(isset($_POST['add-awards-history']))
      {
        $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
          $award_name = mysqli_real_escape_string($dbc,strip_tags($_POST['award_name']));
        $institution = mysqli_real_escape_string($dbc,strip_tags($_POST['institution']));
        $type = mysqli_real_escape_string($dbc,strip_tags($_POST['type']));
        $year_received= mysqli_real_escape_string($dbc,strip_tags($_POST['year_received']));

        $date_created = date('d-M-yy');
        $time_recorded = date('Y/m/d H:i:s');

      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

      $sql_insert= mysqli_query($dbc,"INSERT INTO awards
            (email, award_name, institution,type, year_received, date_created,time_recorded)
              VALUES ('".$email."','".$award_name."','".$institution."', '".$type."','".$year_received."', '".$date_created."','".$time_recorded."')")
              or die (mysqli_error($dbc));

      //log the action
      $action_reference = "Added Awards History " . $school_name;
      $action_name = "Personal Information Creation";
      $action_icon = "far fa-project-diagram text-success";
      $page_id = "Job Seeker Application Details";
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


        else if(isset($_POST['add-job-skills-history']))
        {


            $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
              $skill_name = mysqli_real_escape_string($dbc,strip_tags($_POST['skill_name']));

  $query = mysqli_query($dbc,"INSERT INTO selected_job_skills
              (email,skill_name)
              VALUES
              ('".$email."','".$skill_name."')

          ") or die (mysqli_error($dbc));

        //  $query = mysqli_query($dbc,"
            //          UPDATE selected_job_skills SET skill_name = ('".$skill_name."')
            //          WHERE email ='".$email."'") or die (mysqli_error($dbc));

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

          else if(isset($_POST['add-competency-history']))
          {

              $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
                $competency_name = mysqli_real_escape_string($dbc,strip_tags($_POST['competency_name']));

    $query = mysqli_query($dbc,"INSERT INTO selected_competencies
                (email,competency_name)
                VALUES
                ('".$email."','".$competency_name."')

            ") or die (mysqli_error($dbc));

          //  $query = mysqli_query($dbc,"
              //          UPDATE selected_job_skills SET skill_name = ('".$skill_name."')

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

            else if(isset($_POST['add-KPI-history']))
            {

                $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
                  $kpi_name = mysqli_real_escape_string($dbc,strip_tags($_POST['kpi_name']));

      $query = mysqli_query($dbc,"INSERT INTO selected_KPI
                  (email,kpi_name)
                  VALUES
                  ('".$email."','".$kpi_name."')

              ") or die (mysqli_error($dbc));

            //  $query = mysqli_query($dbc,"
                //          UPDATE selected_job_skills SET skill_name = ('".$skill_name."')

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
