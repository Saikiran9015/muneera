<?php
session_start();
include "../config/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* LOGIN CHECK */
if (!isset($_SESSION['delivery_agent_id'])) {
  header("Location: login.php");
  exit;
}

$aid = $_SESSION['delivery_agent_id'];

/* FETCH ASSIGNED ORDERS */
$orders = mysqli_query($conn,"
  SELECT 
    orders.*,
    customers.name AS customer_name,
    customers.phone AS customer_phone
  FROM orders
  JOIN customers ON orders.customer_id = customers.id
  WHERE orders.delivery_agent_id='$aid'
    AND orders.status='Out for Delivery'
  ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Delivery Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-header">
  <h2>Assigned Orders</h2>
  <a href="../logout.php" class="logout-btn">Logout</a>
</div>

<?php if (mysqli_num_rows($orders) == 0) { ?>

  <!-- EMPTY STATE -->
  <div class="empty-state">
    <div class="empty-icon">📦</div>
    <h3>No Assigned Orders</h3>
    <p>
      You don’t have any deliveries right now.<br>
      Please check back later.
    </p>
  </div>

<?php } else { ?>

  <!-- ORDERS LIST -->
  <?php while ($o = mysqli_fetch_assoc($orders)) { ?>

    <div class="order-card">

      <h3>Order #<?= $o['id'] ?></h3>

      <p><b>Customer:</b> <?= htmlspecialchars($o['customer_name']) ?></p>
      <p><b>Phone:</b> <?= $o['customer_phone'] ?></p>

      <p><b>Delivery Address:</b><br>
        <?= $o['delivery_address'] ? nl2br($o['delivery_address']) : 'Not Available' ?>
      </p>

      <p><b>Payment Mode:</b>
        <?= $o['payment_method'] ?? 'Not Available' ?>
      </p>

      <h4>Products</h4>
      <ul>
        <?php
        $items = mysqli_query($conn,"
          SELECT products.name, order_items.quantity
          FROM order_items
          JOIN products ON order_items.product_id = products.id
          WHERE order_items.order_id='{$o['id']}'
        ");
        while ($i = mysqli_fetch_assoc($items)) {
          echo "<li>{$i['name']} × {$i['quantity']}</li>";
        }
        ?>
      </ul>

      <form method="post" action="update_status.php">
        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
        <button type="submit" name="delivered">Mark Delivered</button>
      </form>

    </div>

  <?php } ?>

<?php } ?>

</body>
</html>

