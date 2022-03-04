<?php
session_start();
require_once('../setup/connect.php');
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	//temporary login for test user
	        //start sql

      // $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
      // $password = mysqli_real_escape_string($dbc,strip_tags($_POST['password']));
        $sql = "SELECT * FROM staff_users WHERE Email='".$_POST['email']."' && password = MD5('".$_POST['password']."') ";


      //  $sql = "SELECT * FROM phpc_staff_users WHERE Email='".$_POST['email']."' && EmpNo=  MD5('".$_POST['password']."') ";
           if($query = mysqli_query($dbc,$sql))
           {
               if(mysqli_num_rows($query) > 0)
               {
                   while($row = mysqli_fetch_array($query))
                   {

                     if($row['status'] == 'deactivated')
                     {
                        exit('deactivated');
                     }
                     else
                     {


                         //   $dep_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['DepartmentCode']."'"));
                            $_SESSION['email'] = $row['Email'];
                            $_SESSION['id'] = $row['id'];
                            $_SESSION['fName'] = $row['fName'];
                             $_SESSION['lName'] = $row['lName'];
                            $_SESSION['contact'] = $dep_name['contact'];
     //$_SESSION['department_code'] = $row['DepartmentCode'];
                         //   $_SESSION['designation'] = $row['designation'];
                            $_SESSION['access_level'] = $row['access_level'];
                            $_SESSION['test_user'] = true;


                            $time_signed_in  = date('Y/m/d H:i:s');
                            $_SESSION['time_signed_in'] = $time_signed_in;
                            $ip_address = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDE??D_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
                            $date_recorded = date('Y/m/d');
                            $user_type = 'testuser';


                            //insert into sign in logs

                              $sql_insert = "INSERT INTO sign_in_logs(email,name,time_signed_in,ip_address,date_recorded)
                                              VALUES

                                              ('".$_SESSION['email']."','".$_SESSION['name']."','".$_SESSION['time_signed_in']."','".$ip_address."','".$date_recorded."')
                                              ";
                              mysqli_query($dbc,$sql_insert) or die(exit("failed"));

                              //insert into activity logs


                              $action_reference = "Logged into the system";
                              $action_name = "Logged in";
                              $action_icon = "far fa-sign-in text-success";
                              $page_id = "login-link";
                              $time_recorded = $time_signed_in;

                               $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                                            (email,action_name,action_reference,action_icon,page_id,time_recorded)
                                      VALUES
                                      ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                                      '".$action_icon."','".$page_id."','".$time_recorded."')"
                               );
                                  exit('success');
                     }


                 }

               }
               else
               {

                   exit('invalid');
               }
           }

        //end sql
	//end of temporary login

}



 ?>
