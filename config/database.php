<?php 

$conn = mysqli_connect('localhost', 'root', '', 'sistemkasir');

if (!$conn) {
    die("Gagal terhubung ke database : " . mysqli_connect_errno());
    };
    
<<<<<<< HEAD
    define('BASE_URL', '/kasir-murni/');
=======
    define('BASE_URL', '/kasir-buatan/');
>>>>>>> 5fd104489c427d5bdc1d62d4d7c79fd812daa44a
    
?>