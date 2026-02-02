<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

/* remove from wishlist */
if (isset($_GET['remove'])) {
  mysqli_query($conn,"
    DELETE FROM wishlist 
    WHERE id='{$_GET['remove']}' AND customer_id='$cid'
  ");
  header("Location: wishlist.php");
  exit;
}

/* remove from cart */
if (isset($_GET['remove_cart'])) {
  mysqli_query($conn,"
    DELETE FROM cart 
    WHERE product_id='{$_GET['remove_cart']}' AND customer_id='$cid'
  ");
  header("Location: wishlist.php");
  exit;
}

$items = mysqli_query($conn,"
  SELECT wishlist.id AS wid, products.*
  FROM wishlist
  JOIN products ON wishlist.product_id = products.id
  WHERE wishlist.customer_id='$cid'
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Wishlist</title>
  <link rel="stylesheet" href="wishlist.css">
</head>
<body>

<h2>My Wishlist</h2>

<?php if (mysqli_num_rows($items) == 0) { ?>
  <p>Your wishlist is empty.</p>
<?php } ?>

<div class="wishlist-container">
<?php while ($w = mysqli_fetch_assoc($items)) { ?>

  <div class="wishlist-card">

    <?php if ($w['image']) { ?>
      <img src="../shop/images/products/<?= $w['image'] ?>">
    <?php } ?>

    <h4><?= $w['name'] ?></h4>
    <p>₹<?= $w['price'] ?></p>

    <div class="actions">
      <a href="add_to_cart.php?pid=<?= $w['id'] ?>">Add to Cart</a>
      <a href="?remove_cart=<?= $w['id'] ?>" class="danger">
        Remove from Cart
      </a>
      <a href="?remove=<?= $w['wid'] ?>" class="danger">
        Remove from Wishlist
      </a>
    </div>

  </div>

<?php } ?>
</div>
<a href="home.php" class="back-btn">← Back to Shopping</a>

</body>
</html>
