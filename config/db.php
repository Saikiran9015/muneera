<?php
$host = "sql101.infinityfree.com";
$user = "if0_40846440";
$pass = "MartEmpower321";
$db   = "if0_40846440_mart";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("DB Error");
}

session_start();
?>
