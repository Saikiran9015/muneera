<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['delivery_agent_id'])) {
  exit;
}

if (isset($_POST['delivered'])) {

  $oid = $_POST['order_id'];
  $aid = $_SESSION['delivery_agent_id'];

  mysqli_query($conn,"
    UPDATE orders
    SET status='Delivered'
    WHERE id='$oid'
      AND delivery_agent_id='$aid'
  ");

  header("Location: dashboard.php");
  exit;
}
