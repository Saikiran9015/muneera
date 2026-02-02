<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];
$pid = $_GET['pid'];

/* avoid duplicates */
$check = mysqli_query($conn,"
  SELECT * FROM wishlist 
  WHERE customer_id='$cid' AND product_id='$pid'
");

if (mysqli_num_rows($check) == 0) {
  mysqli_query($conn,"
    INSERT INTO wishlist (customer_id, product_id)
    VALUES ('$cid','$pid')
  ");
}

header("Location: wishlist.php");
exit;
