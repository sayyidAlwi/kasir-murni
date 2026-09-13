
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - KasirKu</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS link -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <?php 
    session_start();
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

                                <div class="stat-change text-muted">
                                    Produk tersedia
                                </div>

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


            <!-- =================================================
             CHART + STOCK
        ================================================== -->

            <div class="row g-4 mb-4">

                <!-- CHART -->

                <div class="col-12 col-xl-8">

                    <div class="dashboard-card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <div>
                                <i class="bi bi-bar-chart-line me-2"></i>
                                Grafik Penjualan
                            </div>

                            <select class="form-select form-select-sm" style="width: 130px;">
                                <option>Minggu ini</option>
                                <option>Bulan ini</option>
                                <option>Tahun ini</option>
                            </select>

                        </div>

                        <div class="chart-container">

                            <canvas id="salesChart"></canvas>

                        </div>

                    </div>

                </div>


                <!-- STOCK -->

                <div class="col-12 col-xl-4">

                    <div class="dashboard-card">

                        <div class="card-header d-flex justify-content-between">

                            <span>
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Stok Menipis
                            </span>

                            <a href="produk.php" class="text-decoration-none">
                                Lihat semua
                            </a>

                        </div>

                        <div class="card-body p-0">

                            <div class="list-group list-group-flush">

                                <div class="list-group-item p-3">

                                    <div class="d-flex justify-content-between">

                                        <div>
                                            <strong>Kopi Arabica</strong>
                                            <small class="d-block text-muted">
                                                SKU: PRD001
                                            </small>
                                        </div>

                                        <span class="badge text-bg-danger align-self-center">
                                            3 tersisa
                                        </span>

                                    </div>

                                </div>


                                <div class="list-group-item p-3">

                                    <div class="d-flex justify-content-between">

                                        <div>
                                            <strong>Gula Pasir</strong>
                                            <small class="d-block text-muted">
                                                SKU: PRD023
                                            </small>
                                        </div>

                                        <span class="badge text-bg-warning align-self-center">
                                            5 tersisa
                                        </span>

                                    </div>

                                </div>


                                <div class="list-group-item p-3">

                                    <div class="d-flex justify-content-between">

                                        <div>
                                            <strong>Susu UHT</strong>
                                            <small class="d-block text-muted">
                                                SKU: PRD045
                                            </small>
                                        </div>

                                        <span class="badge text-bg-warning align-self-center">
                                            7 tersisa
                                        </span>

                                    </div>

                                </div>


                                <div class="list-group-item p-3">

                                    <div class="d-flex justify-content-between">

                                        <div>
                                            <strong>Teh Celup</strong>
                                            <small class="d-block text-muted">
                                                SKU: PRD051
                                            </small>
                                        </div>

                                        <span class="badge text-bg-warning align-self-center">
                                            8 tersisa
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
             TRANSAKSI TERBARU
        ================================================== -->

            <div class="dashboard-card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span>
                        <i class="bi bi-receipt me-2"></i>
                        Transaksi Terbaru
                    </span>

                    <a href="penjualan.php" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-4">
                                        ID Transaksi
                                    </th>

                                    <th>
                                        Pelanggan
                                    </th>

                                    <th>
                                        Tanggal
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end px-4">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td class="px-4 fw-semibold">
                                        #TRX-00125
                                    </td>

                                    <td>
                                        Budi Santoso
                                    </td>

                                    <td>
                                        13 Sep 2026, 09:42
                                    </td>

                                    <td>
                                        Rp 125.000
                                    </td>

                                    <td>
                                        <span class="badge text-bg-success">
                                            Selesai
                                        </span>
                                    </td>

                                    <td class="text-end px-4">

                                        <a href="#" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    </td>

                                </tr>


                                <tr>

                                    <td class="px-4 fw-semibold">
                                        #TRX-00124
                                    </td>

                                    <td>
                                        Andi Wijaya
                                    </td>

                                    <td>
                                        13 Sep 2026, 09:15
                                    </td>

                                    <td>
                                        Rp 75.000
                                    </td>

                                    <td>
                                        <span class="badge text-bg-success">
                                            Selesai
                                        </span>
                                    </td>

                                    <td class="text-end px-4">

                                        <a href="#" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    </td>

                                </tr>


                                <tr>

                                    <td class="px-4 fw-semibold">
                                        #TRX-00123
                                    </td>

                                    <td>
                                        Siti Aminah
                                    </td>

                                    <td>
                                        13 Sep 2026, 08:53
                                    </td>

                                    <td>
                                        Rp 245.000
                                    </td>

                                    <td>
                                        <span class="badge text-bg-success">
                                            Selesai
                                        </span>

                                    </td>

                                    <td class="text-end px-4">

                                        <a href="#" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    </td>

                                </tr>


                                <tr>

                                    <td class="px-4 fw-semibold">
                                        #TRX-00122
                                    </td>

                                    <td>
                                        Rudi Hartono
                                    </td>

                                    <td>
                                        13 Sep 2026, 08:21
                                    </td>

                                    <td>
                                        Rp 95.000
                                    </td>

                                    <td>
                                        <span class="badge text-bg-warning">
                                            Pending
                                        </span>

                                    </td>

                                    <td class="text-end px-4">

                                        <a href="#" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    </td>

                                </tr>

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
        // SIDEBAR MOBILE
        // ==========================================

        const menuToggle = document.getElementById("menuToggle");
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");

        menuToggle.addEventListener("click", function () {

            sidebar.classList.toggle("show");
            sidebarOverlay.classList.toggle("show");

        });

        sidebarOverlay.addEventListener("click", function () {

            sidebar.classList.remove("show");
            sidebarOverlay.classList.remove("show");

        });


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
```