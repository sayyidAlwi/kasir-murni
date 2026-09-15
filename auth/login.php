<?php

require_once "../config/database.php";

$username = $_POST["username"];
$password = $_POST["password"];


$login = mysqli_query($conn, "SELECT * FROM users where username = '$username' AND password = '$password' AND role");
$cek = mysqli_num_rows($login);


if ($cek > 0) {
    $data = mysqli_fetch_assoc($login);
    if ($data["role"] == 'admin') {
        session_start();
        $_SESSION['id'] = $data['id'];
        $_SESSION['role'] = $data['role'];
        header("location:../admin/index.php");
    } else if ($data["role"] == 'kasir') {
        session_start();
        $_SESSION['id'] = $data['id'];
        $_SESSION['role'] = $data['role'];
        header("location:../admin/index.php");
    } 
} else {
    header("location:../index.php");
}
?>