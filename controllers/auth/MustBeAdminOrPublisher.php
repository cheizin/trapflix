<?php

if(isset($_SESSION['role']))
{
  if(!$_SESSION['role'] == "admin" || !$_SESSION['role'] =="publisher")
  {
    exit("403");
  }
}
else
{
  exit("403");
}

 ?>
