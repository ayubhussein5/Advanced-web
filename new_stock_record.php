<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// ============================
// Handle Add New Item Logic (Moved from old dashboard)
// ============================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $stock_code = $_POST['stock_code'];
    $quantity = $_POST['quantity'];
    $purchase_price = $_POST['purchase_price'];
    $selling_price = $_POST['selling_price'];
    $supplier = $_POST['supplier'];
    $date_recorded = date('Y-m-d H:i:s');

    // Basic validation to ensure required fields are not empty
    if (empty($item_name) || empty($stock_code) || $quantity === '' || $purchase_price === '' || $selling_price === '') {
        $message = "❌ Error: All fields except Supplier must be filled.";
    } else {
        // Use a transaction to ensure uniqueness (stock_code) and insertion safety
        $conn->begin_transaction();
        try {
            $sql = "INSERT INTO items (item_name, stock_code, quantity, purchase_price, selling_price, supplier, date_recorded, recorded_by)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiddssi", $item_name, $stock_code, $quantity, $purchase_price, $selling_price, $supplier, $date_recorded, $user_id);

            if (!$stmt->execute()) {
                // Check if the error is due to a duplicate stock_code
                if ($conn->errno == 1062) {
                    throw new Exception("Stock Code '{$stock_code}' already exists. Please use a unique code.");
                }
                throw new Exception($stmt->error);
            }
            
            $conn->commit();
            $message = "✅ New item added successfully.";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "❌ Error adding item: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item to Stock</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-wrapper">
        
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            
            <h2 style="color: #4a69bd;">➕ Add New Item to Inventory</h2>
            <p>Use this form to add a brand new product to your stock list.</p>
            <hr>

            <?php if ($message): ?>
                <div class="message" style="
                    padding: 10px; 
                    margin-bottom: 20px; 
                    border-radius: 5px; 
                    background-color: <?php echo strpos($message, '✅') !== false ? '#d4edda' : '#f8d7da'; ?>; 
                    color: <?php echo strpos($message, '✅') !== false ? '#155724' : '#721c24'; ?>;
                ">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="new_stock_record.php" style="max-width: 600px; margin: 0 auto;">
                
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" id="item_name" placeholder="Laptop, Keyboard, etc." required>
                <br> <br>
                <label for="stock_code">Unique Stock Code (SKU):</label>
                <input type="text" name="stock_code" id="stock_code" placeholder="LAP123, KEY789" required>
                <br> <br>
                <label for="quantity">Initial Stock Quantity:</label>
                <input type="number" name="quantity" id="quantity" placeholder="100" required min="1">
                <br> <br>
                <label for="purchase_price">Purchase Price (Cost):</label>
                <input type="number" step="0.01" name="purchase_price" id="purchase_price" placeholder="500.00" required min="0.01">
                <br> <br>
                <label for="selling_price">Selling Price (Retail):</label>
                <input type="number" step="0.01" name="selling_price" id="selling_price" placeholder="650.00" required min="0.01">
                <br> <br>
                <label for="supplier">Supplier:</label>
                <input type="text" name="supplier" id="supplier" placeholder="TechWorld" required>
                <br> <br>
                <button type="submit">Add New Item to Stock</button>
            </form>

        </div>
        </div>
</body>
</html>