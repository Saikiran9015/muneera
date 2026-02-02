<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['shop_owner_id'])) {
  header("Location: login.php");
  exit;
}

$uid = $_SESSION['shop_owner_id'];
$shop = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM shops WHERE user_id='$uid'"));
$sid = $shop['id'];

if (isset($_POST['add_agent'])) {
  $name = $_POST['name'];
  $phone = $_POST['phone'];

  mysqli_query($conn,"
    INSERT INTO delivery_agents (shop_id, name, phone)
    VALUES ('$sid','$name','$phone')
  ");

  header("Location: add_delivery_agent.php");
  exit;
}

$agents = mysqli_query($conn,"SELECT * FROM delivery_agents WHERE shop_id='$sid'");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Delivery Agents</title>
  <link rel="stylesheet" href="delivery_agents.css">
</head>
<body>

<div class="agent-container">

  <div class="agent-header">
    <h2>Delivery Agents</h2>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
  </div>

  <div class="agent-card">
    <h3>Add Delivery Agent</h3>

    <form method="post">
      <input name="name" placeholder="Agent Name" required>
      <input name="phone" placeholder="Phone Number" required>
      <button name="add_agent">Add Agent</button>
    </form>
  </div>

  <div class="agent-card">
    <h3>Existing Agents</h3>

    <?php if(mysqli_num_rows($agents)==0){ ?>
      <p class="empty">No delivery agents added yet.</p>
    <?php } ?>

    <ul class="agent-list">
      <?php while($a = mysqli_fetch_assoc($agents)) { ?>
        <li>
          <span><?= htmlspecialchars($a['name']) ?></span>
          <span><?= htmlspecialchars($a['phone']) ?></span>
        </li>
      <?php } ?>
    </ul>
  </div>

</div>

</body>
</html>

