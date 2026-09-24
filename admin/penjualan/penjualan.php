<?php
session_start();
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dateFrom = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : '';
$dateTo = isset($_GET['dateTo']) ? $_GET['dateTo'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <main class="main">
        <section class="content">
            <div class="mb-4">
                <h2 class="page-title">
                    Data Penjualan
                </h2>
                <p>Kelola dan pantau transaksi</p>
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
            </div>

            <div>

                <div class="filter-card">
                   

                   <form action="" method="GET">
                     <!-- SEARCH BAR -->
                     <div class="search-wrapper">
                    
                         <i class="bi bi-search search-icon"></i>
                    
                         <input type="text" name="search" id="searchInput" class="search-input"
                             placeholder="Cari transaksi atau kasir..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                    
                     </div>
                    
                    
                     <!-- FILTER TANGGAL -->
                     <div class="date-filter">
                    
                         <div class="date-group">
                    
                             <label for="dateFrom">
                                 Dari
                             </label>
                    
                             <input type="date" name="dateFrom" id="dateFrom" value="<?= htmlspecialchars($dateFrom) ?>">
                    
                         </div>
                    
                    
                         <div class="date-group">
                    
                             <label for="dateTo">
                                 Sampai
                             </label>
                    
                             <input type="date" name="dateTo" id="dateTo" value="<?= htmlspecialchars($dateTo) ?>">
                    
                         </div>
                    
                    
                         <button type="submit" class="filter-button btn btn-primary">
                    
                             <i class="bi bi-funnel"></i>
                    
                             Filter
                    
                         </button>
                    
                   </form>

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
                        $search = $_GET['search'] ?? '';
                        $dateFrom = $_GET['dateFrom'] ?? '';
                        $dateTo = $_GET['dateTo'] ?? '';

                        $where = [];

                        if ($search !== '') {
                            $search = mysqli_real_escape_string($conn, $search);
                            $where[] = "(t.invoice_number LIKE '%$search%' OR u.name LIKE '%$search%' OR c.name LIKE '%$search%')";
                        }

                        if ($dateFrom !== '') {
                            $dateFrom = mysqli_real_escape_string($conn, $dateFrom);
                            $where[] = "DATE(t.created_at) >= '$dateFrom'";
                        }

                        if ($dateTo !== '') {
                            $dateTo = mysqli_real_escape_string($conn, $dateTo);
                            $where[] = "DATE(t.created_at) <= '$dateTo'";
                        }

                        $sql = "SELECT t.id, t.invoice_number, u.name AS cashier, c.name AS customer, t.total, t.paid, t.change_amount, t.created_at
                                FROM transactions t
                                INNER JOIN users u ON t.user_id = u.id
                                LEFT JOIN customers c ON t.customer_id = c.id";

                        if (!empty($where)) {
                            $sql .= " WHERE " . implode(' AND ', $where);
                        }

                        $sql .= " ORDER BY t.created_at DESC";
                        $transaksi = mysqli_query($conn, $sql);

                        if ($transaksi && mysqli_num_rows($transaksi) > 0) {
                            while ($datas = mysqli_fetch_assoc($transaksi)) {
                        ?>
                                <tr>
                                    <th><?= htmlspecialchars($datas['invoice_number']) ?></th>
                                    <td><?= htmlspecialchars($datas['cashier']) ?></td>
                                    <td><?= htmlspecialchars($datas['customer'] ?? 'Umum') ?></td>
                                    <td><?= date('d/m/Y', strtotime($datas['created_at'])) ?></td>
                                    <td><?= 'Rp' . number_format((float) $datas['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="detail.php?idPenjualan=<?= $datas['id'] ?>" class="btn btn-primary p-1">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data penjualan</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </section>
    </main>
</body>

</html>