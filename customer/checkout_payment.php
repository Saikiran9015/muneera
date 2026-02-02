<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

if (isset($_POST['pay'])) {

  // 1️⃣ get shop_id
  $shopQ = mysqli_query($conn,"
    SELECT products.shop_id
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.customer_id='$cid'
    LIMIT 1
  ");

  if (mysqli_num_rows($shopQ) == 0) {
    die("Cart is empty");
  }

  $shop = mysqli_fetch_assoc($shopQ);
  $shop_id = $shop['shop_id'];

  // 2️⃣ get address & payment
  $address = $_SESSION['delivery_address'] ?? 'Not Provided';
  $payment = $_POST['payment_method'];

  // 3️⃣ INSERT ORDER (FIXED)
  mysqli_query($conn,"
    INSERT INTO orders
    (customer_id, shop_id, status, delivery_address, payment_method)
    VALUES
    ('$cid','$shop_id','Pending','$address','$payment')
  ");

  $order_id = mysqli_insert_id($conn);

  // 4️⃣ order items + reduce stock
  $cart = mysqli_query($conn,"
    SELECT cart.product_id, cart.quantity, products.price
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.customer_id='$cid'
  ");

  while ($c = mysqli_fetch_assoc($cart)) {

    mysqli_query($conn,"
      INSERT INTO order_items (order_id, product_id, quantity, price)
      VALUES ('$order_id','{$c['product_id']}','{$c['quantity']}','{$c['price']}')
    ");

    mysqli_query($conn,"
      UPDATE products
      SET stock = stock - {$c['quantity']}
      WHERE id='{$c['product_id']}'
    ");
  }

  // 5️⃣ clear cart & address session
  mysqli_query($conn,"DELETE FROM cart WHERE customer_id='$cid'");
  unset($_SESSION['delivery_address']);

  header("Location: order_success.php");
  exit;
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Payment</title>
  <link rel="stylesheet" href="checkout.css">
</head>
<body>

<div class="container">
  <h3 class="step-title">Payment</h3>

<form method="post">

  <div class="card payment-card">

    <label class="payment-option">
      <input type="radio" name="payment_method" value="COD" checked>
      <div class="pay-text">
        <b>Cash on Delivery</b>
        <span>Pay when your order arrives</span>
      </div>
    </label>

    <label class="payment-option">
      <input type="radio" name="payment_method" value="UPI">
      <div class="pay-text">
        <b>UPI</b>
        <span>Pay using Google Pay, PhonePe, Paytm</span>
      </div>
    </label>

    <label class="payment-option">
      <input type="radio" name="payment_method" value="NET">
      <div class="pay-text">
        <b>Net Banking</b>
        <span>Pay directly from your bank</span>
      </div>
    </label>

  </div>

  <button name="pay" class="place-order-btn">
    PLACE ORDER
  </button>

</form>


</div>

</body>
</html>
