<?php 
    include '../../config/database.php';

    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        mysqli_query($conn, "INSERT INTO customers (name,address,phone) VALUES ('$name','$address','$phone')");
        header("location:pelanggan.php");
    }
?>