<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.php');
    exit;
}

if (isset($_POST['create'])) {
    $supplier_id = (int) $_POST['supplier_id'];
    $date = $_POST['date'];
    $total = (float) $_POST['total'];
    $user_id = (int) $_SESSION['id'];

    if (empty($supplier_id) || empty($date) || $total <= 0) {
        header('Location: pembelian.php?error=invalid');
        exit;
    }

    $last = mysqli_query($conn, "SELECT id FROM purchases ORDER BY id DESC LIMIT 1");
    $lastData = mysqli_fetch_assoc($last);

    $newId = $lastData ? $lastData['id'] + 1 : 1;
    $code = 'INV-' . str_pad($newId, 4, '0', STR_PAD_LEFT);

    $query = "INSERT INTO purchases (invoice_number, supplier_id, user_id, total, paid, created_at)
              VALUES ('$code', '$supplier_id', '$user_id', '$total', 0, '$date')";

    $pembelian = mysqli_query($conn, $query);
    if (!$pembelian) {
        die('Gagal menambah pembelian: ' . mysqli_error($conn));
    }

    header('Location: pembelian.php');
    exit;
}
?>