<?php 

include '../../config/database.php';

if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $query = "INSERT INTO suppliers (name, address, phone) VALUES ('$name', '$address', '$phone')";
    mysqli_query($conn, $query);

    header("Location: supplier.php");
}

?>