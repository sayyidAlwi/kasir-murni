<?php 
    include '../../config/database.php';

    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];


        mysqli_query($conn, "INSERT INTO users (name,username,password,role) VALUES ('$name','$username','$password','$role')");
        header("location:pengguna.php");
    }
?>