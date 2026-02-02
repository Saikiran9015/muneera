<?php
include "../config/db.php";
if (!isset($_SESSION['shop_owner_id'])) {
  header("Location: ../index.php");
}

$uid = $_SESSION['shop_owner_id'];
$shop = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM shops WHERE user_id='$uid'"));
$sid = $shop['id'];

if (isset($_POST['add_product'])) {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $desc = $_POST['description'];

 $img_name = $_FILES['image']['name'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

  $allowed = ['jpg','jpeg','png','webp'];

  if (!in_array($ext, $allowed)) {
    die("Only JPG, PNG, WEBP allowed");
  }

  $new_name = time()."_".$img_name;
  $path = "images/products/".$new_name;

  move_uploaded_file($tmp_name, $path);

  mysqli_query($conn, "INSERT INTO products 
    (shop_id,name,category,price,stock,description,image)
    VALUES ('$sid','$name','$category','$price','$stock','$desc','$new_name')");

  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Product</title>
  <link rel="stylesheet" href="add_product.css">
</head>
<body>
<div class="form-container">
  <h2>Add Product</h2>

<form method="post" enctype="multipart/form-data">
    <label>Product Image</label>
<input type="file" name="image" accept="image/*" required>


    <input type="text" name="name" placeholder="Product Name" required>

    <select name="category" required>
      <option value="">Select Category</option>
      <option>Fruits</option>
      <option>Vegetables</option>
      <option>Groceries</option>
    </select>

    <input type="number" name="price" placeholder="Price" required>
    <input type="number" name="stock" placeholder="Stock Quantity" required>

    <textarea name="description" placeholder="Product Description"></textarea>

    <button name="add_product">Add Product</button>
  </form>

  <div class="back-link">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</div>

</body>
</html>
