<?php
include "../../config/database.php";

if (isset($_POST['create'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $purchase_price = (float)$_POST['purchase_price'];
    $selling_price = (float)$_POST['selling_price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category'];

    if ($name === '' || $category_id <= 0 || $purchase_price < 0 || $selling_price < 0 || $stock < 0) {
        header("location:produk.php");
        exit;
    }

    $last = mysqli_query($conn, "SELECT id FROM products ORDER BY id DESC LIMIT 1");
    $lastData = mysqli_fetch_assoc($last);

    if ($lastData) {
        $newId = $lastData['id'] + 1;
    } else {
        $newId = 1;
    }

    $code = "PRD-" . str_pad($newId, 4, '0', STR_PAD_LEFT);

    $query = "INSERT INTO products (code, category_id, name, purchase_price, selling_price, stock)
              VALUES ('$code', '$category_id', '$name', '$purchase_price', '$selling_price', '$stock')";

    $produk = mysqli_query($conn, $query);

    if (!$produk) {
        die("Gagal menambah produk: " . mysqli_error($conn));
    }

    header("location:produk.php");
    exit;
}
?>