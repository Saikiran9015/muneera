<?php if (isset($_GET['error']) && $_GET['error']=="single_shop_only") { ?>
  <p style="color:red; text-align:center">
    You can order from only one shop at a time. Please clear cart first.
  </p>
<?php } ?>
<?php
include "../config/db.php";
session_start();

/* login check */
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

/* get search values */
$search = $_GET['search'] ?? '';
$city   = $_GET['city'] ?? '';

/* base query */
$query = "
SELECT 
  products.id AS product_id,
  products.name AS product_name,
  products.price,
  products.image,
  shops.shop_name,
  shops.city
FROM products
JOIN shops ON products.shop_id = shops.id
WHERE 1
";

/* search filter */
if ($search != '') {
  $query .= " AND products.name LIKE '%$search%'";
}

/* location filter */
if ($city != '') {
  $query .= " AND shops.city = '$city'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Home</title>
  <link rel="stylesheet" href="home.css">
</head>
<body>

<!-- TOP NAV -->
<div class="top-nav">
  <span>Welcome, <?php echo $_SESSION['customer_name']; ?></span>

  <div class="nav-links">
    <a href="cart.php">Cart</a>
    <a href="wishlist.php">Wishlist</a>
    <a href="orders.php">Previous Orders</a>
    <a href="../logout.php">Logout</a>
  </div>
</div>

<!-- SEARCH -->
<div class="search-box">
  <form method="get">
    <input type="text" name="search" placeholder="Search product" value="<?php echo $search; ?>">

    <select name="city">
      <option value="">Select Location</option>
      <?php
      $cities = mysqli_query($conn,"SELECT DISTINCT city FROM shops");
      while($c = mysqli_fetch_assoc($cities)) {
        $sel = ($city == $c['city']) ? "selected" : "";
        echo "<option $sel>{$c['city']}</option>";
      }
      ?>
    </select>

    <button type="submit">Search</button>
  </form>
</div>

<!-- RESULTS -->
<div class="results">
  <?php if(mysqli_num_rows($result) == 0) { ?>
    <p>No products found.</p>
  <?php } ?>

  <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="product-card">
      <?php if ($row['image']) { ?>
        <img src="../shop/images/products/<?php echo $row['image']; ?>">
      <?php } ?>

      <h4><?php echo $row['product_name']; ?></h4>
      <p>₹<?php echo $row['price']; ?></p>
      <p><b><?php echo $row['shop_name']; ?></b></p>
      <p><?php echo $row['city']; ?></p>

      <div class="actions">
       <a href="add_to_cart.php?pid=<?php echo $row['product_id']; ?>">Add to Cart</a>

       <a href="add_to_wishlist.php?pid=<?= $row['product_id'] ?>">Wishlist</a>

      </div>
    </div>
  <?php } ?>
   

</div>

</body>
</html>
