<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  //if its milestone creation
  if(isset($_POST['add_product_resource']))
  {
      /* set autocommit to off */
      mysqli_autocommit($dbc, FALSE);

                 foreach ($_POST['resource_name'] as $row=>$selectedOption)
                 {
                   $select_last_id_sql = mysqli_query($dbc,"SELECT resource_id,time_recorded FROM endproductresources ORDER BY
                                                         time_recorded DESC LIMIT 1") or die("failed");
                   $id_row = mysqli_fetch_array($select_last_id_sql);
                   $id = $id_row['resource_id'];
                   $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
                   $int = $int+1;

                   $resource_id = "RES".$int;
                   $id = mysqli_real_escape_string($dbc,strip_tags($_POST['id']));

                   $resource_name = mysqli_real_escape_string($dbc,$_POST['resource_name'][$row]);
                   $recorded_by = $_SESSION['name'];

                   $sql_resources = mysqli_query($dbc,"INSERT INTO endproductresources
                                                          (resource_id,reference_no,resource_name,recorded_by)
                                                      VALUES
                                                      ('".$resource_id."','".$id."','".$resource_name."','".$recorded_by."')
                                            ") or die (mysqli_error($dbc));


                 }

                 //log the action
                 $action_reference = "Added a Resource" . $resource_name;
                 $action_name = "Resource Creation";
                 $action_icon = "fal fa-users-medical text-success";
                 $page_id = "project-resource-plan-tab";
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
else if (isset($_POST['delete_resource']))
{
  mysqli_autocommit($dbc, FALSE);


  $resource_id = mysqli_real_escape_string($dbc,strip_tags($_POST['resource_id']));

  $delete = mysqli_query($dbc,"DELETE FROM endproductresources WHERE resource_id='".$resource_id."'");

  //log the action
  $action_reference = "Deleted a Resource with the id " . $resource_id;
  $action_name = "Resource Removal";
  $action_icon = "fas fa-user-slash text-danger";
  $page_id = "project-resource-plan-tab";
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
else if (isset($_POST['add_product_status']))
{
  mysqli_autocommit($dbc, FALSE);

  $reference_no = mysqli_real_escape_string($dbc,strip_tags($_POST['reference_no']));
  $product_status = mysqli_real_escape_string($dbc,strip_tags($_POST['product_status']));
  $add_product_status_comments = mysqli_real_escape_string($dbc,strip_tags($_POST['add_product_status_comments']));
  $date_recorded = date('d-M-yy');
  $recorded_by = $_SESSION['name'];

  if($product_status == "Not Started")
  {
    $color_code_class = "five";
  }
  if($product_status == "In Progress Behind Schedule")
  {
    $color_code_class = "four";
  }
  if($product_status == "In Progress Within Schedule")
  {
    $color_code_class = "three";
  }
  if($product_status == "Completed")
  {
    $color_code_class = "two";
  }
  if($product_status == "Continous")
  {
    $color_code_class = "one";
  }
  if($product_status == "Repriotised")
  {
    $color_code_class = "one";
  }


  $sql_task_update = mysqli_query($dbc,"INSERT INTO product_updates
                                         (reference_no,status,color_code, comments,date_recorded,recorded_by)
                                     VALUES
                                     ('".$reference_no."','".$product_status."','".$color_code_class."','".$comments."','".$date_recorded."','".$recorded_by."')
                           ") or die (mysqli_error($dbc));

    //log the action
    $action_reference = "Updated the status for the product: " . $reference_no;
    $action_name = "Task Update";
    $action_icon = "fad fa-tasks text-info";
    $page_id = "project-resource-plan-tab";
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
else if (isset($_POST['delete_task']))
{
  mysqli_autocommit($dbc, FALSE);

  $task_id = mysqli_real_escape_string($dbc,strip_tags($_POST['sid']));

  mysqli_query($dbc,"DELETE FROM pm_activities WHERE task_id='".$task_id."'");
  mysqli_query($dbc,"DELETE FROM pm_activity_updates WHERE task_id='".$task_id."'");
  mysqli_query($dbc,"DELETE FROM endproductresources WHERE activity_id='".$task_id."'");

  //log the action
  $action_reference = "Deleted a Task with the id " . $task_id;
  $action_name = "Resource Removal";
  $action_icon = "far fa-user-times text-danger";
  $page_id = "project-resource-plan-tab";
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
