<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  //start of add job seeker
 if(isset($_POST['post-job']))
{
    $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
  $industry_name = mysqli_real_escape_string($dbc,strip_tags($_POST['industry_name']));
  $job_title = mysqli_real_escape_string($dbc,strip_tags($_POST['job_title']));
  $company_name = mysqli_real_escape_string($dbc,strip_tags($_POST['company_name']));
  $com_type = mysqli_real_escape_string($dbc,strip_tags($_POST['com_type']));
  $expLength = mysqli_real_escape_string($dbc,strip_tags($_POST['expLength']));
  $emp_type = mysqli_real_escape_string($dbc,strip_tags($_POST['emp_type']));
  $rank_name = mysqli_real_escape_string($dbc,strip_tags($_POST['rank_name']));

  $exp_level = mysqli_real_escape_string($dbc,strip_tags($_POST['exp_level']));
    $no_vaccancy = mysqli_real_escape_string($dbc,strip_tags($_POST['no_vaccancy']));
  $job_location = mysqli_real_escape_string($dbc,strip_tags($_POST['job_location']));
    $country = mysqli_real_escape_string($dbc,strip_tags($_POST['country']));
  $deadline = mysqli_real_escape_string($dbc,strip_tags($_POST['deadline']));
  $job_description = mysqli_real_escape_string($dbc,strip_tags($_POST['job_description']));
  $responsibility = mysqli_real_escape_string($dbc,strip_tags($_POST['responsibility']));
    $skills = mysqli_real_escape_string($dbc,strip_tags($_POST['skills']));

  $date_recorded = date('d-M-yy');
  $time_recorded = date('Y/m/d H:i:s');

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

$sql_insert= mysqli_query($dbc,"INSERT INTO job_posting
      (email, job_title, job_category, company_name, com_type, expLength, emp_type, rank_name,exp_level, job_location, country, deadline, no_vaccancy, job_description,responsibility,skills, date_recorded,time_recorded)
        VALUES ('".$email."','".$job_title."','".$industry_name."', '".$company_name."','".$com_type."', '".$expLength."', '".$emp_type."','".$rank_name."','".$exp_level."', '".$job_location."',
          '".$country."','".$deadline."', '".$no_vaccancy."','".$job_description."','".$responsibility."','".$skills."','".$date_recorded."','".$time_recorded."')") or die (mysqli_error($dbc));


//log the action
$action_reference = "Posted Job " . $job_title;
$action_name = "Job Information Creation";
$action_icon = "far fa-project-diagram text-success";
$page_id = "Posting job Details";
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
// save mail but don't sendTo
else if(isset($_POST['post-save-emails']))
{
  $sendTo = mysqli_real_escape_string($dbc,strip_tags($_POST['sendTo']));
    $birthdaytime = mysqli_real_escape_string($dbc,strip_tags($_POST['birthdaytime']));

      $long_desc = $_POST['long_desc'];
//$cover_letter = mysqli_real_escape_string($dbc,strip_tags($_POST['cover_letter']));

//$date_recorded = date('d-M-yyadd-details-form
//$time_recorded = date('Y/m/d H:i:s');

  $recorded_by = $_SESSION['fName'];

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

       $sql_insert= mysqli_query($dbc,"INSERT INTO bulk_save_email
           (sendTo, message, date_time, recorded_by)
             VALUES ('".$sendTo."','".$long_desc."','".$birthdaytime."', '".$recorded_by."')")
              or die (mysqli_error($dbc));

              if($sql_insert)
              {
             //start of pdo FOR multiple emails

             //start of pdo FOR Senior advisors
      foreach ($_POST['sendToMany'] as $selectedEmail)
      {

        $insert_approver = mysqli_query($dbc,"INSERT INTO bulk_email_multiple_schedule (mail_address,mail_details,  recorded_by)
        VALUES ('".$selectedEmail."','".$long_desc."','".$recorded_by."')") or die (mysqli_error($dbc));


      }


        //log the action
        $action_reference = "Message Sent to " . $sendTo;
        $action_name = "Job Application";
        $action_icon = "far fa-project-diagram text-success";
        $page_id = "Job Application Details";
        $time_recorded = date('Y/m/d H:i:s');

        $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                  (email,action_name,action_reference,action_icon,page_id,time_recorded)
                      VALUES
              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                      '".$action_icon."','".$page_id."','".$time_recorded."')"
                ) or die (mysqli_error($dbc));


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
//save emails Sent
else if(isset($_POST['post-emails']))
{

  $sendTo = mysqli_real_escape_string($dbc,strip_tags($_POST['sendTo']));

      $long_desc = $_POST['long_desc'];
//$cover_letter = mysqli_real_escape_string($dbc,strip_tags($_POST['cover_letter']));

//$date_recorded = date('d-M-yyadd-details-form
//$time_recorded = date('Y/m/d H:i:s');

  $recorded_by = $_SESSION['fName'];

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

       $sql_insert= mysqli_query($dbc,"INSERT INTO bulk_email
           (sendTo, message, recorded_by)
             VALUES ('".$sendTo."','".$long_desc."', '".$recorded_by."')")
              or die (mysqli_error($dbc));

        if($sql_insert)
        {
       //start of pdo FOR multiple emails

       //start of pdo FOR Senior advisors
foreach ($_POST['sendToMany'] as $selectedEmail)
{

  $insert_approver = mysqli_query($dbc,"INSERT INTO bulk_email_multiple (mail_address,mail_details,  recorded_by)
  VALUES ('".$selectedEmail."','".$long_desc."','".$recorded_by."')") or die (mysqli_error($dbc));

}

  //log the action
  $action_reference = "Message Sent to " . $sendTo;
  $action_name = "Job Application";
  $action_icon = "far fa-project-diagram text-success";
  $page_id = "Job Application Details";
  $time_recorded = date('Y/m/d H:i:s');

  $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
            (email,action_name,action_reference,action_icon,page_id,time_recorded)
                VALUES
        ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                '".$action_icon."','".$page_id."','".$time_recorded."')"
          ) or die (mysqli_error($dbc));


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



//start of job recruiter
else if(isset($_POST['add-job-application']))
{
  $job_posting_id = mysqli_real_escape_string($dbc,strip_tags($_POST['job_posting_id']));
$applicant_email = mysqli_real_escape_string($dbc,strip_tags($_POST['applicant_email']));
$cover_letter = mysqli_real_escape_string($dbc,strip_tags($_POST['cover_letter']));

//$date_recorded = date('d-M-yy');


$time_recorded = date('Y/m/d H:i:s');

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

$sql_insert= mysqli_query($dbc,"INSERT INTO applied_jobs
    (job_posting_id, applicant_email, cover_letter, time_recorded)
      VALUES ('".$job_posting_id."','".$applicant_email."','".$cover_letter."', '".$time_recorded."')")
       or die (mysqli_error($dbc));

//log the action
$action_reference = "Applied for the job " . $job_posting_id;
$action_name = "Job Application";
$action_icon = "far fa-project-diagram text-success";
$page_id = "Job Application Details";
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

else if(isset($_POST['add_test_answer']))
{
  $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
//$answer_name = mysqli_real_escape_string($dbc,strip_tags($_POST['answer_name']));

    $answer_name = $_POST['answer_name'];
$email= mysqli_real_escape_string($dbc,strip_tags($_POST['email']));

//$date_recorded = date('d-M-yy');

$time_recorded = date('Y/m/d H:i:s');

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

$sql_insert= mysqli_query($dbc,"INSERT INTO answered_test
    (reference_no, email, answer_name, time_recorded)
      VALUES ('".$id."','".$_SESSION['email']."','".$answer_name."', '".$time_recorded."')")
       or die (mysqli_error($dbc));

//log the action
$action_reference = "Answered the test " . $answer_name;
$action_name = "Answered Test";
$action_icon = "far fa-project-diagram text-success";
$page_id = "Job Application Details";
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

else if(isset($_POST['add_response_answer']))
{
  $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
//$response_name = mysqli_real_escape_string($dbc,strip_tags($_POST['response_name']));
  $response_name = $_POST['response_name'];
$email= mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
$marks = mysqli_real_escape_string($dbc,strip_tags($_POST['marks']));

$scheme_name = mysqli_real_escape_string($dbc,strip_tags($_POST['scheme_name']));

//$date_recorded = date('d-M-yy');

$time_recorded = date('Y/m/d H:i:s');

/* set autocommit to off */
mysqli_autocommit($dbc, FALSE);

$sql_insert= mysqli_query($dbc,"INSERT INTO answered_response_test
    (reference_no, email, response_name, marks,remarks, time_recorded)
      VALUES ('".$id."','".$email."','".$response_name."', '".$marks."', '".$scheme_name."','".$time_recorded."')")
       or die (mysqli_error($dbc));

//log the action
$action_reference = "Response for the test " . $response_name;
$action_name = "Answered Test";
$action_icon = "far fa-project-diagram text-success";
$page_id = "Job Application Details";
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

          //start of pdo FOR activity owners
          foreach ($_POST['skill_name'] as $selectedOption)
          {
            $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
              $skill_name = mysqli_real_escape_string($dbc,strip_tags($_POST['skill_name']));

  $query = mysqli_query($dbc,"
              INSERT INTO selected_job_skills
              (email,skill_name)
              VALUES
              ('".$email."','".$selectedOption."')

          ") or die (mysqli_error($dbc));

        //  $query = mysqli_query($dbc,"
            //          UPDATE selected_job_skills SET skill_name = ('".$skill_name."')
            //          WHERE email ='".$email."'") or die (mysqli_error($dbc));
}

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

//  Post REQUEST for application
else if(isset($_POST['add_request_application']))
{

      mysqli_autocommit($dbc, FALSE);

  $Email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
  $job_title  = mysqli_real_escape_string($dbc,strip_tags($_POST['job_title']));
    $lName  = mysqli_real_escape_string($dbc,strip_tags($_POST['lName']));
  $special_info  = mysqli_real_escape_string($dbc,strip_tags($_POST['special_info']));

  $recorded_by = $_SESSION['fName'];
  $time_recorded = date('Y/m/d H:i:s');


$sql_insert= mysqli_query($dbc,"INSERT INTO request_application
      (reference_no, post_name, special_info, time_recorded, recorded_by)
        VALUES ('".$Email."','".$job_title."', '".$special_info."','".$time_recorded."',
          '".$recorded_by."')") or die (mysqli_error($dbc));

//log the action
$action_reference = "Requested Job APPLICATION FOR" . $lName;
$action_name = "Job APPLICATION request";
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


  // Post Request for add test Details

  else if(isset($_POST['add_test_history']))
  {

        mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $test_name  = mysqli_real_escape_string($dbc,strip_tags($_POST['test_name']));

    $recorded_by = $_SESSION['fName'];
    $time_recorded = date('Y/m/d H:i:s');


  $sql_insert= mysqli_query($dbc,"INSERT INTO job_test
        (reference_no, test_name, time_recorded, recorded_by)
          VALUES ('".$id."','".$test_name."','".$time_recorded."',
            '".$recorded_by."')") or die (mysqli_error($dbc));

  //log the action
  $action_reference = "Addedd a Test with details" . $test_name;
  $action_name = "JTest APPLICATION request";
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
    // add Test more Details
    else if(isset($_POST['add_details']))
    {

          mysqli_autocommit($dbc, FALSE);

      $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    //  $long_desc  = mysqli_real_escape_string($dbc,strip_tags($_POST['long_desc']));

      $long_desc = $_POST['long_desc'];
      $recorded_by = $_SESSION['fName'];
      $time_recorded = date('Y/m/d H:i:s');


    $sql_insert= mysqli_query($dbc,"INSERT INTO job_titles_more_description
          (title_name, description)
            VALUES ('".$id."','".$long_desc."')") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Added test details" . $long_desc;
    $action_name = "Test Details";
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


    else if(isset($_POST['add_timer_history']))
    {
          mysqli_autocommit($dbc, FALSE);

          $posted_job = mysqli_real_escape_string($dbc,strip_tags($_POST['posted_job']));

      //    $row2= mysqli_real_escape_string($dbc,strip_tags($_POST['row2']));

          $sql_query = mysqli_query($dbc,"SELECT * FROM assigned_test WHERE posted_job ='".$posted_job."'");

            if(mysqli_num_rows($sql_query) < 1)
            {
              exit("exist");


          }
          else
          {
            $posted_job = mysqli_real_escape_string($dbc,strip_tags($_POST['posted_job']));
            $hrs  = mysqli_real_escape_string($dbc,strip_tags($_POST['hrs']));
            $mins  = mysqli_real_escape_string($dbc,strip_tags($_POST['mins']));

            $recorded_by = $_SESSION['fName'];
            $time_recorded = date('Y/m/d H:i:s');


          $sql_insert= mysqli_query($dbc,"INSERT INTO quiz_timer
                (hrs, mins,  posted_job)
                  VALUES ('".$hrs."','".$mins."','".$posted_job."')") or die (mysqli_error($dbc));

          //log the action
          $action_reference = "Addedd a Timer for the Job Post" . $posted_job;
          $action_name = "JTest APPLICATION request";
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





      }



//Post for assigning test to a job postText

else if(isset($_POST['add-job-post']))
{

      mysqli_autocommit($dbc, FALSE);

  $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
  $job_title  = mysqli_real_escape_string($dbc,strip_tags($_POST['job_title']));

  $recorded_by = $_SESSION['fName'];
  $time_recorded = date('Y/m/d H:i:s');


$sql_insert= mysqli_query($dbc,"INSERT INTO assigned_test
      (reference_no, posted_job, time_recorded, recorded_by)
        VALUES ('".$id."','".$job_title."','".$time_recorded."',
          '".$recorded_by."')") or die (mysqli_error($dbc));

//log the action
$action_reference = "Assigned Job test for " . $job_title;
$action_name = "Job TeST Assignment";
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


// Post save for vaccancy status_name
else if(isset($_POST['add-status-post']))
{

      mysqli_autocommit($dbc, FALSE);


  $email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $status_name = mysqli_real_escape_string($dbc,strip_tags($_POST['status_name']));

  $recorded_by = $_SESSION['fName'];
  $time_recorded = date('Y/m/d H:i:s');


$sql_insert= mysqli_query($dbc,"INSERT INTO application_status_details
      (reference_no, email, status_name, time_recorded, recorded_by)
        VALUES ('".$id."','".$email."','".$status_name."','".$time_recorded."',
          '".$recorded_by."')") or die (mysqli_error($dbc));

//log the action
$action_reference = "Change application status to" . $status_name ;
$action_name = "Job Application status";
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



      else if(isset($_POST['add-evidence-document']))
      {


        $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
      //  $product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
          $recorded_by = $_SESSION['fName'];

            $email = $_SESSION['email'];
        // Upload file

        $uploadDir = '../../views/documents/';
        $uploadStatus = 1;
        $uploadedFile = '';
        if(!empty($_FILES["additional_file"]["name"])){

            // File path config
            $fileName = basename($_FILES["additional_file"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf','mp4', 'doc', 'docx');
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

        $uploadDir = '../../views/documents/';
        $uploadStatus = 1;
        $uploadedFile = '';
        if(!empty($_FILES["additional_file2"]["name"])){

            // File path config
            $fileName = basename($_FILES["additional_file2"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf', 'mp4','doc', 'docx');
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



        $uploadDir = '../../views/documents/';
        $uploadStatus = 1;
        $uploadedFile = '';
        if(!empty($_FILES["file"]["name"])){

            // File path config
            $fileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf', 'mp4','doc', 'docx');
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
            (reference_no, cv, college_doc, kcse_doc, recorded_by)
          VALUES ('".$email."','".$additional_file."', '".$additional_file2."','".$uploadedFile."','".$recorded_by."')") or die (mysqli_error($dbc));



        //log the action
        $action_reference = "Attached Evidence Documents for the reference " . $reference_no;
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

        else if(isset($_POST['add-evidence-document2']))
        {

          $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
        //  $product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
            $recorded_by = $_SESSION['fName'];

              $email = $_SESSION['email'];
          // Upload file
          $uploadDir = '../../views/documents/';
          $uploadStatus = 1;
          $uploadedFile = '';
          if(!empty($_FILES["additional_file"]["name"])){

              // File path config
              $fileName = basename($_FILES["additional_file"]["name"]);
              $targetFilePath = $uploadDir . $fileName;
              $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

              // Allow certain file formats
              $allowTypes = array('mp4', 'avi', 'mov');
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

              if($uploadStatus == 1)
              {
                //insert records to stock item


            /* set autocommit to off */
            mysqli_autocommit($dbc, FALSE);


            $insert_documents = mysqli_query($dbc,"INSERT INTO all_evidence_document
              (reference_no, cv, college_doc, kcse_doc, recorded_by)
            VALUES ('".$email."','".$additional_file."', '".$additional_file2."','".$uploadedFile."','".$recorded_by."')") or die (mysqli_error($dbc));



          //log the action
          $action_reference = "Attached Evidence Documents for the reference " . $reference_no;
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
