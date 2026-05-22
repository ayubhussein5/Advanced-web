<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$user_id = $_SESSION['user_id'];

// Fetch available items for the dropdown menu (only items with stock > 0)
// This query runs BEFORE the POST check so that the dropdown reflects current stock.
$items_result = $conn->query("SELECT item_id, item_name, quantity, selling_price FROM items WHERE quantity > 0 ORDER BY item_name ASC");
$items = $items_result ? $items_result->fetch_all(MYSQLI_ASSOC) : [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = (int)$_POST['item_id'];
    $quantity_sold = (int)$_POST['quantity_sold'];
    $sale_price = (float)$_POST['sale_price'];
    
    // --- TRANSACTION START ---
    $conn->begin_transaction();
    $success = true;

    try {
        // 1. Check current stock quantity (FOR UPDATE locks the row to prevent double-selling)
        $stmt_check = $conn->prepare("SELECT quantity FROM items WHERE item_id = ? FOR UPDATE"); 
        $stmt_check->bind_param("i", $item_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $item_stock = $result_check->fetch_assoc();
        $stmt_check->close();

        if (!$item_stock || $item_stock['quantity'] < $quantity_sold) {
            throw new Exception("Insufficient stock. Only " . ($item_stock['quantity'] ?? 0) . " items available.");
        }
        if ($quantity_sold <= 0 || $sale_price <= 0) {
            throw new Exception("Quantity sold and Sale Price must be positive values.");
        }

        // 2. Record the sale into the 'sales' table
        $stmt_sale = $conn->prepare("INSERT INTO sales (item_id, quantity_sold, sale_price, sold_by) VALUES (?, ?, ?, ?)");
        $stmt_sale->bind_param("iidi", $item_id, $quantity_sold, $sale_price, $user_id);
        $stmt_sale->execute();
        $stmt_sale->close();

        // 3. Deduct stock quantity from the 'items' table
        $new_quantity = $item_stock['quantity'] - $quantity_sold;
        $stmt_update = $conn->prepare("UPDATE items SET quantity = ? WHERE item_id = ?");
        $stmt_update->bind_param("ii", $new_quantity, $item_id);
        $stmt_update->execute();
        $stmt_update->close();

        $conn->commit();
        $message = "✅ Success! Sale recorded and stock updated.";
        
        // Re-fetch items after a successful sale to update the dropdown on the same page
        $items_result = $conn->query("SELECT item_id, item_name, quantity, selling_price FROM items WHERE quantity > 0 ORDER BY item_name ASC");
        $items = $items_result ? $items_result->fetch_all(MYSQLI_ASSOC) : [];


    } catch (Exception $e) {
        $conn->rollback();
        $message = "❌ Transaction Failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Items Sold</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Add some specific styling for the form within the new layout */
        .sale-form {
            max-width: 600px; 
            margin: 0 auto; 
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    
    <div class="main-wrapper">
    
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            
            <h2 style="color: #4a69bd;">💰 Record New Sale Transaction</h2>
            <p style="font-size: 1em; color: #555;">
                Log a sale to update inventory and track revenue.
            </p>
            <hr>

            <?php if ($message): ?>
                <div class="message" style="
                    padding: 15px; 
                    margin-bottom: 20px; 
                    border-radius: 5px; 
                    background-color: <?php echo strpos($message, '✅') !== false ? '#d4edda' : '#f8d7da'; ?>; 
                    color: <?php echo strpos($message, '✅') !== false ? '#155724' : '#721c24'; ?>;
                ">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($items)): ?>
                <div class="message" style="background-color: #ffc; color: #8a6d3b; padding: 15px; border-radius: 5px;">
                    No items are currently in stock. Add stock first using the sidebar link.
                </div>
            <?php else: ?>
            <form action="items_sold_record.php" method="post" class="sale-form">
                
                <label for="item_id" style="text-align: left; font-weight: bold; margin-bottom: -10px;">Select Item:</label>
                <select name="item_id" id="item_id" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required onchange="updateSalePrice(this)"> 
                    <option value="">-- Choose Item --</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?php echo $item['item_id']; ?>" data-price="<?php echo htmlspecialchars($item['selling_price']); ?>" data-qty="<?php echo htmlspecialchars($item['quantity']); ?>">
                            <?php echo htmlspecialchars($item['item_name']); ?> (Stock: <?php echo $item['quantity']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select> <br> <br>

                <label for="qty_sold">Quantity Sold:</label>
                <input type="number" name="quantity_sold" placeholder="Quantity Sold" required min="1" id="qty_sold">
                <br> <br>
                <label for="sale_price_input">Final Sale Price (per item):</label>
                <input type="number" name="sale_price" placeholder="Final Sale Price per item" required min="0.01" step="0.01" id="sale_price_input">
                <br> <br> 
                <button type="submit" class="btn" style="background-color: #2ecc71;">Finalize Sale</button>
            </form>
            <?php endif; ?>

        </div>
        </div>
    
    <script>
    // JavaScript function to automatically populate the sale price and set max quantity
    function updateSalePrice(select) {
        const selectedOption = select.options[select.selectedIndex];
        
        // Ensure that a valid option is selected (not the "-- Choose Item --" option)
        if (selectedOption.value) {
             const defaultPrice = selectedOption.getAttribute('data-price');
             const maxQty = selectedOption.getAttribute('data-qty');
             
             document.getElementById('sale_price_input').value = defaultPrice;
             document.getElementById('qty_sold').setAttribute('max', maxQty);
        } else {
             // Reset fields if default option is selected
             document.getElementById('sale_price_input').value = '';
             document.getElementById('qty_sold').removeAttribute('max');
        }
        document.getElementById('qty_sold').value = ''; // Clear quantity input on item change
    }
    </script>
</body>
</html>