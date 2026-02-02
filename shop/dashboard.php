<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['shop_owner_id'])) {
  header("Location: login.php");
  exit;
}

$uid = $_SESSION['shop_owner_id'];
$shop = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM shops WHERE user_id='$uid'"));
$sid  = $shop['id'];

/* STATS */
$product = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM products WHERE shop_id='$sid'
"));

$total_orders = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM orders WHERE shop_id='$sid'
"));

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM orders 
  WHERE shop_id='$sid' AND status='Pending'
"));

$delivered = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM orders 
  WHERE shop_id='$sid' AND status='Delivered'
"));
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shop Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-container">

  <!-- HEADER -->
  <div class="dashboard-header">
    <h2>Hello <?= htmlspecialchars($shop['shop_name']) ?> !!</h2>
    <div class="dashboard-actions">
      <a href="orders.php">Orders</a>
      <a href="add_product.php">Add Product</a>
      <a href="add_delivery_agent.php">Delivery Agents</a>
      <a href="../logout.php">Logout</a>
    </div>
  </div>

  <!-- STATS -->
  <div class="stats">
    <div class="stat-box">Products<br><b><?= $product['total'] ?></b></div>
    <div class="stat-box">Orders<br><b><?= $total_orders['total'] ?></b></div>
    <div class="stat-box">Pending<br><b><?= $pending['total'] ?></b></div>
    <div class="stat-box">Delivered<br><b><?= $delivered['total'] ?></b></div>
  </div>
<!-- PRODUCTS --> <h3>Your Products</h3> <?php $pq = mysqli_query($conn,"SELECT * FROM products WHERE shop_id='$sid'"); while ($p = mysqli_fetch_assoc($pq)) { ?> <div class="product-card"> <?php if (!empty($p['image'])) { ?> <img src="images/products/<?= $p['image'] ?>" class="product-img"> <?php } ?> <div> <b><?= htmlspecialchars($p['name']) ?></b><br> ₹<?= $p['price'] ?> | Stock: <?= $p['stock'] ?> <?= ($p['stock'] < 5 ? "<span class='low'>LOW STOCK</span>" : "") ?> <br> <a href="edit_product.php?id=<?= $p['id'] ?>">Edit</a> | <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Delete?')">Delete</a> </div> </div> <?php } ?>
</div>
</body>
</html>

