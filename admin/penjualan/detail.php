<?php
session_start();
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.php');
    exit;
}

$id = isset($_GET['idPenjualan']) ? (int) $_GET['idPenjualan'] : 0;

if ($id <= 0) {
    die('ID penjualan tidak valid.');
}

$query = mysqli_query($conn, "
    SELECT t.*, u.name AS user_name, c.name AS customer_name, c.phone AS customer_phone
    FROM transactions t
    INNER JOIN users u ON u.id = t.user_id
    LEFT JOIN customers c ON c.id = t.customer_id
    WHERE t.id = $id
");

$transaction = mysqli_fetch_assoc($query);

if (!$transaction) {
    die('Data penjualan tidak ditemukan.');
}

$query_detail = mysqli_query($conn, "
    SELECT td.*, p.code AS product_code, p.name AS product_name
    FROM transaction_details td
    INNER JOIN products p ON p.id = td.product_id
    WHERE td.transaction_id = $id
    ORDER BY td.id ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <main class="main">
        <section class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="page-title">Detail Penjualan</h2>
                    <p>Informasi lengkap transaksi penjualan</p>
                </div>
                <div>
                    <a href="penjualan.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1"><?= htmlspecialchars($transaction['invoice_number']) ?></h4>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i>
                                <?= date('d F Y', strtotime($transaction['created_at'])) ?>
                            </small>
                        </div>
                        <span class="badge bg-success">Penjualan</span>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-muted">Kasir</div>
                            <div class="fw-bold"><?= htmlspecialchars($transaction['user_name']) ?></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-muted">Pelanggan</div>
                            <div class="fw-bold"><?= htmlspecialchars($transaction['customer_name'] ?? 'Umum') ?></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-muted">Telepon</div>
                            <div class="fw-bold"><?= htmlspecialchars($transaction['customer_phone'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Detail Produk</h5>
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($query_detail && mysqli_num_rows($query_detail) > 0) {
                                $no = 1;
                                while ($detail = mysqli_fetch_assoc($query_detail)) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($detail['product_name']) ?></div>
                                        <small class="text-muted">Kode: <?= htmlspecialchars($detail['product_code']) ?></small>
                                    </td>
                                    <td>Rp <?= number_format((float) $detail['price'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= (int) $detail['quantity'] ?></td>
                                    <td class="text-end fw-bold">Rp <?= number_format((float) $detail['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada produk pada transaksi ini.</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Penjualan</span>
                        <strong>Rp <?= number_format((float) $transaction['total'], 0, ',', '.') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Dibayar</span>
                        <span>Rp <?= number_format((float) $transaction['paid'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kembalian</span>
                        <span>Rp <?= number_format((float) $transaction['change_amount'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
