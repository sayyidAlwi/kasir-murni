<?php

include '../../config/database.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $query = mysqli_query($conn, "UPDATE suppliers SET name = '$name', address ='$address', phone = '$phone' WHERE id = '$id'");
}

?>