<?php
ob_start(); // Turns on output buffering
session_start();

date_default_timezone_set("Europe/London");

try {
    $con = new PDO("mysql:dbname=trapflix_trap1;host=localhost", "trapflix_trap1", "9000@Kenya");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}
