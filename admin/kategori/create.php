<?php
    include '../../config/database.php';

    if (isset($_POST['create'])) {
        $name = $_POST['name'];

        $query = "INSERT INTO categories (name) VALUES ('$name')";
        mysqli_query($conn, $query);

        header("Location: kategori.php");
        exit();
    }

?>