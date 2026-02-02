<?php
session_start();
include "../config/db.php";

if (isset($_SESSION['shop_owner_id'])) {
  header("Location: dashboard.php");
  exit;
}

if (isset($_POST['register'])) {
  $shop_name  = $_POST['shop_name'];
  $owner_name = $_POST['owner_name'];
  $email      = $_POST['email'];
  $password   = $_POST['password'];
  $phone      = $_POST['phone'];
  $address    = $_POST['address'];
  $city       = $_POST['city'];
  $pincode    = $_POST['pincode'];

  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    $error = "Email already registered";
  } else {
    mysqli_query($conn,"
      INSERT INTO users (name,email,password,role)
      VALUES ('$owner_name','$email','$password','shopOwner')
    ");

    $uid = mysqli_insert_id($conn);

    mysqli_query($conn,"
      INSERT INTO shops (user_id,shop_name,owner_name,phone,address,city,pincode)
      VALUES ('$uid','$shop_name','$owner_name','$phone','$address','$city','$pincode')
    ");

    header("Location: login.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register Your Shop</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="page-bg">

  <div class="register-card">

    <h2>Register Your Shop</h2>
    <p class="subtitle">Join EmpowerMart & grow your business</p>

    <?php if (isset($error)) { ?>
      <p class="error"><?= $error ?></p>
    <?php } ?>

    <form method="post">
      <input name="shop_name" placeholder="Shop Name" required>
      <input name="owner_name" placeholder="Owner Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input name="phone" placeholder="Phone Number" required>
      <textarea name="address" placeholder="Shop Address" required></textarea>
      <input name="city" placeholder="City" required>
      <input name="pincode" placeholder="Pincode" required>

      <button name="register">Register Shop</button>
    </form>

    <p class="login-link">
      Already registered?
      <a href="login.php">Login here</a>
    </p>

  </div>

</div>

</body>
</html>
</html>