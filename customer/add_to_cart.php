<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];
$pid = $_GET['pid'];

/* get product's shop */
$pq = mysqli_query($conn,"SELECT shop_id FROM products WHERE id='$pid'");
$p = mysqli_fetch_assoc($pq);
$product_shop_id = $p['shop_id'];

/* check existing cart shop */
$cart_shop_q = mysqli_query($conn,"
  SELECT products.shop_id
  FROM cart
  JOIN products ON cart.product_id = products.id
  WHERE cart.customer_id='$cid'
  LIMIT 1
");

if (mysqli_num_rows($cart_shop_q) > 0) {
  $cart_shop = mysqli_fetch_assoc($cart_shop_q)['shop_id'];

  if ($cart_shop != $product_shop_id) {
    // BLOCK ADD
    header("Location: home.php?error=single_shop_only");
    exit;
  }
}

/* add to cart */
$check = mysqli_query($conn,"
  SELECT * FROM cart
  WHERE customer_id='$cid' AND product_id='$pid'
");

if (mysqli_num_rows($check) > 0) {
  mysqli_query($conn,"
    UPDATE cart
    SET quantity = quantity+1
    WHERE customer_id='$cid' AND product_id='$pid'
  ");
} else {
  mysqli_query($conn,"
    INSERT INTO cart (customer_id, product_id, quantity)
    VALUES ('$cid','$pid',1)
  ");
}

header("Location: cart.php");
exit;
