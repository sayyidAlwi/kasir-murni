<?php

require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../registrasi.php");
    exit;
}


if (isset($_POST['regist'])) {


    $nama = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $query = mysqli_query($conn, "INSERT INTO users (name, username, password) VALUES ('$nama', '$username', '$password')");
    header("location:../index.php");
}

if ($username === "") {
    $errors[] = "Username wajib diisi.";
} elseif (strlen($username) > 50) {
    $errors[] = "Username maksimal 50 karakter.";
}

if ($password === "") {
    $errors[] = "Password wajib diisi.";
} elseif (strlen($password) < 8) {
    $errors[] = "Password minimal 8 karakter.";
} elseif (strlen($password) > 255) {
    $errors[] = "Password maksimal 255 karakter.";
} elseif ($password !== $confirmPassword) {
    $errors[] = 'Konfirmasi password tidak cocok';
}


if (!empty($errors)) {
    $_SESSION["login_error"] = implode(" ", $errors);
    $_SESSION["old_username"] = $username;
    header("Location: ../registrasi.php");
    exit;
}

?>