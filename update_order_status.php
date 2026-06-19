<?php
include("db.php");

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn,
"UPDATE customer_orders
 SET status='$status'
 WHERE id='$id'");

header("Location: received_orders.php");
exit();
?>