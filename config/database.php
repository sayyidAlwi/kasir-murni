<?php 

$conn = mysqli_connect('localhost', 'root', '', 'sistemkasir');

if (!$conn) {
    die("Gagal terhubung ke database : " . mysqli_connect_errno());
    };
    
    define('BASE_URL', '/kasir-murni/');
    
?>