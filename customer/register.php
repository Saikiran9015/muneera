<?php
session_start();
include "../config/db.php";

if (isset($_POST['register'])) {

  $name     = $_POST['name'];
  $email    = $_POST['email'];
  $password = $_POST['password'];
  $phone    = $_POST['phone'];

  /* check duplicate email */
  $check = mysqli_query($conn,"SELECT * FROM customers WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    $error = "Email already exists";
  } else {

    mysqli_query($conn,"
      INSERT INTO customers (name, email, password, phone)
      VALUES ('$name', '$email', '$password', '$phone')
    ");

    header("Location: login.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Register</title>
  <link rel="stylesheet" href="customer.css">
</head>
<body>

<div class="auth-box">
  <h2>Customer Register</h2>

  <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

  <form method="post">
    <input name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Password" required>

    <button name="register">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
