<!-- =====================================================
     SIDEBAR
===================================================== -->


<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php
$settingsMessage = '';

$storeNameResult = mysqli_query($conn, "SELECT setting_value FROM app_settings WHERE setting_key = 'store_name'");
$storeNameRow = mysqli_fetch_assoc($storeNameResult);
$storeName = $storeNameRow['setting_value'] ?? 'KasirDoelim';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $newStoreName = trim($_POST['store_name'] ?? '');
    $newStoreName = $newStoreName !== '' ? $newStoreName : 'KasirDoelim';
    $newTheme = $_POST['theme'] ?? 'light';
    $newTheme = in_array($newTheme, ['light', 'dark'], true) ? $newTheme : 'light';

    $storeStatement = mysqli_prepare($conn, "INSERT INTO app_settings (setting_key, setting_value) VALUES ('store_name', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    mysqli_stmt_bind_param($storeStatement, 's', $newStoreName);
    mysqli_stmt_execute($storeStatement);
    mysqli_stmt_close($storeStatement);

    $storeName = $newStoreName;
    $settingsMessage = 'Pengaturan berhasil disimpan.';
}
?>

<aside class="sidebar" id="sidebar">

    <a href="<?= BASE_URL ?>kasir/index.php" class="sidebar-brand">
        <i class="bi bi-shop"></i>
        <?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?>
    </a>

    <div class="sidebar-menu">

        <div class="menu-title">
            Menu Utama
        </div>

        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        ?>

        <a href="<?= BASE_URL ?>kasir/index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-cart-plus"></i>
            <span>Kasir</span>
        </a>

        <a href="<?= BASE_URL ?>kasir/produk/produk.php" class="<?= $currentPage == 'produk.php' ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i>
            <span>Produk & Stok</span>
        </a>

        <a href="<?= BASE_URL ?>kasir/penjualan/penjualan.php">
            <i class="bi bi-tags"></i>
            <span>Riwayat Transaksi</span>
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
                <?php if ($settingsMessage !== ''): ?>
                    <div class="alert alert-success py-2" role="alert">
                        <?= htmlspecialchars($settingsMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="store_name">Nama Toko</label>
                        <input class="form-control" type="text" id="store_name" name="store_name" value="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?>" maxlength="255" required>
                    </div>
                    <button class="btn btn-primary" type="submit" name="save_settings" value="1">
                        Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Overlay -->
 <div class="sidebar-overlay" id="sidebarOverlay"></div>
