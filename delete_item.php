<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$item_id = $_GET['id'] ?? null;
$message = '';

if (!$item_id) {
    $message = "❌ Error: No item ID provided for deletion.";
} else {
    // --- 1. Check for Dependent Sales Records ---
    $sql_check = "SELECT COUNT(*) AS count FROM sales WHERE item_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $item_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();
    $stmt_check->close();
    
    $sales_count = $row_check['count'];

    if ($sales_count > 0) {
        // If dependent sales records exist, prevent deletion and inform the user.
        $message = "❌ Error: Cannot delete Item ID $item_id. It has $sales_count associated sales record(s). Please delete the associated sales records first.";
    } else {
        // --- 2. Proceed with Deletion if no sales records are linked ---
        $sql_delete = "DELETE FROM items WHERE item_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $item_id);

        if ($stmt_delete->execute()) {
            $message = "✅ Success! Item ID $item_id was permanently deleted.";
        } else {
            // General error catch (e.g., item ID not found, database connection issue)
            $message = "❌ Error deleting item: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    }
}

// 🔑 CRITICAL FIX 1: Store the message in the session variable
$_SESSION['status_message'] = $message;

// 🔑 CRITICAL FIX 2: Redirect back to the stock list WITHOUT the message in the URL
header("Location: stock_placement.php");
exit();
