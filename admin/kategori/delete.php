<?php 
    include '../../config/database.php';

    if (isset($_POST['delete'])) {
        $id = $_GET['idKategori'];

        $query = mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
        header("Location: kategori.php");
        exit();
    }
?>