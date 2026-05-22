<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$item_id = $_GET['id'] ?? null; // Get item ID from URL

// --- 1. Handle Form Submission (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $item_name = trim($_POST['item_name']);
    $stock_code = trim($_POST['stock_code']);
    $quantity = (int)$_POST['quantity'];
    $purchase_price = (float)$_POST['purchase_price'];
    $selling_price = (float)$_POST['selling_price'];
    $supplier = trim($_POST['supplier']);

    if (empty($item_name) || empty($stock_code) || $quantity < 0 || $purchase_price <= 0) {
        $message = "❌ Error: Please check fields. Quantity cannot be negative and prices must be positive.";
    } else {
        $sql = "UPDATE items SET item_name=?, stock_code=?, quantity=?, purchase_price=?, selling_price=?, supplier=? WHERE item_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiddsi", $item_name, $stock_code, $quantity, $purchase_price, $selling_price, $supplier, $item_id);

        if ($stmt->execute()) {
            $message = "✅ Success! Item ID $item_id updated successfully.";
        } else {
            // Check for duplicate key error (if stock_code is unique)
            if ($conn->errno == 1062) {
                $message = "❌ Error: Stock Code '{$stock_code}' already exists for another item. Please use a unique code.";
            } else {
                $message = "❌ Error updating item: " . $stmt->error;
            }
        }
    }
}

// --- 2. Fetch Current Item Data (READ for display) ---
$item = null;
if ($item_id) {
    $sql_fetch = "SELECT * FROM items WHERE item_id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $item_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    $item = $result_fetch->fetch_assoc();
    $stmt_fetch->close();
}

if (!$item && $item_id) {
    $message = "❌ Error: Item not found.";
}

// Re-fetch the data after a successful POST to show the updated values
if (isset($_POST['item_id']) && strpos($message, '✅ Success!') !== false) {
    $sql_re_fetch = "SELECT * FROM items WHERE item_id = ?";
    $stmt_re_fetch = $conn->prepare($sql_re_fetch);
    $stmt_re_fetch->bind_param("i", $item_id);
    $stmt_re_fetch->execute();
    $result_re_fetch = $stmt_re_fetch->get_result();
    $item = $result_re_fetch->fetch_assoc();
    $stmt_re_fetch->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stock Item (ID: <?php echo htmlspecialchars($item_id); ?>)</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Specific styling for the edit form layout */
        .edit-form-wrapper {
            max-width: 800px; /* Wider container for better spacing */
            margin: 20px auto; 
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }

        /* Use CSS Grid for the two-column layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two equal columns */
            gap: 20px 30px; /* Vertical and horizontal space */
        }

        /* Ensure labels and inputs are stacked within grid cells */
        .form-grid label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-grid input[type="text"],
        .form-grid input[type="number"] {
            width: 100%; /* Make inputs fill their grid column */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
        }
        
        .edit-form-wrapper .btn {
            grid-column: 1 / -1; /* Make the button span both columns */
            margin-top: 20px;
            padding: 15px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    
    <!-- 1. START the main wrapper for sidebar layout -->
    <div class="main-wrapper">
    
        <!-- 2. INCLUDE the sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- 3. START the content area -->
        <div class="content">
            
            <h2 style="color: #f39c12;">✏️ Edit Stock Item (ID: <?php echo htmlspecialchars($item_id); ?>)</h2>
            <p><a href="stock_placement.php" class="back-link" style="text-decoration: none; color: #4a69bd; font-weight: bold;">← Back to Stock Inventory</a></p>
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

            <?php if ($item): ?>
            <div class="edit-form-wrapper">
                <form action="edit_item.php?id=<?php echo $item['item_id']; ?>" method="post">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['item_id']); ?>">
                    
                    <div class="form-grid">
                        
                        <!-- Row 1 -->
                        <div>
                            <label for="item_name">Item Name:</label>
                            <input type="text" name="item_name" id="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
                        </div>
                        <div>
                            <label for="stock_code">Unique Stock Code:</label>
                            <input type="text" name="stock_code" id="stock_code" value="<?php echo htmlspecialchars($item['stock_code']); ?>" required>
                        </div>

                        <!-- Row 2 -->
                        <div>
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required min="0">
                        </div>
                        <div>
                            <label for="supplier">Supplier Name:</label>
                            <input type="text" name="supplier" id="supplier" value="<?php echo htmlspecialchars($item['supplier']); ?>">
                        </div>
                        
                        <!-- Row 3 -->
                        <div>
                            <label for="purchase_price">Purchase Price ($):</label>
                            <input type="number" name="purchase_price" id="purchase_price" value="<?php echo htmlspecialchars($item['purchase_price']); ?>" required min="0.01" step="0.01">
                        </div>
                        <div>
                            <label for="selling_price">Target Selling Price ($):</label>
                            <input type="number" name="selling_price" id="selling_price" value="<?php echo htmlspecialchars($item['selling_price']); ?>" required min="0.01" step="0.01">
                        </div>

                        <!-- Button Row -->
                        <button type="submit" class="btn" style="background-color: #f39c12;">Update Stock</button>
                    </div>
                </form>
            </div>
            <?php else: ?>
                 <p style="text-align: center; color: #e74c3c;">The item requested for editing could not be found.</p>
            <?php endif; ?>

        </div>
        <!-- 4. END the content area and main wrapper -->
    </div>
</body>
</html>
