<?php
include '../../config/database.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['create'])) {
    header('Location: pembelian.php');
    exit;
}

$supplier_id = (int) ($_POST['supplier_id'] ?? 0);
$date = trim((string) ($_POST['date'] ?? ''));
$user_id = (int) $_SESSION['id'];
$purchaseItems = $_POST['items'] ?? [];
$normalizedItems = [];
$total = 0.0;

if ($supplier_id <= 0 || $date === '' || empty($purchaseItems)) {
    header('Location: pembelian.php?error=invalid');
    exit;
}

foreach ($purchaseItems as $item) {
    $product_id = (int) ($item['product_id'] ?? 0);
    $quantity = (int) ($item['quantity'] ?? 0);
    $price = (float) ($item['price'] ?? 0);

    if ($product_id <= 0 || $quantity <= 0 || $price <= 0) {
        header('Location: pembelian.php?error=invalid');
        exit;
    }

    $productResult = mysqli_query($conn, "SELECT id, purchase_price, name FROM products WHERE id = $product_id LIMIT 1");
    $product = mysqli_fetch_assoc($productResult);

    if (!$product) {
        header('Location: pembelian.php?error=product_not_found');
        exit;
    }

    $unitPrice = (float) ($product['purchase_price'] > 0 ? $product['purchase_price'] : $price);
    $safePrice = $price > 0 ? $price : $unitPrice;
    $subtotal = $safePrice * $quantity;
    $total += $subtotal;

    $normalizedItems[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price' => $safePrice,
        'subtotal' => $subtotal,
    ];
}

$purchaseItems = $normalizedItems;

$last = mysqli_query($conn, "SELECT id FROM purchases ORDER BY id DESC LIMIT 1");
$lastData = mysqli_fetch_assoc($last);
$newId = $lastData ? ((int) $lastData['id']) + 1 : 1;
$code = 'PUR-' . str_pad($newId, 4, '0', STR_PAD_LEFT);

mysqli_begin_transaction($conn);

try {
    $stmt = mysqli_prepare($conn, "INSERT INTO purchases (invoice_number, supplier_id, user_id, total, paid, created_at) VALUES (?, ?, ?, ?, 0, ?)");
    if (!$stmt) {
        throw new Exception('Gagal menyiapkan data pembelian.');
    }

    mysqli_stmt_bind_param($stmt, 'siids', $code, $supplier_id, $user_id, $total, $date);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Gagal menyimpan pembelian.');
    }

    $purchaseId = mysqli_insert_id($conn);

    foreach ($purchaseItems as $item) {
        if (!isset($item['product_id'], $item['quantity'], $item['price'], $item['subtotal'])) {
            continue;
        }

        $detailStmt = mysqli_prepare($conn, "INSERT INTO purchase_details (purchase_id, product_id, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
        if (!$detailStmt) {
            throw new Exception('Gagal menyiapkan detail pembelian.');
        }

        mysqli_stmt_bind_param($detailStmt, 'iiddd', $purchaseId, $item['product_id'], $item['price'], $item['quantity'], $item['subtotal']);
        if (!mysqli_stmt_execute($detailStmt)) {
            throw new Exception('Gagal menyimpan detail pembelian.');
        }

        $stockUpdate = mysqli_query($conn, "UPDATE products SET stock = stock + {$item['quantity']} WHERE id = {$item['product_id']}");
        if (!$stockUpdate || mysqli_affected_rows($conn) <= 0) {
            throw new Exception('Gagal memperbarui stok produk.');
        }
    }

    mysqli_commit($conn);
    header('Location: pembelian.php?success=created');
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    header('Location: pembelian.php?error=failed');
    exit;
}
?>