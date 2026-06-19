<?php
include("db.php");

if(isset($_POST['item_id']))
{
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $total_amount = $price * $quantity;

    $sql = "INSERT INTO customer_orders
    (customer_name, phone, item_name, quantity, price, total_amount)
    VALUES
    ('$customer_name', '$phone', '$item_name', '$quantity', '$price', '$total_amount')";

    mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Success | Moonlight POS</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #064e3b, #0f766e);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.success-card {
    background: white;
    width: 430px;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.icon {
    font-size: 70px;
    margin-bottom: 15px;
}

h1 {
    color: #064e3b;
    margin-bottom: 10px;
}

p {
    color: #4b5563;
    line-height: 1.6;
}

.amount {
    background: #ecfdf5;
    color: #047857;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    margin: 20px 0;
}

.btn {
    display: inline-block;
    margin: 8px;
    padding: 12px 18px;
    background: #0f766e;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}

.btn.secondary {
    background: #f59e0b;
}
</style>
</head>

<body>

<div class="success-card">
    <div class="icon">✅</div>

    <h1>Order Submitted Successfully</h1>

    <p>
        Your order has been received by Moonlight POS.
        Please check your order status using your phone number.
    </p>

    <?php if(isset($total_amount)) { ?>
        <div class="amount">
            Total Amount: Ksh <?php echo number_format($total_amount, 2); ?>
        </div>
    <?php } ?>

    <a href="shop_items.php" class="btn">Continue Shopping</a>
    <a href="order_status.php" class="btn secondary">Track Order</a>
</div>

</body>
</html>