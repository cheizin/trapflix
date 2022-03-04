<?php
session_start();
include("../../controllers/setup/connect.php");

  ini_set('max_execution_time', 600);
  ini_set('memory_limit','1024M');

  date_default_timezone_set('Africa/Nairobi');

  $timestamp = date("m-d-Y-h-i-s-a");


  $source = 'C:/Apache24/htdocs/p/';
  $fileName = "p-".$timestamp.".zip";

  $selected_location = mysqli_real_escape_string($dbc,strip_tags($_POST['system-backup-location']));
  $destination = $selected_location.$fileName;

  zipData($source,$destination);

  //insert into the backup table
  $real_file_name = 'db-backup-'.date("Y-m-d-h-i-s-a").'-'.(md5(implode(',',$tables))).'.sql';
  $selected_location = mysqli_real_escape_string($dbc,strip_tags($_POST['select-backup-location'])).$real_file_name;
  $backup_type = "system";
  $time_recorded = date('Y/m/d H:i:s');

  $sql_backup = mysqli_query($dbc,"INSERT INTO backups
                  (backup_location,backup_type,triggered_by,time_recorded)
                      VALUES
              ('".$destination."','".$backup_type."','".$_SESSION['name']."','".$time_recorded."')"
               );

  //insert into activity logs
  $action_reference = "Made a system backup: " .$destination;
  $action_name = "System Backup";
  $action_icon = "fal fa-laptop-code text-success";
  $page_id = "admin-backup-link";
  $time_recorded = date('Y/m/d H:i:s');

  $sql_log = mysqli_query($dbc,"INSERT INTO activity_logs
                  (email,action_name,action_reference,action_icon,page_id,time_recorded)
                      VALUES
              ('".$_SESSION['email']."','".$action_name."','".$action_reference."',
                      '".$action_icon."','".$page_id."','".$time_recorded."')"
               ) or die (mysqli_error($dbc));

  function zipData($source, $destination) {
  if (extension_loaded('zip')) {
  if (file_exists($source)) {
  $zip = new ZipArchive();
  if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
  $source = realpath($source);
  if (is_dir($source)) {
  $iterator = new RecursiveDirectoryIterator($source);
  // skip dot files while iterating
  $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
  $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
  foreach ($files as $file) {
  $file = realpath($file);
  if (is_dir($file)) {
  $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
  } else if (is_file($file)) {
  $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
  }
  }
  } else if (is_file($source)) {
  $zip->addFromString(basename($source), file_get_contents($source));
  }
  }
  if( $zip->close())
  {
    echo "success";
  }
  else
  {
    echo "failed";
  }
  }
  }
  return false;
  }



?>
