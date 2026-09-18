<?php 
    include '../../config/database.php';

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];

        $query = mysqli_query($conn, "UPDATE categories SET name='$name' WHERE id='$id'");
        header("Location: kategori.php");
        exit();
    }
?>