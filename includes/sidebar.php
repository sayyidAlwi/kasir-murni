<!-- =====================================================
     SIDEBAR
===================================================== -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS link -->
<link rel="stylesheet" href="../assets/css/style.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<aside class="sidebar" id="sidebar">

    <a href="index.php" class="sidebar-brand">
        <i class="bi bi-shop"></i>
        KasirJorok
    </a>

    <div class="sidebar-menu">

        <div class="menu-title">
            Menu Utama
        </div>

        <?php
            $currentPage = basename($_SERVER['PHP_SELF']);
        ?>

        <a href="<?= BASE_URL ?>admin/index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= BASE_URL ?>admin/produk/produk.php" class="<?= $currentPage == 'produk.php' ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i>
            <span>Produk</span>
        </a>

        <a href="<?= BASE_URL ?>admin/kategori/kategori.php">
            <i class="bi bi-tags"></i>
            <span>Kategori</span>
        </a>

        <a href="<?= BASE_URL ?>admin/pelanggan/pelanggan.php">
            <i class="bi bi-people"></i>
            <span>Pelanggan</span>
        </a>

        <a href="<?= BASE_URL ?>admin/supplier/supplier.php">
            <i class="bi bi-truck"></i>
            <span>Supplier</span>
        </a>

        <div class="menu-title mt-3">
            Transaksi
        </div>

        <a href="<?= BASE_URL ?>admin/penjualan/penjualan.php">
            <i class="bi bi-cart3"></i>
            <span>Penjualan</span>
        </a>

        <a href="<?= BASE_URL ?>admin/pembelian/pembelian.php">
            <i class="bi bi-bag-plus"></i>
            <span>Pembelian</span>
        </a>

        <div class="menu-title mt-3">
            Sistem
        </div>

        <a href="<?= BASE_URL ?>admin/pengguna/pengguna.php">
            <i class="bi bi-person-gear"></i>
            <span>Pengguna</span>
        </a>

        <a href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                    <i class="bi bi-gear"></i>
                                    <span>Pengaturan</span>
                                </a>

        <a href="../auth/logout.php" class="text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>

    </div>

</aside>

<div class="modal fade" id="settingsModal" tabindex="-1"
    aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="settingsModalLabel">Pengaturan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="">Tema</label>
                        <select class="form-select" name="tema" id="">
                            <option value="light">Light <i class="bi bi-lightbulb-fill"></i></option>
                            <option value="dark">Dark <i class="bi bi-lightbulb-off"></i></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nama Toko</label>
                        <input type="text" name="namakasir" value="<?= $pelanggan ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>