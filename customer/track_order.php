<?php
session_start();
include "../config/db.php";

/* LOGIN CHECK */
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];
$oid = $_GET['order_id'] ?? 0;

/* FETCH ORDER */
$order = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT * FROM orders
  WHERE id='$oid' AND customer_id='$cid'
"));

if (!$order) {
  die("Order not found");
}

$status = $order['status'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Track Order</title>
  <link rel="stylesheet" href="track.css">
</head>
<body>

<div class="track-container">

  <div class="track-header">
    <h2>Track Order #<?= $order['id'] ?></h2>
   
  </div>

  <?php if ($status === 'Cancelled') { ?>

    <p class="cancelled-text">❌ Order Cancelled</p>

  <?php } else { ?>

  <!-- TIMELINE -->
  <div class="timeline">

    <div class="step <?= in_array($status, ['Pending','Confirmed','Out for Delivery','Delivered']) ? 'active' : '' ?>">
      Order Placed
    </div>

    <div class="step <?= in_array($status, ['Confirmed','Out for Delivery','Delivered']) ? 'active' : '' ?>">
      Confirmed
    </div>

    <div class="step <?= in_array($status, ['Out for Delivery','Delivered']) ? 'active' : '' ?>">
      Out for Delivery
    </div>

    <div class="step <?= $status === 'Delivered' ? 'active' : '' ?>">
      Delivered
    </div>

  </div>

  <?php } ?>

  <div class="status-box">
    <b>Current Status:</b>
    <span><?= htmlspecialchars($status) ?></span>
  </div>

  <div class="btn-group">
  <a href="orders.php" class="btn-outline">← Back to Orders</a>
  <a href="home.php" class="btn-primary">🛒 Back to Shopping</a>
</div>


</div>

</body>
</html>
