<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] == 'Inventory Manager') {
    header("Location: manager_dashboard.php");
    exit();
}

$item_id = $_GET['id'] ?? null;
$message = '';

if (!$item_id) {
    $message = "❌ Error: No item ID provided for deletion.";
} else {

    $sql_check = "SELECT COUNT(*) AS count FROM sales WHERE item_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $item_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();
    $stmt_check->close();

    $sales_count = $row_check['count'];

    if ($sales_count > 0) {
        $message = "❌ Error: Cannot delete Item ID $item_id because it has $sales_count sales record(s).";
    } else {

        $sql_delete = "DELETE FROM items WHERE item_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $item_id);

        if ($stmt_delete->execute()) {
            $message = "✅ Success! Item ID $item_id was permanently deleted.";
        } else {
            $message = "❌ Error deleting item: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    }
}

$_SESSION['status_message'] = $message;

header("Location: stock_placement.php");
exit();
?>