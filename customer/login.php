<?php
include "../config/db.php";
session_start();

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $res = mysqli_query($conn, "SELECT * FROM customers 
    WHERE email='$email' AND password='$password'");

  if (mysqli_num_rows($res) == 1) {
    $row = mysqli_fetch_assoc($res);
    $_SESSION['customer_id'] = $row['id'];
    $_SESSION['customer_name'] = $row['name'];
    header("Location: home.php");
  } else {
    $error = "Invalid login details";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Login</title>
  <link rel="stylesheet" href="customer.css">
</head>
<body>

<div class="auth-box">
  <h2>Customer Login</h2>

  <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
  </form>

  <p>No account? <a href="register.php">Register</a></p>
</div>

</body>
</html>
