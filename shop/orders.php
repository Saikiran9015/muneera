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

/* DELIVERY AGENTS */
$agents = mysqli_query($conn,"SELECT * FROM delivery_agents WHERE shop_id='$sid'");

/* ASSIGN AGENT */
if (isset($_POST['assign_agent'])) {
  mysqli_query($conn,"
    UPDATE orders
    SET delivery_agent_id='{$_POST['agent_id']}',
        status='Out for Delivery'
    WHERE id='{$_POST['order_id']}' AND shop_id='$sid'
  ");
  header("Location: orders.php");
  exit;
}

/* ORDERS */
$orders = mysqli_query($conn,"
  SELECT orders.*, customers.name customer_name
  FROM orders
  JOIN customers ON orders.customer_id = customers.id
  WHERE orders.shop_id='$sid'
  ORDER BY orders.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Orders</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-container">

<div class="dashboard-header">
  <h2>Customer Orders</h2>
  <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

<?php while ($o = mysqli_fetch_assoc($orders)) { ?>
<div class="order-box">

  <h4>
    Order #<?= $o['id'] ?> |
    <?= htmlspecialchars($o['customer_name']) ?> |
    <span><?= $o['status'] ?></span>
  </h4>

  <!-- ITEMS -->
  <table>
    <tr><th>Product</th><th>Qty</th><th>Price</th></tr>
    <?php
    $items = mysqli_query($conn,"
      SELECT products.name, order_items.quantity, order_items.price
      FROM order_items
      JOIN products ON order_items.product_id = products.id
      WHERE order_items.order_id='{$o['id']}'
        AND products.shop_id='$sid'
    ");
    while ($i = mysqli_fetch_assoc($items)) {
      echo "<tr>
        <td>{$i['name']}</td>
        <td>{$i['quantity']}</td>
        <td>₹{$i['price']}</td>
      </tr>";
    }
    ?>
  </table>

  <!-- ASSIGN -->
  <?php if ($o['status'] == 'Pending') { ?>
  <form method="post" class="assign-form">
    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
    <select name="agent_id" required>
      <option value="">Assign Delivery Agent</option>
      <?php mysqli_data_seek($agents,0);
      while ($a = mysqli_fetch_assoc($agents)) { ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
      <?php } ?>
    </select>
    <button name="assign_agent">Assign</button>
  </form>
  <?php } else { ?>
    <p><b><?= $o['status'] ?></b></p>
  <?php } ?>

</div>
<?php } ?>

</div>
</body>
</html>

