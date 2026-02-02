<?php
include "../config/db.php";
session_start();
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $pass  = $_POST['password'];

  $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$pass' AND role='shopOwner'");
  if (mysqli_num_rows($q) == 1) {
    $u = mysqli_fetch_assoc($q);
    $_SESSION['shop_owner_id'] = $u['id'];
    header("Location: dashboard.php");
  } else {
    $error = "Invalid login details";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shop Login</title>
  <link rel="stylesheet" href="shop.css">
</head>
<body>
<div class="box">
  <h2>Shop Owner Login</h2>
  <?php if(isset($_GET['registered'])) echo "<p class='success'>Registration successful. Login now.</p>"; ?>
  <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
  </form>
</div>
</body>
</html>
