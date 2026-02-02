<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include "../config/db.php";

if (isset($_POST['login'])) {

  $phone = mysqli_real_escape_string($conn, $_POST['phone']);

  $q = mysqli_query($conn,"
    SELECT * FROM delivery_agents 
    WHERE phone='$phone'
  ");

  if (mysqli_num_rows($q) == 1) {
    $agent = mysqli_fetch_assoc($q);

    $_SESSION['delivery_agent_id'] = $agent['id'];

    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Delivery agent not found";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Delivery Agent Login</title>
</head>
<body>



<link rel="stylesheet" href="delivery.css">

<div class="login-box">
  <h2>Delivery Agent Login</h2>

  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

  <form method="post">
    <input type="text" name="phone" placeholder="Phone Number" required>
    <button type="submit" name="login">Login</button>
      <a href="../index.php" class="back-index-btn">
  ← Back to Home
</a>

  </form>
</div>





</body>
</html>

