<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ensure username is set in session for display
$username = $_SESSION['username'] ?? 'User';

// --- FETCH DATA FOR DASHBOARD CARDS ---
// 1. Total Items in Stock (Total quantity from all items)
$sql_stock_qty = "SELECT IFNULL(SUM(quantity), 0) AS total_stock_qty FROM items";
$result_stock_qty = $conn->query($sql_stock_qty);
$total_stock_qty = $result_stock_qty->fetch_assoc()['total_stock_qty'] ?? 0;

// 2. Total Items Sold (Total quantity sold across all sales)
$sql_items_sold = "SELECT IFNULL(SUM(quantity_sold), 0) AS total_items_sold FROM sales";
$result_items_sold = $conn->query($sql_items_sold);
$total_items_sold = $result_items_sold->fetch_assoc()['total_items_sold'] ?? 0;

// 3. Number of Unique Stock Items (Total item rows with positive stock)
$sql_item_count = "SELECT COUNT(item_id) AS total_item_count FROM items WHERE quantity > 0";
$result_item_count = $conn->query($sql_item_count);
$total_item_count = $result_item_count->fetch_assoc()['total_item_count'] ?? 0;

// 4. Total Sales Transactions (Total rows in sales table)
$sql_sale_count = "SELECT COUNT(sale_id) AS total_sale_count FROM sales";
$result_sale_count = $conn->query($sql_sale_count);
$total_sale_count = $result_sale_count->fetch_assoc()['total_sale_count'] ?? 0;

// --- END FETCH DATA ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Moonlight Enterprises</title> 
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* DASHBOARD CARDS STYLING */
        .card-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-left: 5px solid;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card h4 {
            margin: 0 0 10px 0;
            color: #555;
            font-size: 1.1em;
        }
        .card .value {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card .link-btn {
            display: block;
            margin-top: 15px;
            padding: 8px;
            background-color: #4a69bd;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            transition: background-color 0.3s;
        }
        .card .link-btn:hover {
            background-color: #3b53a4;
        }
        
        /* Card Colors */
        .stock-card { border-left-color: #2ecc71; }
        .item-card { border-left-color: #f39c12; }
        .sold-card { border-left-color: #e74c3c; }
        .trans-card { border-left-color: #3498db; }
        
        /* General content header */
        .welcome-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            padding-bottom: 10px;
            text-align: left;
        }
        /* Ensure the body is aligned left for the sidebar layout */
        body { align-items: flex-start; }
    </style>
</head>
<body>
    <div class="main-wrapper">
        
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <div class="welcome-header">
                <h1 style="color: #4a69bd; margin: 0;">Dashboard Overview</h1>
                <p style="font-size: 1.1em; color: #555;">
                    Welcome, **<?php echo htmlspecialchars($username); ?>**. Quick system snapshot:
                </p>
            </div>
            
            <div class="card-container">
                
                <div class="card stock-card">
                    <h4>Total Quantity in Stock</h4>
                    <div class="value" style="color: #2ecc71;"><?php echo number_format($total_stock_qty); ?></div>
                    <a href="stock_placement.php" class="link-btn" style="background-color: #2ecc71;">View Stock Inventory</a>
                </div>

                <div class="card item-card">
                    <h4>Unique Items on Shelf</h4>
                    <div class="value" style="color: #f39c12;"><?php echo number_format($total_item_count); ?></div>
                    <a href="new_stock_record.php" class="link-btn" style="background-color: #f39c12;">Add New Item</a>
                </div>

                <div class="card sold-card">
                    <h4>Total Quantity Sold</h4>
                    <div class="value" style="color: #e74c3c;"><?php echo number_format($total_items_sold); ?></div>
                    <a href="view_sales_records.php" class="link-btn" style="background-color: #e74c3c;">View Sales Records</a>
                </div>

                <div class="card trans-card">
                    <h4>Total Transactions Logged</h4>
                    <div class="value" style="color: #3498db;"><?php echo number_format($total_sale_count); ?></div>
                    <a href="items_sold_record.php" class="link-btn" style="background-color: #3498db;">Record New Sale</a>
                </div>

            </div>

        </div>
    </div>
</body>
</html>