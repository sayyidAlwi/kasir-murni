<?php 
    include '../../config/database.php';

    $id = $_GET['idTransaksi'];

$query = mysqli_query($conn, "
    SELECT
        transactions.*,
        users.name AS cashier,
        customers.name AS customer

    FROM transactions

    INNER JOIN users
        ON transactions.user_id = users.id

    LEFT JOIN customers
        ON transactions.customer_id = customers.id

    WHERE transactions.id = '$id'
");

$transaction = mysqli_fetch_assoc($query);

$query_detail = mysqli_query($conn, "
    SELECT
        transaction_details.*,
        products.code,
        products.name AS product_name

    FROM transaction_details

    INNER JOIN products
        ON transaction_details.product_id = products.id

    WHERE transaction_details.transaction_id = '$id'
");
?>