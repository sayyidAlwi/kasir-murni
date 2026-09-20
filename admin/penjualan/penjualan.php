<?php
session_start();
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <main class="main">
        <section class="content">
            <div class="mb-4">
                <h2 class="page-title">
                    Data Produk
                </h2>
                <p>Kelola dan pantau transaks</p>
            </div>

            <div class="row g-3 mb-4">

                <!-- Penjualan -->

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-title">
                                    Penjualan Hari Ini
                                </div>
                                <p class="stat-value">
                                    Rp 2.450.000
                                </p>
                                <div class="stat-change text-success">
                                    <i class="bi bi-arrow-up"></i>
                                    12.5% dari kemarin
                                </div>
                            </div>
                            <div class="stat-icon bg-primary-subtle text-primary">
                                <i class="bi bi-cart-check"></i>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Pendapatan -->

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-title">
                                    Pendapatan
                                </div>
                                <p class="stat-value">
                                    Rp 18,5 Jt
                                </p>
                                <div class="stat-change text-success">
                                    <i class="bi bi-arrow-up"></i>
                                    8.2% bulan ini
                                </div>
                            </div>
                            <div class="stat-icon bg-success-subtle text-success">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pelanggan -->

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-title">
                                    Pelanggan
                                </div>
                                <p class="stat-value">
                                    <?php
                                    $pelanggan = mysqli_query($conn, "SELECT * FROM customers");
                                    echo mysqli_num_rows($pelanggan);
                                    ?>
                                </p>
                                <div class="stat-change text-success">
                                    <i class="bi bi-arrow-up"></i>
                                    5.4% bulan ini
                                </div>

                            </div>
                            <div class="stat-icon bg-info-subtle text-info">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>

                <div class="filter-card">

                    <!-- SEARCH BAR -->
                    <div class="search-wrapper">

                        <i class="bi bi-search search-icon"></i>

                        <input type="text" id="searchInput" class="search-input"
                            placeholder="Cari transaksi atau kasir..." autocomplete="off">

                    </div>


                    <!-- FILTER TANGGAL -->
                    <div class="date-filter">

                        <div class="date-group">

                            <label for="dateFrom">
                                Dari
                            </label>

                            <input type="date" id="dateFrom">

                        </div>


                        <div class="date-group">

                            <label for="dateTo">
                                Sampai
                            </label>

                            <input type="date" id="dateTo">

                        </div>


                        <button type="button" class="filter-button" onclick="filterData()">

                            <i class="bi bi-funnel"></i>

                            Filter

                        </button>

                    </div>

                </div>


            </div>

            <div class="mt-2">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">KODE</th>
                            <th scope="col">PETUGAS</th>
                            <th scope="col">PELANGGAN</th>
                            <th scope="col">TANGGAL</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">OPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transaksi = mysqli_query($conn, "SELECT
                                                transactions.id,
                                                transactions.invoice_number,
                                                users.name AS cashier,
                                                customers.name AS customer,
                                                transactions.total,
                                                transactions.paid,
                                                transactions.change_amount,
                                                transactions.created_at
                                                FROM transactions
                                                INNER JOIN users
                                                ON transactions.user_id = users.id

                                                LEFT JOIN customers
                                                ON transactions.customer_id = customers.id

                                                ORDER BY transactions.id DESC");

                        $data = mysqli_fetch_all($transaksi, MYSQLI_ASSOC);
                        ?>
                        <?php foreach ($data as $datas): ?>
                            <tr>
                                <th><?= $datas['invoice_number'] ?></th>
                                <td><?= $datas['cashier'] ?></td>
                                <td><?= $datas['customer'] ?? 'umum' ?></td>
                                <td><?= date('d/m/Y', strtotime($datas['created_at'])); ?></td>
                                <td><?= "Rp" . number_format($datas['total'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="delete.php?idTransaksi=<?= $datas['id'] ?>" data-bs-toggle="modal"
                                        data-bs-target="#edit<?= $datas['id'] ?>" class="btn btn-primary p-1"><i
                                            class="bi bi-eye"></i> Detail</a>

                                    <div class="modal fade" id="edit<?= $datas['id'] ?>" tabindex="-1"
                                        aria-labelledby="editModalLabel<?= $datas['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog"></div>
                                        <div class="main-content">

                                            <style>
                                                /* HEADER */
                                                .page-header {
                                                    display: flex;
                                                    justify-content: space-between;
                                                    align-items: center;
                                                    margin-bottom: 25px;
                                                    background-color: #fdfdfd;
                                                    border-radius: 10px;
                                                    padding: 25px;
                                                    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
                                                    margin-bottom: 20px;
                                                }

                                                .page-title {
                                                    font-size: 28px;
                                                    font-weight: 700;
                                                }

                                                .page-subtitle {
                                                    color: #030303;
                                                    margin-top: 5px;
                                                    font-weight: 600;
                                                }

                                                /* CARD */
                                                .detail-card {
                                                    
                                                    background: white;
                                                    border: none;
                                                    border-radius: 14px;
                                                    padding: 25px;
                                                    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
                                                    margin-bottom: 20px;
                                                }

                                                /* TRANSACTION INFO */
                                                .invoice-box {
                                                    display: flex;
                                                    justify-content: space-between;
                                                    align-items: center;
                                                    padding-bottom: 20px;
                                                    border-bottom: 1px solid #eee;
                                                    margin-bottom: 20px;
                                                }

                                                .invoice-number {
                                                    font-size: 24px;
                                                    font-weight: 700;
                                                }

                                                .invoice-date {
                                                    color: #6c757d;
                                                    font-size: 14px;
                                                    margin-top: 5px;
                                                }

                                                .badge-success {
                                                    background: #d1fae5;
                                                    color: #047857;
                                                    padding: 8px 14px;
                                                    border-radius: 20px;
                                                    font-weight: 600;
                                                }

                                                /* INFO */
                                                .info-title {
                                                    font-size: 15px;
                                                    font-weight: 700;
                                                    margin-bottom: 15px;
                                                }

                                                .info-item {
                                                    margin-bottom: 12px;
                                                }

                                                .info-label {
                                                    font-size: 13px;
                                                    color: #6c757d;
                                                    margin-bottom: 3px;
                                                }

                                                .info-value {
                                                    font-weight: 600;
                                                }

                                                /* TABLE */
                                                .table thead th {
                                                    background: #f8f9fa;
                                                    border-bottom: none;
                                                    font-size: 13px;
                                                    color: #6c757d;
                                                    text-transform: uppercase;
                                                    padding: 15px;
                                                }

                                                .table tbody td {
                                                    padding: 16px 15px;
                                                    vertical-align: middle;
                                                }

                                                .product-code {
                                                    font-size: 13px;
                                                    color: #6c757d;
                                                }

                                                .product-name {
                                                    font-weight: 600;
                                                }

                                                /* TOTAL */
                                                .payment-box {
                                                    max-width: 450px;
                                                    margin-left: auto;
                                                }

                                                .payment-row {
                                                    display: flex;
                                                    justify-content: space-between;
                                                    padding: 8px 0;
                                                }

                                                .payment-row.total {
                                                    font-size: 20px;
                                                    font-weight: 700;
                                                    border-top: 1px solid #eee;
                                                    margin-top: 10px;
                                                    padding-top: 15px;
                                                }

                                                .payment-row.change {
                                                    color: #198754;
                                                    font-weight: 600;
                                                }

                                                /* BUTTON */
                                                .btn-back {
                                                    border-radius: 8px;
                                                }

                                                .btn-print {
                                                    border-radius: 8px;
                                                }

                                                /* RESPONSIVE */
                                                @media (max-width: 992px) {
                                                    .main-content {
                                                        margin-left: 0;
                                                        padding: 20px;
                                                    }
                                                }

                                                @media (max-width: 576px) {
                                                    .main-content {
                                                        padding: 15px;
                                                    }

                                                    .page-header {
                                                        align-items: flex-start;
                                                        flex-direction: column;
                                                        gap: 15px;
                                                    }

                                                    .invoice-box {
                                                        flex-direction: column;
                                                        align-items: flex-start;
                                                        gap: 15px;
                                                    }

                                                    .detail-card {
                                                        padding: 18px;
                                                    }

                                                    .table {
                                                        min-width: 700px;
                                                    }

                                                    .table-wrapper {
                                                        overflow-x: auto;
                                                    }
                                                }

                                                @media print {
                                                    body {
                                                        background: white;
                                                    }

                                                    .no-print {
                                                        display: none !important;
                                                    }

                                                    .main-content {
                                                        margin-left: 0;
                                                        padding: 0;
                                                    }

                                                    .detail-card {
                                                        box-shadow: none;
                                                    }
                                                }
                                            </style>

                                            <!-- HEADER -->
                                            <div class="page-header no-print">
                                                <div>
                                                    <h1 class="page-title"> Detail Penjualan </h1>
                                                    <p class="page-subtitle"> Informasi lengkap transaksi penjualan </p>
                                                </div>
                                                <div class="d-flex gap-2 m-3"> <a href="penjualan.php"
                                                        class="btn btn-outline-secondary btn-back"> <i
                                                            class="bi bi-arrow-left"></i> Kembali </a> <button
                                                        onclick="window.print()" class="btn btn-primary btn-print"> <i
                                                            class="bi bi-printer"></i> Cetak </button> </div>
                                            </div> <!-- INFORMASI TRANSAKSI -->
                                            <div class="detail-card">
                                                <div class="invoice-box">
                                                    <div>
                                                        <div class="invoice-number">
                                                            <?= htmlspecialchars($datas['invoice_number']); ?>
                                                        </div>
                                                        <div class="invoice-date"> <i class="bi bi-calendar3"></i>
                                                            <?= date('d F Y, H:i', strtotime($datas['created_at'])); ?>
                                                        </div>
                                                    </div> <span class="badge-success"> <i class="bi bi-check-circle"></i>
                                                        Selesai </span>
                                                </div> <!-- INFORMASI KASIR & CUSTOMER -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="info-title"> <i class="bi bi-person-badge"></i> Kasir
                                                        </div>
                                                        <div class="info-item">
                                                            <div class="info-label"> Nama Kasir </div>
                                                            <div class="info-value">
                                                                <?= htmlspecialchars($datas['cashier']); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-title"> <i class="bi bi-person"></i> Customer
                                                        </div>
                                                        <div class="info-item">
                                                            <div class="info-label"> Nama Customer </div>
                                                            <div class="info-value">
                                                                <?= htmlspecialchars($datas['customer'] ?? 'Umum'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-title"> <i class="bi bi-telephone"></i> Kontak
                                                        </div>
                                                        <div class="info-item">
                                                            <div class="info-label"> Nomor Telepon </div>
                                                            <div class="info-value">
                                                                <?= htmlspecialchars($datas['phone'] ?? '-'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- DETAIL PRODUK -->
                                            <div class="detail-card">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <h5 class="mb-1 fw-bold"> Detail Produk </h5> <small
                                                            class="text-muted">
                                                            Produk yang dibeli dalam transaksi ini </small>
                                                    </div>
                                                </div>
                                                <div class="table-wrapper">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%"> # </th>
                                                                <th> Produk </th>
                                                                <th> Harga </th>
                                                                <th class="text-center"> Qty </th>
                                                                <th class="text-end"> Subtotal </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no = 1;
                                                            while ($detail = mysqli_fetch_assoc($transaksi)): ?>
                                                                <tr>
                                                                    <td> <?= $no++; ?> </td>
                                                                    <td>
                                                                        <div class="product-name">
                                                                            <?= htmlspecialchars($detail['product_name']); ?>
                                                                        </div>
                                                                        <div class="product-code"> Kode:
                                                                            <?= htmlspecialchars($detail['code']); ?>
                                                                        </div>
                                                                    </td>
                                                                    <td> Rp <?= number_format($detail['price'], 0, ',', '.'); ?>
                                                                    </td>
                                                                    <td class="text-center"> <?= $detail['quantity']; ?> </td>
                                                                    <td class="text-end fw-semibold"> Rp
                                                                        <?= number_format($detail['subtotal'], 0, ',', '.'); ?>
                                                                    </td>
                                                                </tr> <?php endwhile; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> <!-- PEMBAYARAN -->
                                            <div class="detail-card">
                                                <div class="payment-box">
                                                    <div class="payment-row"> <span> Total </span> <strong> Rp
                                                            <?= number_format($datas['total'], 0, ',', '.'); ?>
                                                        </strong> </div>
                                                    <div class="payment-row"> <span> Dibayar </span> <span> Rp
                                                            <?= number_format($datas['paid'], 0, ',', '.'); ?> </span>
                                                    </div>
                                                    <div class="payment-row chandatas['change_amount'], 0, ',', '.'); ?>
                                                        </span> </div>
                                                    <div class="payment-row total"> <span> Total Pembayaran </span> <span>
                                                            Rp
                                                            <?= number_format($datas['total'], 0, ',', '.'); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </section>
    </main>
</body>

</html>