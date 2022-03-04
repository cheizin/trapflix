<?php
session_start();
include("../../controllers/setup/connect.php");
  if($_SESSION['access_level']!='admin')
  {
    exit("unauthorized");
  }

  $download_id = mysqli_real_escape_string($dbc,strip_tags($_POST['download_id']));


  //select filename

  $file_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT backup_location FROM backups WHERE id='".$download_id."'"));

  // File to download.
  $file = $file_row['backup_location'];

  // Maximum size of chunks (in bytes).
  $maxRead = 1 * 1024 * 1024; // 1MB

  // Give a nice name to your download.
  $fileName = $file;

  // Open a file in read mode.
  $fh = fopen($file, 'r');

  // These headers will force download on browser,
  // and set the custom file name for the download, respectively.
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . $fileName . '"');

  // Run this until we have read the whole file.
  // feof (eof means "end of file") returns `true` when the handler
  // has reached the end of file.
  while (!feof($fh)) {
      // Read and output the next chunk.
      echo fread($fh, $maxRead);

      // Flush the output buffer to free memory.
      ob_flush();
  }

  // Exit to make sure not to output anything else.
  exit;


 ?>
