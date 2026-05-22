<?php
session_start(); // CRITICAL: Must be the very first line
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- START: MESSAGE RETRIEVAL LOGIC (Reads the message from the delete script) ---
$message = '';
// Check if the delete script stored a message in the session
if (isset($_SESSION['status_message'])) {
    $message = $_SESSION['status_message'];
    // ⚠️ IMPORTANT: Clear the message immediately so it doesn't show up on refresh
    unset($_SESSION['status_message']); 
}
// --- END: MESSAGE RETRIEVAL LOGIC ---


// SQL to fetch all item records, joining items and users tables to show who recorded it
$sql = "SELECT i.*, u.username as recorded_by_user
        FROM items i
        JOIN users u ON i.recorded_by = u.id
        ORDER BY i.quantity DESC, i.item_name ASC"; 

$result = $conn->query($sql);

if (!$result) {
    // If database fetch fails, use $message to display the error
    $message = "Error fetching inventory data: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Inventory</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Specific styling for the inventory table */
        .inventory-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 0.9em; 
            text-align: left; 
        }
        .inventory-table th, .inventory-table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
        }
        .inventory-table th { 
            background-color: #ecf0f1; 
            color: #2c3e50; 
            text-align: center; 
        }
        .inventory-table tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        /* Style for low stock warning */
        .low-stock {
            background-color: #f9e3e3; /* Light Red */
            font-weight: bold;
            color: #c0392b; /* Dark Red */
        }
    </style>
</head>
<body>
    
    <div class="main-wrapper">
    
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            
            <h2 style="color: #4a69bd;">📦 Current Stock Inventory</h2>
            <p style="font-size: 1em; color: #555;">
                Detailed view of all items, quantities, and pricing. Items with quantity less than or equal to 5 are flagged.
            </p>
            
            <hr>

            <?php if ($message): // THIS BLOCK DISPLAYS THE MESSAGE ?>
                <?php 
                    // Determine style based on success/error indicator (✅ or ❌)
                    $bg_color = strpos($message, '✅') !== false ? '#d4edda' : '#f8d7da'; // Green for success
                    $text_color = strpos($message, '✅') !== false ? '#155724' : '#721c24'; // Dark green/red
                ?>
                <div class="message" style="
                    padding: 15px; 
                    margin-bottom: 20px; 
                    border-radius: 5px; 
                    background-color: <?php echo $bg_color; ?>; 
                    color: <?php echo $text_color; ?>;
                ">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Stock Code</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Supplier</th>
                            <th>Date Recorded</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($row = $result->fetch_assoc()): 
                            // Apply low stock warning style
                            $row_class = ($row['quantity'] <= 5) ? 'low-stock' : '';
                        ?>
                        <tr class="<?php echo $row_class; ?>">
                            <td><?php echo $row['item_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['stock_code']); ?></td>
                            <td style="text-align: center;"><?php echo $row['quantity']; ?></td>
                            <td>Ksh <?php echo number_format($row['purchase_price'], 2); ?></td>
                            <td>Ksh <?php echo number_format($row['selling_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($row['date_recorded'])); ?></td>
                            <td><?php echo htmlspecialchars($row['recorded_by_user']); ?></td>
                            <td style="text-align: center;">
                                <a href="edit_item.php?id=<?php echo $row['item_id']; ?>" style="color: #3498db; text-decoration: none; margin-right: 10px;">✏️ Edit</a>
                                <a href="delete_item.php?id=<?php echo $row['item_id']; ?>" 
                                   onclick="return confirm('WARNING! Are you sure you want to delete <?php echo htmlspecialchars($row['item_name']); ?>? This cannot be undone.')" 
                                   style="color: #e74c3c; text-decoration: none;">❌ Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="message" style="background-color: #ffc; color: #8a6d3b; padding: 15px; border-radius: 5px;">
                    The inventory is currently empty. Please add items using the "Add New Item" link.
                </div>
            <?php endif; ?>

        </div>
        </div>
</body>
</html>
