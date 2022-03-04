<?php

require_once('UserIsAuthenticated.php');

if(isset($_SESSION['access_level']))
{
    if($_SESSION['access_level']!='admin')
    {
        exit('403');
    }
}


?>