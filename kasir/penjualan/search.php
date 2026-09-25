<?php

include '../../config/database.php';

$search = $_GET['search'] ?? '';
$dateFrom = $_GET['dateFrom'] ?? '';
$dateTo = $_GET['dateTo'] ?? '';

$where = [];

if ($search != '') {
    $search = mysqli_real_escape_string($conn, $search);

    $where[] = "(transactions.invoice_number LIKE '%$search%'
                OR users.name LIKE '%$search%'
                OR customers.name LIKE '%$search%')";
}

if ($dateFrom != '' && $dateTo != '') {
    $where[] = "DATE(transactions.created_at) 
                BETWEEN '$start' AND '$end'";
}
 header("location: penjualan.php?" . http_build_query(['search' => $search, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo]));

?>