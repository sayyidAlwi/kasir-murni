<?php 
    include '../../config/database.php';
    $id = (int)$_GET['idPelanggan'];
    mysqli_query($conn, "DELETE FROM customers WHERE id = '$id'");
    header("location:pelanggan.php");
?>