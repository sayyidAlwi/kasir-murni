<!-- =====================================================
     SIDEBAR
===================================================== -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS link -->
<link rel="stylesheet" href="../assets/css/style.css">

<script src="<?= BASE_URL ?>/assets/js/script.js"></script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php
$settingsMessage = '';

$storeNameResult = mysqli_query($conn, "SELECT setting_value FROM app_settings WHERE setting_key = 'store_name'");
$storeNameRow = mysqli_fetch_assoc($storeNameResult);
$storeName = $storeNameRow['setting_value'] ?? 'KasirDoelim';

$themeResult = mysqli_query($conn, "SELECT setting_value FROM app_settings WHERE setting_key = 'theme'");
$themeRow = mysqli_fetch_assoc($themeResult);
$theme = $themeRow['setting_value'] ?? 'light';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $newStoreName = trim($_POST['store_name'] ?? '');
    $newStoreName = $newStoreName !== '' ? $newStoreName : 'KasirDoelim';
    $newTheme = $_POST['theme'] ?? 'light';
    $newTheme = in_array($newTheme, ['light', 'dark'], true) ? $newTheme : 'light';

    $storeStatement = mysqli_prepare($conn, "INSERT INTO app_settings (setting_key, setting_value) VALUES ('store_name', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    mysqli_stmt_bind_param($storeStatement, 's', $newStoreName);
    mysqli_stmt_execute($storeStatement);
    mysqli_stmt_close($storeStatement);

    $themeStatement = mysqli_prepare($conn, "INSERT INTO app_settings (setting_key, setting_value) VALUES ('theme', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    mysqli_stmt_bind_param($themeStatement, 's', $newTheme);
    mysqli_stmt_execute($themeStatement);
    mysqli_stmt_close($themeStatement);

    $storeName = $newStoreName;
    $theme = $newTheme;
    $settingsMessage = 'Pengaturan berhasil disimpan.';
}
?>

<aside class="sidebar" id="sidebar">

    <a href="index.php" class="sidebar-brand">
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

        <a href="<?= BASE_URL ?>kasir/riwayat/riwayat.php">
            <i class="bi bi-tags"></i>
            <span>Riwayat Transaksi</span>
        </a>

        <a href="<?= BASE_URL ?>kasir/laporan/laporan.php">
            <i class="bi bi-people"></i>
            <span>Laporan</span>
        </a>

        <a href="<?= BASE_URL ?>kasir/pengeluaran/pengeluaran.php">
            <i class="bi bi-truck"></i>
            <span>Pengeluaran</span>
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
                        <label class="form-label" for="themeselect">Tema</label>
                        <select class="form-select" id="themeselect" name="theme">
                            <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>Light</option>
                            <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Dark</option>
                        </select>
                    </div>
                    <section class="settings-languange">
                        <label class="language-label" for="languageselect">Bahasa</label>
                        <select id="LanguageSelect">
                            <option value="id">ID Bahasa</option>
                            <option value="en">GB English</option>
                            <option value="ja">JP 日本語</option>
                        </select>
                    </section>

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
