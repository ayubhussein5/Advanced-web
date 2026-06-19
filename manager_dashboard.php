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

$total_stock_qty = $conn->query("SELECT IFNULL(SUM(quantity), 0) AS total FROM items")->fetch_assoc()['total'];
$total_items_sold = $conn->query("SELECT IFNULL(SUM(quantity_sold), 0) AS total FROM sales")->fetch_assoc()['total'];
$total_sale_count = $conn->query("SELECT COUNT(sale_id) AS total FROM sales")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(id) AS total FROM customer_orders")->fetch_assoc()['total'];
$total_items = $conn->query("SELECT COUNT(item_id) AS total FROM items WHERE quantity > 0")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory Manager Dashboard</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #061f18;
    color: #eafaf1;
}

.wrapper {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 320px;
    background: #131a1f;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding: 25px;
    border-right: 1px solid #24483b;
}

.logo {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 5px;
}

.subtitle {
    color: #4ade80;
    font-size: 13px;
    margin-bottom: 40px;
}

.menu-links a {
    display: block;
    color: #b7d8c6;
    text-decoration: none;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    font-size: 16px;
}

.menu-links a:hover {
    background: #12382c;
    color: white;
}

.sidebar-footer {
    margin-top: auto;
}

.logout-btn {
    display: block;
    text-align: center;
    padding: 15px;
    background: #8b4513;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-size: 18px;
    font-weight: bold;
}

.logout-btn:hover {
    background: #a0522d;
}

.content {
    flex: 1;
    padding: 35px;
    background: linear-gradient(135deg, #062e22, #02140f);
}

.header h1 {
    font-size: 38px;
    margin: 0;
    color: #ffffff;
}

.header p {
    color: #7dd3a8;
    font-size: 17px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-top: 35px;
}

.card {
    background: rgba(16, 64, 48, 0.8);
    border: 1px solid #145c43;
    border-radius: 10px;
    padding: 25px;
    min-height: 140px;
}

.card.orange {
    background: rgba(78, 55, 21, 0.8);
    border-color: #7c4a12;
}

.card.red {
    background: rgba(70, 44, 32, 0.8);
    border-color: #8b4b20;
}

.card h3 {
    color: #9ee7bd;
    font-size: 16px;
    margin-bottom: 15px;
}

.card .value {
    font-size: 35px;
    font-weight: bold;
    color: white;
}

.panels {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-top: 35px;
}

.panel {
    background: rgba(16, 38, 31, 0.95);
    border: 1px solid #1d4f3d;
    border-radius: 10px;
    padding: 25px;
    min-height: 260px;
}

.panel h2 {
    margin-top: 0;
    color: white;
}

.panel a {
    display: block;
    background: #047857;
    color: white;
    text-decoration: none;
    padding: 14px;
    margin-top: 15px;
    border-radius: 8px;
    text-align: center;
}

.panel a:hover {
    background: #059669;
}

.note {
    border: 1px dashed #2c6e55;
    padding: 40px;
    text-align: center;
    color: #9ecfba;
    margin-top: 20px;
}
</style>
</head>

<body>

<div class="wrapper">

    <div class="sidebar">
        <div class="logo">🌿 Moonlight POS</div>
        <div class="subtitle">INVENTORY MANAGER PANEL</div>

        <div class="menu-links">
            <a href="manager_dashboard.php">▦ Operations Overview</a>
            <a href="manager_stock_view.php">📦 Stock Observation</a>
            <a href="manager_sales_view.php">📈 Sales Performance</a>
            <a href="manager_orders_view.php">🛒 Customer Orders Monitor</a>
             <a href="manager_reports.php">📑 Reports & Analytics</a>
        </div>

        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn">⏻ Sign Out</a>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <h1>Inventory Operations Center</h1>
            <p>Monitor stock levels, sales activities, and customer orders in real time.</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Quantity in Stock</h3>
                <div class="value"><?php echo number_format($total_stock_qty); ?></div>
            </div>

            <div class="card orange">
                <h3>Unique Items on Shelf</h3>
                <div class="value"><?php echo number_format($total_items); ?></div>
            </div>

            <div class="card red">
                <h3>Total Quantity Sold</h3>
                <div class="value"><?php echo number_format($total_items_sold); ?></div>
            </div>

            <div class="card">
                <h3>Pending / Received Orders</h3>
                <div class="value"><?php echo number_format($total_orders); ?></div>
            </div>
        </div>

        <div class="panels">
            <div class="panel">
                <h2>Stock Observation</h2>
                <div class="note">View available stock, item codes, suppliers, purchase prices, and selling prices.</div>
                <a href="manager_stock_view.php">View Stock Inventory</a>
            </div>

            <div class="panel">
                <h2>Sales Performance</h2>
                <div class="note">Monitor sales records, quantities sold, sales prices, and transaction dates.</div>
                <a href="manager_sales_view.php">View Sales Records</a>
            </div>

            <div class="panel">
                <h2>Customer Orders Monitor</h2>
                <div class="note">Observe customer orders, quantities requested, order totals, and order status.</div>
                <a href="manager_orders_view.php">View Customer Orders</a>
            </div>
        </div>

    </div>

</div>

</body>
</html>