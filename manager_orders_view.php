<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'Inventory Manager') {
    header("Location: dashboard.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM customer_orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Orders Monitor</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #061f18;
    color: #eafaf1;
}

.container {
    padding: 35px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

h1 {
    color: white;
    margin: 0;
}

.back-btn {
    background: #047857;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    text-decoration: none;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #10261f;
    margin-top: 30px;
    border: 1px solid #1d4f3d;
}

th, td {
    padding: 14px;
    border: 1px solid #1d4f3d;
    text-align: left;
}

th {
    background: #12382c;
    color: #9ee7bd;
}

tr:hover {
    background: #17382e;
}

.status {
    font-weight: bold;
    color: #facc15;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Customer Orders Monitor</h1>
            <p>View-only customer order records and order status.</p>
        </div>

        <a href="manager_dashboard.php" class="back-btn">← Back Dashboard</a>
    </div>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['item_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>Ksh <?php echo number_format($row['price'], 2); ?></td>
            <td>Ksh <?php echo number_format($row['total_amount'], 2); ?></td>
            <td class="status"><?php echo $row['status']; ?></td>
            <td><?php echo $row['order_date']; ?></td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>