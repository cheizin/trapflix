<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  //start close project

   if(isset($_POST['make_top']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id'];

    $insert_project_phase = mysqli_query($dbc,"INSERT INTO major_channels
                                           (category_id)
                                       VALUES
                                       ('".$id."')
                             ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Made top Channel " . $id['id'];
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

  else if(isset($_POST['disapprove_channel']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT category_name FROM main_categories WHERE id='".$id."'"));
    $name = $name['category_name'];
    
    $channel_name = str_replace(' ', '%20', $name);


    //mysqli_query($dbc,"DELETE FROM videos WHERE id ='".$id."'");

    mysqli_query($dbc,"UPDATE main_categories SET
                                        approved ='no' WHERE id ='".$id."'");

    //log the action
    $action_reference = "Deleted the channel with id " . $id['id'];
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
        
                        $phone = mysqli_fetch_array(mysqli_query($dbc,"SELECT mobile FROM users WHERE access_level='admin' ORDER BY id DESC LIMIT 1"));
        $phone= $phone['mobile'];
        
        $message_admin = 'The%20Channel%20'.$channel_name.'%20has%20been%20Disapproved%20by%20Trapflix%20Admin';
        
        //start send sms notification to channel admin
              $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$phone}&message={$message_admin}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
        
        //end send sms notification to channel admin
        
        
        //start send sms notification to channel creator
        $sql_creator = mysqli_fetch_array(mysqli_query($dbc,"SELECT mobile FROM users WHERE id IN (SELECT user_id FROM main_categories WHERE id='".$id."')"));
        $contact = $sql_creator['mobile'];
        $message= 'The%20Channel%20'.$channel_name.'%20has%20been%20Disapproved%20by%20Trapflix%20Admin';
              $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message={$message}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
        
        //end send sms notification to channel creator
        
        
      exit("success");
    }
    else
    {
      mysqli_rollback($dbc);
      exit("failed");
    }
  }
  
    else if(isset($_POST['approve_channel']))
  {
    /* set autocommit to off */
    mysqli_autocommit($dbc, FALSE);

    $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));
    
    //channel name
    
    $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT category_name FROM main_categories WHERE id='".$id."'"));
    $name = $name['category_name'];
    
    $channel_name = str_replace(' ', '%20', $name);

  //  $project_id = mysqli_fetch_array(mysqli_query($dbc,"SELECT project_id,project_name,id FROM pm_projects WHERE id='".$id."'"));
  //  $project_id = $project_id['project_id'];


    //mysqli_query($dbc,"DELETE FROM videos WHERE id ='".$id."'");

    mysqli_query($dbc,"UPDATE main_categories SET
                                        approved ='yes' WHERE id ='".$id."'");

    //log the action
    $action_reference = "Approved the channel with id " . $id['id'];
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
        
                $phone = mysqli_fetch_array(mysqli_query($dbc,"SELECT mobile FROM users WHERE access_level='admin' ORDER BY id DESC LIMIT 1"));
        $phone= $phone['mobile'];
        
        $message_admin = 'The%20Channel%20'.$channel_name.'%20has%20been%20Approved%20by%20Trapflix%20Admin%20You%20Can%20Now%20Post%20Unlimited%20Videos%20';
        
        //start send sms notification to channel admin
              $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$phone}&message={$message_admin}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
        
        //end send sms notification to channel admin
        
        
        //start send sms notification to channel creator
        $sql_creator = mysqli_fetch_array(mysqli_query($dbc,"SELECT mobile FROM users WHERE id IN (SELECT user_id FROM main_categories WHERE id='".$id."')"));
        $contact = $sql_creator['mobile'];
        $message= 'The%20Channel%20'.$channel_name.'%20has%20been%20Approved%20by%20Trapflix%20Admin';
              $curl = curl_init(); 
               curl_setopt_array($curl, array(   CURLOPT_URL 
               => "https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients={$contact}&message={$message}",  
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => "GET", )); 
               $response = curl_exec($curl); 
               curl_close($curl); 
        
        //end send sms notification to channel creator
        
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
