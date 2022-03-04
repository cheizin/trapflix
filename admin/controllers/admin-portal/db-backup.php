<?php
date_default_timezone_set('Africa/Nairobi');
session_start();
include("../../controllers/setup/connect.php");
//MySQL server and database
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'Projects2018!';
$dbname = 'projects';
$tables = '*';



//Call the core function
backup_tables($dbhost, $dbuser, $dbpass, $dbname, $tables);
//insert into the backup table
$selected_location = mysqli_real_escape_string($dbc,strip_tags($_POST['select-backup-location']));
$fileName = 'db-backup-'.date("Y-m-d-h-i-s-a").'-'.'.sql';

$fileName = $selected_location.$fileName;
$backup_location = $fileName;
$backup_type = "database";
$time_recorded = date('Y/m/d H:i:s');

$sql_backup = mysqli_query($dbc,"INSERT INTO backups
                (backup_location,backup_type,triggered_by,time_recorded)
                    VALUES
            ('".$backup_location."','".$backup_type."','".$_SESSION['name']."','".$time_recorded."')"
             );

//insert into activity logs

$action_reference = "Made a database backup: " .$backup_location;
$action_name = "Database Backup";
$action_icon = "fas fa-cloud-upload-alt text-success";
$page_id = "admin-backup-link";
$time_recorded = date('Y/m/d H:i:s');

$sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                (email,action_name,action_reference,action_icon,page_id,time_recorded)
                    VALUES
            ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                    '".$action_icon."','".$page_id."','".$time_recorded."')"
             );

//Core function
function backup_tables($host, $user, $pass, $dbname, $tables = '*') {
    $link = mysqli_connect($host,$user,$pass, $dbname);

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    mysqli_query($link, "SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

    $return = '';
    //cycle through
    foreach($tables as $table)
    {
        $result = mysqli_query($link, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $num_rows = mysqli_num_rows($result);

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        $counter = 1;

        //Over tables
        for ($i = 0; $i < $num_fields; $i++)
        {   //Over rows
            while($row = mysqli_fetch_row($result))
            {
                if($counter == 1){
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                } else{
                    $return.= '(';
                }

                //Over fields
                for($j=0; $j<$num_fields; $j++)
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }

                if($num_rows == $counter){
                    $return.= ");\n";
                } else{
                    $return.= "),\n";
                }
                ++$counter;
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $selected_location = $_POST['select-backup-location'];
    $fileName = 'db-backup-'.date("Y-m-d-h-i-s-a").'-'.'.sql';

    $fileName = $selected_location.$fileName;

    $handle = fopen($fileName,'w+');
    fwrite($handle,$return);


    if(fclose($handle)){


        echo("success");
    }
    else
    {
      exit("failed");
    }

}
