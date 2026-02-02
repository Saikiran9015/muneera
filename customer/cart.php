<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

/* INCREASE / DECREASE */
if (isset($_GET['inc'])) {
  mysqli_query($conn,"UPDATE cart SET quantity = quantity+1 WHERE id='".$_GET['inc']."'");
}
if (isset($_GET['dec'])) {
  mysqli_query($conn,"UPDATE cart SET quantity = GREATEST(quantity-1,1) WHERE id='".$_GET['dec']."'");
}

/* REMOVE */
if (isset($_GET['remove'])) {
  mysqli_query($conn,"DELETE FROM cart WHERE id='".$_GET['remove']."'");
}

/* FETCH CART */
$items = mysqli_query($conn,"
  SELECT cart.id AS cart_id, cart.quantity,
         products.name, products.price, products.category,
         shops.shop_name
  FROM cart
  JOIN products ON cart.product_id = products.id
  JOIN shops ON products.shop_id = shops.id
  WHERE cart.customer_id='$cid'
");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="cart.css">
</head>
<body>

<h2 class="page-title">Shopping Cart</h2>


<div class="cart-container">

<?php while($c = mysqli_fetch_assoc($items)) {
  $item_total = $c['price'] * $c['quantity'];
  $total += $item_total;
?>
  <div class="cart-card">
    <div class="cart-top">
      <div>
        <h3><?= $c['name'] ?></h3>
        <p><?= $c['shop_name'] ?></p>
        <span class="category"><?= $c['category'] ?></span>
      </div>

      <a class="delete" href="?remove=<?= $c['cart_id'] ?>">🗑</a>
    </div>

    <div class="cart-bottom">
      <div class="qty">
        <a href="?dec=<?= $c['cart_id'] ?>">−</a>
        <span><?= $c['quantity'] ?></span>
        <a href="?inc=<?= $c['cart_id'] ?>">+</a>
      </div>

      <div class="price">
        ₹<?= number_format($item_total,2) ?>
      </div>
    </div>
  </div>
<?php } ?>

</div>

<div class="cart-total">
  <span>Total Amount:</span>
  <b>₹<?= number_format($total,2) ?></b>
</div>


<a href="checkout_address.php" class="place-btn">Place Order</a><br/><br/><br/>
<a href="home.php" class="back-btn bottom-back"> ← Back to Shopping</a>
</body>
</html>
