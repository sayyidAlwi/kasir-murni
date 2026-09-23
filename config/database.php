<?php 

$conn = mysqli_connect('localhost', 'root', '', 'sistemkasir');

if (!$conn) {
    die("Gagal terhubung ke database : " . mysqli_connect_errno());
    };

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS app_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL DEFAULT ''
)");
    
    define('BASE_URL', '/kasir-murni/');

?>