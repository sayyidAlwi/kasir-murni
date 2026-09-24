<?php

session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$username = isset($_POST["username"]) && is_string($_POST["username"])
    ? trim($_POST["username"])
    : "";
$password = isset($_POST["password"]) && is_string($_POST["password"])
    ? $_POST["password"]
    : "";
$errors = [];

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
}

if (!empty($errors)) {
    $_SESSION["login_error"] = implode(" ", $errors);
    $_SESSION["old_username"] = $username;
    header("Location: ../index.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, role, password FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

$loginValid = false;
$userId = null;
$role = null;

if ($stmt->num_rows === 1) {
    $stmt->bind_result($userId, $role, $storedPassword);
    $stmt->fetch();

    // Mendukung password hash dan password lama yang masih disimpan sebagai teks biasa.
    $loginValid = password_verify($password, $storedPassword);
    if (!$loginValid) {
        $loginValid = hash_equals((string) $storedPassword, $password);
    }
}

$stmt->close();

if (!$loginValid) {
    $_SESSION["login_error"] = "Username atau password salah.";
    $_SESSION["old_username"] = $username;
    header("Location: ../index.php");
    exit;
}

$_SESSION["id"] = (int) $userId;
$_SESSION["role"] = $role;
session_regenerate_id(true);

if ($role === "admin") {
    header("Location: ../admin/index.php");
} else {
    header("Location: ../kasir/index.php");
}
exit;