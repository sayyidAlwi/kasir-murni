<?php 
include '../../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php'
?>

<body>
    <main class="main">
        <section class="content">
            <div class="mb-4">
                <h2 class="page-title">Laporan</h2>
                <p>Pantau dan analisis aktivitas penjualan toko </p>
            </div>

            <div class="date-filter mb-4">
                <div class="date-group">
                    <input type="date" >
                </div>
                <button type="submit" class="filter-button btn btn-primary">
                    <i class="bi bi-funnel"></i>Filter
                </button>
            </div>

            <div class="row g-4 mb-4">

                <!-- Penjualan -->

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-title">
                                    Total Penjualan
                                </div>
                                <p class="stat-value">
                                    <?php
                                    $penjualan = mysqli_query($conn, "SELECT SUM(total) as total FROM transactions");
                                    $row = mysqli_fetch_assoc($penjualan);
                                    echo "Rp. " . number_format($row['total'], 0, ',', '.');
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
                                    Total Transaksi
                                </div>
                                <p class="stat-value">
                                    <?php
                                    $transaksi = mysqli_query($conn, "SELECT * FROM transactions");
                                    echo mysqli_num_rows($transaksi);
                                    ?>
                                </p>
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
                                    Rata-Rata Transaksi
                                </div>
                                <p class="stat-value">
                                    <?php
                                    $rata_rata = 0;
                                    if ($row['total'] != 0) {
                                        $rata_rata = $row['total'] / mysqli_num_rows($transaksi);
                                        echo "Rp. " . number_format($rata_rata, 0, ',', '.');
                                    } else {
                                        echo "Rp. 0";
                                    }
                                    ?>
                                </p>

                            </div>
                            <div class="stat-icon bg-info-subtle text-info">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Produk -->

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
                                        $value = mysqli_num_rows($produk);
                                        echo $value;
                                    ?>
                                </p>
                            </div>
                            <div class="stat-icon bg-info-subtle text-info">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
</body>