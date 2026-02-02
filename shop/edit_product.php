<?php
include "../config/db.php";
$id = $_GET['id'];

$p = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE id='$id'"));

if (isset($_POST['update'])) {
  mysqli_query($conn,"UPDATE products SET
    name='{$_POST['name']}',
    price='{$_POST['price']}',
    stock='{$_POST['stock']}'
    WHERE id='$id'");
  header("Location: dashboard.php");
}
?>
<form method="post">
  <input name="name" value="<?php echo $p['name']; ?>">
  <input name="price" value="<?php echo $p['price']; ?>">
  <input name="stock" value="<?php echo $p['stock']; ?>">
  <button name="update">Update</button>
</form>
