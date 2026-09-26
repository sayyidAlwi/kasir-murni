<?php 
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'sistemkasir');

if (!$conn) {
    die("Gagal terhubung ke database : " . mysqli_connect_errno());
};

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS app_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL DEFAULT ''
)");

$appTheme = 'light';
$themeRes = mysqli_query($conn, "SELECT setting_value FROM app_settings WHERE setting_key = 'theme' LIMIT 1");
if ($themeRes && mysqli_num_rows($themeRes) > 0) {
    $themeRow = mysqli_fetch_assoc($themeRes);
    $appTheme = in_array($themeRow['setting_value'] ?? 'light', ['light', 'dark'], true) ? $themeRow['setting_value'] : 'light';
}

define('BASE_URL', '/kasir-murni/');

?>