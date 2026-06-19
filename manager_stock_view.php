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

$result = mysqli_query($conn, "SELECT * FROM items ORDER BY item_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Stock Observation</title>

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
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Stock Observation</h1>
            <p>View-only inventory stock list.</p>
        </div>

        <a href="manager_dashboard.php" class="back-btn">← Back Dashboard</a>
    </div>

    <table>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Stock Code</th>
            <th>Quantity</th>
            <th>Purchase Price</th>
            <th>Selling Price</th>
            <th>Supplier</th>
            <th>Date Recorded</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['item_id']; ?></td>
            <td><?php echo $row['item_name']; ?></td>
            <td><?php echo $row['stock_code']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>Ksh <?php echo number_format($row['purchase_price'], 2); ?></td>
            <td>Ksh <?php echo number_format($row['selling_price'], 2); ?></td>
            <td><?php echo $row['supplier']; ?></td>
            <td><?php echo $row['date_recorded']; ?></td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>