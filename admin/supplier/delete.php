<?php 

include '../../config/database.php';

$id = $_GET['idsupplier'];
$query = mysqli_query($conn, "DELETE FROM suppliers WHERE id = '$id'");
header("Location: supplier.php");

?>