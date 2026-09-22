<?php 
    include '../../config/database.php';
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        mysqli_query($conn, "UPDATE users SET name='$name', username='$username', password='$password' WHERE id = '$id'");
        header("location:pengguna.php");
    }
?>