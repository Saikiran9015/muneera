<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

$orders = mysqli_query($conn, "
  SELECT 
    orders.id,
    orders.status,
    orders.created_at,
    shops.shop_name
  FROM orders
  JOIN shops ON orders.shop_id = shops.id
  WHERE orders.customer_id = '$cid'
  ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Orders</title>
  <link rel="stylesheet" href="orders.css">
</head>
<body>

<div class="orders-page">

  <h2>My Orders</h2>

  <a href="home.php" class="back-btn">← Back to Shopping</a>

  <?php if (mysqli_num_rows($orders) == 0) { ?>
    <p class="empty">No previous orders found.</p>
  <?php } ?>

  <?php while ($o = mysqli_fetch_assoc($orders)) { ?>

    <div class="order-card">

      <!-- ORDER HEADER -->
      <div class="order-header">
        <div>
          <h3>Order #<?= $o['id'] ?></h3>
          <p><?= htmlspecialchars($o['shop_name']) ?></p>
        </div>

        <div class="order-meta">
          <span class="status <?= strtolower($o['status']) ?>">
            <?= $o['status'] ?>
          </span>
          <small><?= $o['created_at'] ?></small>
        </div>
      </div>

      <!-- ORDER ITEMS -->
      <table class="order-table">
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
        </tr>

        <?php
        $items = mysqli_query($conn, "
          SELECT products.name, order_items.quantity, order_items.price
          FROM order_items
          JOIN products ON order_items.product_id = products.id
          WHERE order_items.order_id = '{$o['id']}'
        ");

        while ($i = mysqli_fetch_assoc($items)) {
          echo "
          <tr>
            <td>{$i['name']}</td>
            <td>{$i['quantity']}</td>
            <td>₹{$i['price']}</td>
          </tr>";
        }
        ?>
      </table>

      <!-- ACTION -->
      <div class="order-action">
        <a href="track_order.php?order_id=<?= $o['id'] ?>" class="track-btn">
          Track Order
        </a>
      </div>

    </div>

  <?php } ?>

</div>

</body>
</html>

