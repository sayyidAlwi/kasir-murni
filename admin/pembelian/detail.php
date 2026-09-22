<?php 
    include '../../config/database.php';

    $id = $_GET['idPembelian'];
    $query = mysqli_query($conn, "SELECT purchases.id, purchases.code, purchases.total, purchases.created_at,
    suppliers.name AS suppliers, supliers.phone, suppliers.address, users.username AS user_name FROM purchases
    INNER JOIN suppliers ON suppliers.id = purchases.supplier_id INNER JOIN users ON users.id = purchases.user_id WHERE purchases.id = '$id'");

    $pemebelian = mysqli_fetch_assoc($query);

    if (!$pemebelian) {
        die("Data pembelian tidak ditemukan.");
    }
?>