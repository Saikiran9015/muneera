<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

if (isset($_POST['save'])) {

  $name     = $_POST['name'];
  $phone    = $_POST['phone'];
  $address  = $_POST['address'];
  $city     = $_POST['city'];
  $pincode  = $_POST['pincode'];

  // save in table (optional but fine)
  mysqli_query($conn,"
    INSERT INTO customer_addresses
    (customer_id, name, phone, address, city, pincode)
    VALUES
    ('$cid','$name','$phone','$address','$city','$pincode')
  ");

  // ✅ IMPORTANT: SAVE ADDRESS IN SESSION
  $_SESSION['delivery_address'] =
    $name."\n".
    $phone."\n".
    $address.", ".$city." - ".$pincode;

  header("Location: checkout_summary.php");
  exit;
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Address</title>
  <link rel="stylesheet" href="checkout.css">
</head>
<body>

<div class="container">
  <h3 class="step-title">Address</h3>

  <div class="card">
    <form method="post">
      <input name="name" placeholder="Full Name" required>
      <input name="phone" placeholder="Phone Number" required>
      <textarea name="address" placeholder="Address" required></textarea>
      <input name="city" placeholder="City" required>
      <input name="pincode" placeholder="Pincode" required>
        <button name="save">SAVE & CONTINUE</button>
    </form>
  </div>
</div>

</body>
</html>
