<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{


  //if its project creation
  if(isset($_POST['add-project']))
  {
    //fetch last id
   $select_last_id_sql = mysqli_query($dbc,"SELECT project_id,time_recorded FROM pm_projects ORDER BY
                                          time_recorded DESC LIMIT 1");
      $id_row = mysqli_fetch_array($select_last_id_sql);
      $id = $id_row['project_id'];
      $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
      $int = $int+1;

      $project_id = "PROJ".$int;

    $strategic_objective = mysqli_real_escape_string($dbc,strip_tags($_POST['strategic_objective']));
    $project_name = mysqli_real_escape_string($dbc,strip_tags($_POST['project_name']));
    $project_description = mysqli_real_escape_string($dbc,strip_tags($_POST['project_description']));
    $start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['project_start_date']));
    $end_date= mysqli_real_escape_string($dbc,strip_tags($_POST['project_end_date']));
    $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));
    $line = mysqli_real_escape_string($dbc,strip_tags($_POST['line']));
    $internal_currency = mysqli_real_escape_string($dbc,strip_tags($_POST['internal_currency']));
    $external_currency = mysqli_real_escape_string($dbc,strip_tags($_POST['external_currency']));
    $internal_budget = mysqli_real_escape_string($dbc,strip_tags($_POST['internal_budget']));
    $external_budget = mysqli_real_escape_string($dbc,strip_tags($_POST['external_budget']));
    $funding_agency = mysqli_real_escape_string($dbc,strip_tags($_POST['funding_agency']));
    $project_owner = mysqli_real_escape_string($dbc,strip_tags($_POST['project_owner']));
    $project_manager = mysqli_real_escape_string($dbc,strip_tags($_POST['project_manager']));
    $project_phase = mysqli_real_escape_string($dbc,strip_tags($_POST['project_phase']));
  //  $senior_user = mysqli_real_escape_string($dbc,strip_tags($_POST['senior_user']));
    $senior_contractor = mysqli_real_escape_string($dbc,strip_tags($_POST['senior_contractor']));
  //  $project_advisor = mysqli_real_escape_string($dbc,strip_tags($_POST['project_advisor']));
    //$related_activity = mysqli_real_escape_string($dbc,strip_tags($_POST['related_workplan_activity']));

    $related_activity = 'null';

    $senior_user = implode(" , ",$_POST['senior_user']);
    $project_advisor = implode(" , ",$_POST['project_advisor']);

    $date_recorded = date('d-M-y');
    $recorded_by = $_SESSION['name'];


    //insert records to pm_projects

    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);
    $sql_insert = "
                  INSERT INTO pm_projects
                          (project_id,strategic_objective_id,activity_id,project_name,project_description,start_date,end_date,
                            duration,project_owner,senior_contractor,senior_user,project_advisor,project_manager,recorded_by)
                  VALUES
                          ('".$project_id."', '".$strategic_objective."', '".$related_activity."', '".$project_name."',
                            '".$project_description."','".$start_date."', '".$end_date."', '".$duration."','".$project_owner."','".$senior_contractor."',
                            '".$senior_user."','".$project_advisor."','".$project_manager."','".$recorded_by."')
                ";

                foreach ($_POST['related_workplan_activity'] as $selectedOption)
                {

                  $query = mysqli_query($dbc,"
                              INSERT INTO pm_project_attached_workplans
                              (project_id,activity_id,recorded_by)
                              VALUES
                              ('".$project_id."','".$selectedOption."','".$recorded_by."')

                          ") or die (mysqli_error($dbc));
                }

                foreach ($_POST['line'] as $row=>$selectedOption)
                {
                  $funding_agency = mysqli_real_escape_string($dbc,$_POST['funding_agency']);
                  $line = mysqli_real_escape_string($dbc,$_POST['line'][$row]);
                  $currency = mysqli_real_escape_string($dbc,$_POST['currency'][$row]);
                  $amount = mysqli_real_escape_string($dbc,$_POST['budget'][$row]);

                  $sql_budget = mysqli_query($dbc,  "INSERT INTO pm_budget
                                        (project_id,funding_agency,budget_line,currency_type,amount,recorded_by)
                                VALUES
                                        ('".$project_id."', '".$funding_agency."','".$line."' , '".$currency."', '".$amount."','".$recorded_by."')
                              ") or die (mysqli_error($dbc));

                }

                //start insert into pm_stakeholders
                foreach ($_POST['senior_user'] as $row=>$selectedOption)
                {
                  $senior_user = mysqli_real_escape_string($dbc,$_POST['senior_user'][$row]);
                  if($_POST['senior_user'][$row])
                  {
                    $role = 'Senior User';
                    $name= $senior_user;
                  }

                  $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$name."'"));
                  $email = $get_stakeholder_email['Email'];

                  $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                                        (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                                VALUES
                                        ('".$project_id."', '".$role."','".$email."' , '".$name."','".$recorded_by."')
                              ") or die (mysqli_error($dbc));

                }
                foreach ($_POST['project_advisor'] as $row=>$selectedOption)
                {
                  $project_advisor = mysqli_real_escape_string($dbc,$_POST['project_advisor'][$row]);

                  if($_POST['project_advisor'][$row])
                  {
                    $role = 'Project Advisor';
                    $name = $project_advisor;
                  }

                  $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$name."'"));
                  $email = $get_stakeholder_email['Email'];

                  $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                                        (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                                VALUES
                                        ('".$project_id."', '".$role."','".$email."' , '".$name."','".$recorded_by."')
                              ") or die (mysqli_error($dbc));

                }

                $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$project_owner."'"));
                $email = $get_stakeholder_email['Email'];
                $role = 'Project Owner';

                $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                                      (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                              VALUES
                                      ('".$project_id."', '".$role."','".$email."' , '".$project_owner."','".$recorded_by."')
                            ") or die (mysqli_error($dbc));

                $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$project_manager."'"));
                $email = $get_stakeholder_email['Email'];
                $role = 'Project Manager';

                $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                                                  (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                                          VALUES
                                                  ('".$project_id."', '".$role."','".$email."' , '".$project_manager."','".$recorded_by."')
                                        ") or die (mysqli_error($dbc));

                  //end insert into pm_stakeholders

  $sql_insert_phase = "INSERT INTO  pm_projects_update (project_id,project_phase,date_recorded,recorded_by)
                        VALUES
                        ('".$project_id."','".$project_phase."','".$date_recorded."','".$recorded_by."')";


  mysqli_query($dbc,$sql_insert_phase);



    $insert_project = mysqli_query($dbc,$sql_insert);


    //check if project contractor exists, if not, add contractor into contractors table
  $sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT contractor_name FROM pm_contractors WHERE contractor_name='".$senior_contractor."'"));
  if($sql_check < 1)
  {
      $insert_contractor = mysqli_query($dbc,"INSERT INTO pm_contractors (contractor_name,recorded_by) VALUES ('".$senior_contractor."','".$recorded_by."')");
  }

  //log the action
  $action_reference = "Added a project " . $project_name;
  $action_name = "Project Creation";
  $action_icon = "far fa-project-diagram text-success";
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
  //end of add project

  //start of edit project
  else if(isset($_POST['edit_project']))
  {

    // mysqli_real_escape_string($dbc,strip_tags($_POST['senior_user']));

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $strategic_objective = mysqli_real_escape_string($dbc,strip_tags($_POST['strategic_objective']));
    $project_name = mysqli_real_escape_string($dbc,strip_tags($_POST['project_name']));
    $project_description = mysqli_real_escape_string($dbc,strip_tags($_POST['project_description']));
    $start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['project_start_date']));
    $end_date= mysqli_real_escape_string($dbc,strip_tags($_POST['project_end_date']));
    $duration = mysqli_real_escape_string($dbc,strip_tags($_POST['duration']));
    $funding_agency = mysqli_real_escape_string($dbc,strip_tags($_POST['funding_agency']));
    $project_owner = mysqli_real_escape_string($dbc,strip_tags($_POST['project_owner']));
    $project_manager = mysqli_real_escape_string($dbc,strip_tags($_POST['project_manager']));
   //$senior_user = mysqli_real_escape_string($dbc,strip_tags($_POST['senior_user']));
    $senior_contractor = mysqli_real_escape_string($dbc,strip_tags($_POST['senior_contractor']));
   //$project_advisor = mysqli_real_escape_string($dbc,strip_tags($_POST['project_advisor']));
    //$related_activity = mysqli_real_escape_string($dbc,strip_tags($_POST['related_workplan_activity']));
    $related_activity = 'null';

    $senior_user = implode(" , ",$_POST['senior_user']);
    $project_advisor = implode(" , ",$_POST['project_advisor']);

    $project_id_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id FROM pm_projects WHERE id='".$id."'"));
    $project_id = $project_id_row['project_id'];

    $date_recorded = date('d-M-y');
    $recorded_by = $_SESSION['name'];

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
    senior_contractor='$senior_contractor',
    senior_user='".$senior_user."',
    project_advisor='".$project_advisor."',
    project_manager='".$project_manager."',
    recorded_by='$recorded_by'

    WHERE project_id='$id'
    ";

    $insert_project = mysqli_query($dbc,$sql_insert) or die (mysqli_error($dbc));

    //UPDATE attached workplans
    mysqli_query($dbc,"UPDATE pm_project_attached_workplans SET changed='yes' WHERE project_id='$id'");
    foreach ($_POST['related_workplan_activity'] as $selectedOption)
    {

      $query = mysqli_query($dbc,"
                  INSERT INTO pm_project_attached_workplans
                  (project_id,activity_id,recorded_by)
                  VALUES
                  ('".$id."','".$selectedOption."','".$recorded_by."')

              ") or die (mysqli_error($dbc));
    }


    //check if project contractor exists, if not, add contractor into contractors table
    $sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT contractor_name FROM pm_contractors WHERE contractor_name='".$senior_contractor."'"));
    if($sql_check < 1)
    {
        $insert_contractor = mysqli_query($dbc,"INSERT INTO pm_contractors (contractor_name,recorded_by) VALUES ('".$senior_contractor."','".$recorded_by."')");
    }


    $project_id = $id;

    mysqli_query($dbc,"DELETE FROM pm_budget WHERE project_id='".$project_id."'");

    foreach ($_POST['budget_line'] as $row=>$selectedOption)
    {

      $funding_agency = mysqli_real_escape_string($dbc,$_POST['funding_agency']);
      $line = mysqli_real_escape_string($dbc,$_POST['budget_line'][$row]);
      $currency = mysqli_real_escape_string($dbc,$_POST['currency'][$row]);
      $amount = mysqli_real_escape_string($dbc,$_POST['amount'][$row]);
      $query = mysqli_query($dbc,"
                  INSERT INTO pm_budget
                  (project_id,funding_agency,budget_line,currency_type,amount,recorded_by)
                  VALUES
                  ('".$project_id."', '".$funding_agency."','".$line."' , '".$currency."', '".$amount."','".$recorded_by."')

              ") or die (mysqli_error($dbc));
    }

    mysqli_query($dbc,"DELETE FROM pm_project_roles WHERE project_id='".$project_id."'");
    //start insert into pm_stakeholders
    foreach ($_POST['senior_user'] as $row=>$selectedOption)
    {
      $senior_user = mysqli_real_escape_string($dbc,$_POST['senior_user'][$row]);
      if($_POST['senior_user'][$row])
      {
        $role = 'Senior User';
        $name= $senior_user;
      }

      $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$name."'"));
      $email = $get_stakeholder_email['Email'];

      $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                            (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                    VALUES
                            ('".$project_id."', '".$role."','".$email."' , '".$name."','".$recorded_by."')
                  ") or die (mysqli_error($dbc));

    }
    foreach ($_POST['project_advisor'] as $row=>$selectedOption)
    {
      $project_advisor = mysqli_real_escape_string($dbc,$_POST['project_advisor'][$row]);

      if($_POST['project_advisor'][$row])
      {
        $role = 'Project Advisor';
        $name = $project_advisor;
      }

      $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$name."'"));
      $email = $get_stakeholder_email['Email'];

      $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                            (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                    VALUES
                            ('".$project_id."', '".$role."','".$email."' , '".$name."','".$recorded_by."')
                  ") or die (mysqli_error($dbc));

    }

    $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$project_owner."'"));
    $email = $get_stakeholder_email['Email'];
    $role = 'Project Owner';

    $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                          (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                  VALUES
                          ('".$project_id."', '".$role."','".$email."' , '".$project_owner."','".$recorded_by."')
                ") or die (mysqli_error($dbc));

    $get_stakeholder_email = mysqli_fetch_array(mysqli_query($dbc,"SELECT Email FROM staff_users WHERE Name='".$project_manager."'"));
    $email = $get_stakeholder_email['Email'];
    $role = 'Project Manager';

    $sql_stakeholders = mysqli_query($dbc,  "INSERT INTO pm_project_roles
                                      (project_id,project_role,project_stakeholder_email,project_stakeholder_name,recorded_by)
                              VALUES
                                      ('".$project_id."', '".$role."','".$email."' , '".$project_manager."','".$recorded_by."')
                            ") or die (mysqli_error($dbc));

      //end insert into pm_stakeholders

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

  //end of edit project

  //start close project

  else if(isset($_POST['make_popular']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id'];


    $insert_project_phase = mysqli_query($dbc,"INSERT INTO popular_videos
                                           (popular_video_id)
                                       VALUES
                                       ('".$id."')
                             ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Inserted Popular videos " . $id['id'];
    $action_name = "Project Deletion";
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

  else if(isset($_POST['deactivateuser2']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id'];
    //mysqli_query($dbc,"DELETE FROM videos WHERE id ='".$id."'");
        
    mysqli_query($dbc,"UPDATE users SET
                                        user_status='deactivated' WHERE id ='".$id."'");
                                        

    //log the action
    $action_reference = "Deleted the video with id " . $id['id'];
    $action_name = "Project Deletion";
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
  //start close email

  else if(isset($_POST['delete_project']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id
    mysqli_query($dbc,"DELETE FROM bulk_email WHERE id ='".$id."'");

    //log the action
    $action_reference = "Deleted the question with id " . $id['id'];
    $action_name = "Project Deletion";
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
  //end close email

  // delete job posted
  else if(isset($_POST['delete_job']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id'];


    mysqli_query($dbc,"UPDATE job_posting SET
                                        changed='yes' WHERE id ='".$id."' && changed='no'");


    //log the action
    $action_reference = "Deleted the ajob PoST with id " . $id['id'];
    $action_name = "Project Deletion";
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

  //restore jobs
  else if(isset($_POST['restore_job']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id'];


    mysqli_query($dbc,"UPDATE job_posting SET
                                        changed='no' WHERE id ='".$id."' && changed='yes'");


    //log the action
    $action_reference = "Deleted the ajob PoST with id " . $id['id'];
    $action_name = "Project Deletion";
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

  $date_recorded = date('d-M-y');
  $recorded_by = $_SESSION['name'];

  mysqli_query($dbc,"UPDATE pm_projects_update SET changed='yes' WHERE project_id='".$project_id."'");

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

  $date_recorded = date('d-M-y');
  $recorded_by = $_SESSION['name'];

  mysqli_query($dbc,"UPDATE pm_projects_update_status SET changed='yes' WHERE project_id='".$project_id."'");

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
