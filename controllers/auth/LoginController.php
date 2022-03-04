<?php
session_start();
require_once('../setup/connect.php');
require_once("../../assets/libs/BrowserDetection/lib/BrowserDetection.php");
if($_SERVER['REQUEST_METHOD'] == 'POST')
{


	//temporary login for test user
	        //start sql
          /*
        $sql = "SELECT * FROM staff_users WHERE Email='".$_POST['email']."' && EmpNo='".$_POST['password']."' ";
           if($query = mysqli_query($dbc,$sql))
           {
               if(mysqli_num_rows($query) > 0)
               {
                   while($row = mysqli_fetch_array($query))
                   {
                     if($row['Email'] == 'testuser@test.com' && $row['EmpNo'] == 'TEST001')
                     {
                       $_SESSION['email'] = $row['Email'];
                       $_SESSION['name'] = $row['Name'];
                       $_SESSION['department'] = $row['Department'];
                       $_SESSION['department_code'] = $row['DepartmentCode'];
                       $_SESSION['designation'] = $row['designation'];
                       $_SESSION['access_level'] = $row['access_level'];

                       $time_signed_in  = date('Y/m/d H:i:s');
                       $_SESSION['time_signed_in'] = $time_signed_in;
                       $ip_address = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDE??D_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
                       $date_recorded = date('Y/m/d');


                       //insert into sign in logs

                       $sql_insert = "INSERT INTO sign_in_logs(email,name,time_signed_in,ip_address,date_recorded)
                                       VALUES

                                       ('".$_SESSION['email']."','".$_SESSION['name']."','".$_SESSION['time_signed_in']."','".$ip_address."','".$date_recorded."')
                                       ";
                       mysqli_query($dbc,$sql_insert) or die(exit("failed"));

                   }
                 }
                   exit('success');
               }
               else
               {

                   exit('invalid');
               }
           }
           */
        //end sql
	//end of temporary login

  // set to true to display debug info
  	$debug = (bool)FALSE;

  	// used for LDAP Bind
  	$ldapdomain	= "Cma.local";
  	$ldapdn		= "DC=Cma,DC=local";
  	$ldapip		= "ldap://10.0.70.1";
  	$ldapport	= 389;

  	// Get username and password from POST values
  	/*$ldapuid	= utf8_encode($_POST['u']);
  	$ldappass	= utf8_encode($_POST['p']);
    */

    $ldapuid	= strip_tags($_POST['email']);
    $ldappass	= strip_tags($_POST['password']);

  	$ldaprdn	= 'CMAKE' . '\\' . $ldapuid;

  	// connect to LDAP Server
  	$ldapconn = ldap_connect($ldapdomain,$ldapport)
  		or die ('Could not connect to Authentication Server: $ldapip');

  	if ($debug) {
  		echo "Connect result is ".$ldapconn."<br />";
  	}

  	if($ldapconn){

  		ldap_set_option($ldapconn,LDAP_OPT_PROTOCOL_VERSION,3);
  		ldap_set_option($ldapconn,LDAP_OPT_REFERRALS,0);

  		// bind to LDAP Server
  		$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

  		// verify connection
  		if($ldapbind) {
  			if ($debug) {
  				echo "LDAP Bind Successful<br />";
  			}

  			$filter = "(&(objectCategory=person)(samaccountname=".$ldapuid."))";

  			$justthese = array("ou", "sn", "givenname", "mail", "SAMAccountName","thumbnailPhoto");

  			$search = ldap_search($ldapconn, $ldapdn, $filter, $justthese);

  			$data = ldap_get_entries($ldapconn, $search);
  			if ($debug) {
  				print_r($data);
  			}

  			$givenname = $data[0]['givenname'][0]." ".$data[0]['sn'][0];
  			$useremail = $data[0]['mail'][0];

        //start sql
        $sql = "SELECT * FROM staff_users WHERE Email='".$useremail."' && status='active'";
           if($query = mysqli_query($dbc,$sql))
           {
               if(mysqli_num_rows($query) > 0)
               {
                   while($row = mysqli_fetch_array($query))
                   {
                       $dep_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM departments WHERE department_id='".$row['DepartmentCode']."'"));
                       $_SESSION['email'] = $row['Email'];
                       $_SESSION['staff_id'] = $row['EmpNo'];
                       $_SESSION['name'] = $row['Name'];
                       $_SESSION['department'] = $dep_name['department_name'];
                       $_SESSION['department_code'] = $row['DepartmentCode'];
											 $_SESSION['designation'] = $row['designation'];
                       $_SESSION['access_level'] = $row['access_level'];

                       $_SESSION['sign_in_name'] = $ldapuid;

                       if(isset($data[0]['thumbnailphoto'][0]))
                       {
                         $_SESSION['profile_picture'] =  $data[0]['thumbnailphoto'][0];
                       }


                       $time_signed_in  = date('Y/m/d H:i:s');
                       $_SESSION['time_signed_in'] = $time_signed_in;
                       $ip_address = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDE??D_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
                       $date_recorded = date('Y/m/d');


                       //insert into sign in logs

                       $sql_insert = "INSERT INTO sign_in_logs(email,name,time_signed_in,ip_address,date_recorded)
                                       VALUES

                                       ('".$_SESSION['email']."','".$_SESSION['name']."','".$_SESSION['time_signed_in']."','".$ip_address."','".$date_recorded."')
                                       ";
                       mysqli_query($dbc,$sql_insert) or die(exit("failed"));

                       //insert into activity logs
                       $browser = new Wolfcast\BrowserDetection();
                       $browser_name = $browser->getName();
                       $platform_name = $browser->getPlatformVersion();

                       $action_reference = "Logged into the system on " . $platform_name. " using ". $browser_name;
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

                   }
                   exit('success');
               }
               else
               {

                   exit('invalid');
               }
           }
        //end sql

  		}else{
  			exit('Could not bind to Authentication Server');
  		}
  	}


  /*
$ldap = ldap_connect("Cma.local");
$email = 'CMAKE' ."\\" .$_POST['email'];
if ($bind = ldap_bind($ldap, $email, $_POST['password'])) {
  // log them in!
  //fetch users
  $domain_mail = $_POST['email'].'@cma.or.ke';
  $sql = "SELECT * FROM staff_users WHERE Email='".$domain_mail."'";
     if($query = mysqli_query($dbc,$sql))
     {
         if(mysqli_num_rows($query) > 0)
         {
             while($row = mysqli_fetch_array($query))
             {
                 $_SESSION['email'] = $row['Email'];
                 $_SESSION['name'] = $row['Name'];
                 $_SESSION['department'] = $row['Department'];
                 $_SESSION['department_code'] = $row['DepartmentCode'];
                 $_SESSION['access_level'] = $row['access_level'];

                 $time_signed_in  = date('Y/m/d H:i:s');
                 $_SESSION['time_signed_in'] = $time_signed_in;
                 $ip_address = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDE??D_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
                 $date_recorded = date('Y/m/d');


                 //insert into sign in logs

                 $sql_insert = "INSERT INTO sign_in_logs(email,name,time_signed_in,ip_address,date_recorded)
                                 VALUES

                                 ('".$_SESSION['email']."','".$_SESSION['name']."','".$_SESSION['time_signed_in']."','".$ip_address."','".$date_recorded."')
                                 ";
                 mysqli_query($dbc,$sql_insert) or die(exit("failed"));

             }
             exit('success');
         }
         else
         {

             exit('invalid');
         }
     }
  //end sql
} else {
  // error message
  exit("failed");
}
*/
}


?>
