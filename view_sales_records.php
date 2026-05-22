<?php
session_start();
require 'db.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

// SQL to fetch sales records, joining sales, items, and users tables
$sql = "SELECT s.sale_id, s.quantity_sold, s.sale_price, s.sale_date,
               i.item_name, i.stock_code, 
               u.username as sold_by_user
        FROM sales s
        JOIN items i ON s.item_id = i.item_id
        JOIN users u ON s.sold_by = u.id
        ORDER BY s.sale_date DESC"; // Most recent sales first

$result = $conn->query($sql);

if (!$result) {
    $message = "Error fetching sales data: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sales History</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* This style block remains for page-specific element styling */
        .sales-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.9em; text-align: left; }
        .sales-table th, .sales-table td { border: 1px solid #ddd; padding: 8px; }
        .sales-table th { background-color: #d4edda; color: #155724; text-align: center; }
        .sales-table tr:nth-child(even) { background-color: #f9f9f9; }
        /* Remove the old .container styles and .back-link since the sidebar handles navigation */
    </style>
</head>
<body>
    
    <div class="main-wrapper">
    
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            
            <h2 style="color: #4a69bd;">📈 Sales History and Transaction Records</h2>
            <p style="font-size: 1em; color: #555;">
                Detailed log of all completed sales transactions.
            </p>
            
            <hr>

            <?php if ($message): ?>
                <div class="message" style="
                    padding: 10px; 
                    margin-bottom: 20px; 
                    border-radius: 5px; 
                    background-color: #f8d7da; 
                    color: #721c24;
                ">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Item Name</th>
                            <th>Stock Code</th>
                            <th>Qty Sold</th>
                            <th>Sale Price (per item)</th>
                            <th>Total Sale</th>
                            <th>Date & Time</th>
                            <th>Sold By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_revenue_overall = 0;
                        while ($row = $result->fetch_assoc()): 
                            $total_sale = $row['quantity_sold'] * $row['sale_price'];
                            $total_revenue_overall += $total_sale;
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo htmlspecialchars($row['sale_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($row['stock_code']); ?></td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($row['quantity_sold']); ?></td>
                            <td>Ksh <?php echo number_format($row['sale_price'], 2); ?></td>
                            <td style="font-weight: bold;">Ksh <?php echo number_format($total_sale, 2); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($row['sale_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['sold_by_user']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <tr style="background-color: #ccffcc;">
                            <td colspan="5" style="text-align: right; font-weight: bold;">OVERALL TOTAL SALES:</td>
                            <td style="font-weight: bold;">Ksh <?php echo number_format($total_revenue_overall, 2); ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="message" style="background-color: #ffc; color: #8a6d3b; padding: 15px; border-radius: 5px;">
                    No sales transactions have been recorded yet.
                </div>
            <?php endif; ?>

        </div>
        </div>
</body>
</html>