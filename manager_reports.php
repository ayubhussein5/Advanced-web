<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$total_stock_value = $conn->query("SELECT IFNULL(SUM(quantity * purchase_price), 0) AS total FROM items")->fetch_assoc()['total'];
$total_sales_revenue = $conn->query("SELECT IFNULL(SUM(quantity_sold * sale_price), 0) AS total FROM sales")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(id) AS total FROM customer_orders")->fetch_assoc()['total'];
$completed_orders = $conn->query("SELECT COUNT(id) AS total FROM customer_orders WHERE status='Completed'")->fetch_assoc()['total'];
$cancelled_orders = $conn->query("SELECT COUNT(id) AS total FROM customer_orders WHERE status='Cancelled'")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(id) AS total FROM customer_orders WHERE status='Pending'")->fetch_assoc()['total'];

$top_items = mysqli_query($conn,"
SELECT items.item_name, SUM(sales.quantity_sold) AS total_sold
FROM sales
LEFT JOIN items ON sales.item_id = items.item_id
GROUP BY items.item_name
ORDER BY total_sold DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports & Analytics</title>

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

.header h1 {
    margin: 0;
    font-size: 36px;
}

.header p {
    color: #9ee7bd;
}

.back-btn {
    background: #047857;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    text-decoration: none;
}

.section-title {
    margin-top: 35px;
    margin-bottom: 15px;
    color: white;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

.card {
    background: #10261f;
    border: 1px solid #1d4f3d;
    border-radius: 12px;
    padding: 25px;
}

.card h3 {
    color: #9ee7bd;
    margin-top: 0;
    font-size: 16px;
}

.value {
    font-size: 30px;
    font-weight: bold;
    color: white;
}

.report-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
    margin-top: 30px;
}

.panel {
    background: #10261f;
    border: 1px solid #1d4f3d;
    border-radius: 12px;
    padding: 25px;
}

.panel h2 {
    margin-top: 0;
}

.summary-box {
    border: 1px dashed #2c6e55;
    padding: 18px;
    margin-top: 15px;
    color: #b7f7d4;
    line-height: 1.7;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #10261f;
    margin-top: 15px;
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

.status-good {
    color: #4ade80;
    font-weight: bold;
}

.status-warning {
    color: #facc15;
    font-weight: bold;
}

.status-danger {
    color: #fb7185;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Reports & Analytics</h1>
            <p>View-only business summary for inventory, sales, and customer orders.</p>
        </div>

        <a href="manager_dashboard.php" class="back-btn">← Back Dashboard</a>
    </div>

    <h2 class="section-title">Financial Summary</h2>

    <div class="cards">
        <div class="card">
            <h3>Total Stock Value</h3>
            <div class="value">Ksh <?php echo number_format($total_stock_value, 2); ?></div>
        </div>

        <div class="card">
            <h3>Total Sales Revenue</h3>
            <div class="value">Ksh <?php echo number_format($total_sales_revenue, 2); ?></div>
        </div>

        <div class="card">
            <h3>Total Customer Orders</h3>
            <div class="value"><?php echo number_format($total_orders); ?></div>
        </div>
    </div>

    <h2 class="section-title">Order Status Summary</h2>

    <div class="cards">
        <div class="card">
            <h3>Completed Orders</h3>
            <div class="value status-good"><?php echo number_format($completed_orders); ?></div>
        </div>

        <div class="card">
            <h3>Pending Orders</h3>
            <div class="value status-warning"><?php echo number_format($pending_orders); ?></div>
        </div>

        <div class="card">
            <h3>Cancelled Orders</h3>
            <div class="value status-danger"><?php echo number_format($cancelled_orders); ?></div>
        </div>
    </div>

    <div class="report-grid">

        <div class="panel">
            <h2>Top Selling Items</h2>

            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Total Quantity Sold</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($top_items)) { ?>
                <tr>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['total_sold']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div class="panel">
            <h2>Manager Report Note</h2>

            <div class="summary-box">
                This report gives a quick view of stock value, sales revenue,
                customer order performance, and top selling items.
                The Inventory Manager can use this page for observation and
                decision making only.
            </div>
        </div>

    </div>

</div>

</body>
</html>