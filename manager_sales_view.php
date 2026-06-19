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

$result = mysqli_query($conn,"
SELECT sales.*, items.item_name
FROM sales
LEFT JOIN items ON sales.item_id = items.item_id
ORDER BY sale_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Sales Performance</title>

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
            <h1>Sales Performance</h1>
            <p>View-only sales records and transaction performance.</p>
        </div>

        <a href="manager_dashboard.php" class="back-btn">← Back Dashboard</a>
    </div>

    <table>
        <tr>
            <th>Sale ID</th>
            <th>Item Name</th>
            <th>Quantity Sold</th>
            <th>Sale Price</th>
            <th>Sale Date</th>
            <th>Recorded By</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['sale_id']; ?></td>
            <td><?php echo $row['item_name']; ?></td>
            <td><?php echo $row['quantity_sold']; ?></td>
            <td>Ksh <?php echo number_format($row['sale_price'], 2); ?></td>
            <td><?php echo $row['sale_date']; ?></td>
            <td><?php echo $row['sold_by']; ?></td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>