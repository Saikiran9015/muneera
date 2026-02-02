<?php
include "../config/db.php";
$cid = $_SESSION['customer_id'];
session_start();

$items = mysqli_query($conn,"
  SELECT products.name, products.price, cart.quantity
  FROM cart
  JOIN products ON cart.product_id = products.id
  WHERE cart.customer_id='$cid'
");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Summary</title>
  <link rel="stylesheet" href="checkout.css">
</head>
<body>

<div class="container">
  <h3 class="step-title">Order Summary</h3>

  <?php while($i = mysqli_fetch_assoc($items)) {
    $sum = $i['price'] * $i['quantity'];
    $total += $sum;
  ?>
    <div class="card">
      <b><?= $i['name'] ?></b><br>
      Qty: <?= $i['quantity'] ?><br>
      ₹<?= number_format($sum,2) ?>
    </div>
  <?php } ?>

  <div class="card">
    <b>Total: ₹<?= number_format($total,2) ?></b>
  </div>

  
    <a href="checkout_payment.php">
  <button>CONTINUE</button>
</a>

  
</div>

</body>
</html>
