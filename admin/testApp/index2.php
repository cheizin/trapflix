<?php
$data =  file_get_contents('php://imput');
$handle = fopen('validation.txt', 'w');
fwrite($handle, $data);
 ?>
