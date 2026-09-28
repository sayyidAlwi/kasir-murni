<!DOCTYPE html>
<html lang="id">


<body>
    <?php
    include "../config/database.php";
    ?>

    <?php
    include("../includes/header.php");
    ?>
    <?php
    include("../includes/sidebar.php");
    ?>


    <!-- =====================================================
     MAIN
===================================================== -->

    <main class="main">

        <!-- =================================================
         CONTENT
    ================================================== -->

        <section class="content">

            <!-- HEADER -->

            <div class="mb-4">

                <h2 class="page-title">
                    Dashboard
                </h2>

                <p class="page-subtitle mb-0">
                    Selamat datang kembali,
                    <strong>
                        <?php $id = $_SESSION['id'];
                        $dt_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
                        while ($user = mysqli_fetch_array($dt_user)) { ?>
                            <?php
                            echo $user['username'];
                        } ?>
                    </strong>.
                    <br>
                    Berikut ringkasan sistem kasir hari ini.
                </p>

            </div>


            <!-- =================================================
             STATISTICS
        ================================================== -->

            <div class="row g-4 mb-4">

                <!-- Penjualan -->

                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="stat-card">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="stat-title">
                                    Penjualan Hari Ini
                                </div>

                                <p class="stat-value">

                                    <?php
                                    $query = mysqli_query($conn, "
    SELECT SUM(total) AS total_penjualan
    FROM transactions
    WHERE created_at >= CURDATE()
    AND created_at < CURDATE() + INTERVAL 1 DAY
");

                                    $data = mysqli_fetch_assoc($query);

                                    $total_penjualan = $data['total_penjualan'] ?? 0;
                                    echo "Rp" . number_format($total_penjualan, 0, ',', '.');

                                    ?>
                                </p>

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
                                    <?php

                                    $query = mysqli_query($conn, "
    SELECT 
        COALESCE(
            SUM(
                (td.price - p.purchase_price) * td.quantity
            ),
            0
        ) AS keuntungan
    FROM transaction_details td
    JOIN transactions t
        ON td.transaction_id = t.id
    JOIN products p
        ON td.product_id = p.id
    WHERE t.created_at >= CURDATE()
    AND t.created_at < CURDATE() + INTERVAL 1 DAY
");

                                    $data = mysqli_fetch_assoc($query);

                                    $keuntungan = $data['keuntungan'] ?? 0;

                                    echo 'Rp' . number_format($keuntungan, 0, ',', '.');
                                    ?>
                                </p>


                            </div>

                            <div class="stat-icon bg-success-subtle text-success">
                                <i class="bi bi-cash-stack"></i>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- Produk -->

                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="stat-card">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="stat-title">
                                    Total Produk
                                </div>

                                <p class="stat-value">
                                    <?php
                                    $produk = mysqli_query($conn, "SELECT * FROM products");
                                    echo mysqli_num_rows($produk)
                                        ?>
                                </p>

                            </div>

                            <div class="stat-icon bg-warning-subtle text-warning">
                                <i class="bi bi-box-seam"></i>
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

                            </div>

                            <div class="stat-icon bg-info-subtle text-info">
                                <i class="bi bi-people"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
             TRANSAKSI
        ================================================== -->

            <div class="row g-4 mb-4">

            <!-- =================================================
             TRANSAKSI TERBARU
        ================================================== -->

            <div class="dashboard-card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span>
                        <i class="bi bi-receipt me-2"></i>
                        Transaksi Terbaru
                    </span>

                    <a href="penjualan/penjualan.php" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>

                </div>

                <?php
                $query = mysqli_query($conn, "
                    SELECT *
                    FROM transactions
                    ORDER BY created_at DESC
                    LIMIT 5
                    ");
                ?>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">
 <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) : 
                    ?>
                
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $data['invoice_number']; ?></td>
                        <td><?= date('d-m-Y', strtotime($data['created_at'])); ?></td>
                        <td>
                            Rp <?= number_format($data['total'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <a href="penjualan/detail.php?idPenjualan=<?= $data['id']; ?>" 
                               class="btn btn-sm btn-primary">
                                Detail
                            </a>
                        </td>
                    </tr>

                    <?php endwhile; ?>
                </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </section>

    </main>


    <!-- =====================================================
     JAVASCRIPT
===================================================== -->

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- Chart.js -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js">
    </script>


    <script>
        // ==========================================
        // SALES CHART
        // ==========================================

        const ctx = document.getElementById("salesChart");

        new Chart(ctx, {

            type: "line",

            data: {

                labels: [
                    "Sen",
                    "Sel",
                    "Rab",
                    "Kam",
                    "Jum",
                    "Sab",
                    "Min"
                ],

                datasets: [

                    {
                        label: "Penjualan",

                        data: [
                            1200000,
                            1800000,
                            1500000,
                            2200000,
                            1900000,
                            2800000,
                            2450000
                        ],

                        borderWidth: 3,

                        tension: 0.4,

                        fill: true
                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function (value) {

                                return "Rp " +
                                    (value / 1000000).toFixed(1) +
                                    " Jt";

                            }

                        }

                    }

                }

            }

        });
    </script>

</body>

</html>