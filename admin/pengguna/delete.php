<?php 
    include '../../config/database.php';
    $id = (int)$_GET['idpengguna'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    header("location:pengguna.php");
?>