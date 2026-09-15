<?php 
    include '../../config/database.php';

    $id = (int)$_GET['idPelanggan'];
    mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
    header("location:produk.php");
?>