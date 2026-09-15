<?php 
include "../../config/database.php";

    if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $purchase_price = (float)$_POST['purchase_price'];
    $selling_price = (float)$_POST['selling_price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category'];
    
    mysqli_query($conn, "UPDATE products SET name='$name', purchase_price='$purchase_price', selling_price='$selling_price', stock='$stock', category_id='$category_id' WHERE id = '$id'");
    header("location:produk.php");
    
    }
?>