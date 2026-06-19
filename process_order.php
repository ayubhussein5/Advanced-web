<?php
session_start();
include("db.php");
if (isset($_SESSION['role']) && $_SESSION['role'] == 'Inventory Manager') {
    header("Location: manager_orders_view.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = $_GET['id'];
    $new_status = $_GET['status'];

    $order_result = mysqli_query($conn, "SELECT * FROM customer_orders WHERE id='$id'");
    $order = mysqli_fetch_assoc($order_result);

    $item_name = $order['item_name'];
    $order_quantity = $order['quantity'];
    $sale_price = $order['price'];
    $old_status = $order['status'];

    $item_result = mysqli_query($conn, "SELECT item_id, quantity FROM items WHERE item_name='$item_name'");
    $item = mysqli_fetch_assoc($item_result);

    $item_id = $item['item_id'];
    $available_stock = $item['quantity'];
    $sold_by = $_SESSION['user_id'] ?? 1;

    if ($old_status != "Completed" && $new_status == "Completed") {

        if ($available_stock >= $order_quantity) {

            mysqli_query($conn, "UPDATE items 
                                 SET quantity = quantity - $order_quantity 
                                 WHERE item_id='$item_id'");

            mysqli_query($conn, "INSERT INTO sales
                                (item_id, quantity_sold, sale_price, sold_by)
                                VALUES
                                ('$item_id', '$order_quantity', '$sale_price', '$sold_by')");
        } else {
            echo "<h2>Not enough stock to complete this order.</h2>";
            echo "<a href='received_orders.php'>Go Back</a>";
            exit();
        }
    }

    if ($old_status == "Completed" && $new_status == "Cancelled") {

        mysqli_query($conn, "UPDATE items 
                             SET quantity = quantity + $order_quantity 
                             WHERE item_id='$item_id'");

        mysqli_query($conn, "DELETE FROM sales 
                             WHERE item_id='$item_id' 
                             AND quantity_sold='$order_quantity' 
                             AND sale_price='$sale_price'
                             ORDER BY sale_id DESC 
                             LIMIT 1");
    }

    mysqli_query($conn, "UPDATE customer_orders 
                         SET status='$new_status' 
                         WHERE id='$id'");

    header("Location: received_orders.php");
    exit();
}
?>