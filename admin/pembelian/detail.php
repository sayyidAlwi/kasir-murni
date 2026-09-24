<?php
session_start();
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.php');
    exit;
}

$id = isset($_GET['idPembelian']) ? (int) $_GET['idPembelian'] : 0;

if ($id <= 0) {
    die('ID pembelian tidak valid.');
}

$query = mysqli_query($conn, "
    SELECT p.*, s.name AS supplier_name, s.phone AS supplier_phone, s.address AS supplier_address, u.name AS user_name
    FROM purchases p
    INNER JOIN suppliers s ON s.id = p.supplier_id
    INNER JOIN users u ON u.id = p.user_id
    WHERE p.id = $id
");

$purchase = mysqli_fetch_assoc($query);

if (!$purchase) {
    die('Data pembelian tidak ditemukan.');
}

$query_detail = mysqli_query($conn, "
    SELECT pd.*, pr.code AS product_code, pr.name AS product_name
    FROM purchase_details pd
    INNER JOIN products pr ON pr.id = pd.product_id
    WHERE pd.purchase_id = $id
");
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <main class="main">
        <section class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="page-title">Detail Pembelian</h2>
                    <p>Informasi lengkap transaksi pembelian</p>
                </div>
                <div>
                    <a href="pembelian.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1"><?= htmlspecialchars($purchase['invoice_number']) ?></h4>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i>
                                <?= date('d F Y', strtotime($purchase['created_at'])) ?>
                            </small>
                        </div>
                        <span class="badge bg-success">Pembelian</span>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-muted">Supplier</div>
                            <div class="fw-bold"><?= htmlspecialchars($purchase['supplier_name']) ?></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-muted">Petugas</div>
                            <div class="fw-bold"><?= htmlspecialchars($purchase['user_name']) ?></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-muted">Telepon</div>
                            <div class="fw-bold"><?= htmlspecialchars($purchase['supplier_phone'] ?? '-') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-muted">Alamat Supplier</div>
                            <div class="fw-bold"><?= htmlspecialchars($purchase['supplier_address'] ?? '-') ?></div>
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
                                <th>Harga Beli</th>
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
                                    <td colspan="5" class="text-center">Belum ada produk pada pembelian ini.</td>
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
                        <span>Total Pembelian</span>
                        <strong>Rp <?= number_format((float) $purchase['total'], 0, ',', '.') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Dibayar</span>
                        <span>Rp <?= number_format((float) $purchase['paid'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold border-top pt-2">
                        <span>Sisa</span>
                        <span>Rp <?= number_format((float) ($purchase['total'] - $purchase['paid']), 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
