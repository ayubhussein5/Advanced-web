<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Status | Moonlight POS</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f7fb;
    color: #1f2937;
}

.navbar {
    background: white;
    padding: 18px 60px;
    display: flex;
    justify-content: space-between;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.logo {
    font-size: 25px;
    font-weight: bold;
    color: #0f766e;
}

.navbar a {
    margin-left: 25px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

.hero {
    padding: 55px 70px;
    background: linear-gradient(135deg, #064e3b, #0f766e);
    color: white;
}

.container {
    padding: 45px 70px;
}

.search-box {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    max-width: 600px;
}

input {
    width: 100%;
    padding: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    margin-top: 10px;
}

button {
    margin-top: 15px;
    padding: 13px 25px;
    background: #0f766e;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
}

.order-card {
    background: white;
    margin-top: 25px;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.status {
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: bold;
}

.Pending { background: #fef3c7; color: #92400e; }
.Processing { background: #dbeafe; color: #1d4ed8; }
.Completed { background: #dcfce7; color: #166534; }
.Cancelled { background: #fee2e2; color: #991b1b; }

.footer {
    text-align: center;
    background: #111827;
    color: white;
    padding: 25px;
    margin-top: 50px;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">🌙 Moonlight POS</div>
    <div>
        <a href="index.php">Home</a>
        <a href="shop_items.php">Shop Items</a>
        <a href="order_status.php">Order Status</a>
        
        <?php if(isset($_SESSION['username'])) { ?>
        <a href="logout.php">Logout</a>
        <?php } else { ?>
        <a href="login.php">Sign In</a>
        <a href="register.php">Register</a>
        <?php } ?>
    </div>
</div>

<div class="hero">
    <h1>📦 Order Tracking Center</h1>
    <p>Enter your phone number to check your order progress.</p>
</div>

<div class="container">

    <div class="search-box">
        <h2>Check Your Order</h2>

        <form method="POST">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="Enter phone number used during order" required>
            <button type="submit">Track Order</button>
        </form>
    </div>

    <?php
    if(isset($_POST['phone'])) {
        $phone = $_POST['phone'];

        $result = mysqli_query($conn,
        "SELECT * FROM customer_orders 
         WHERE phone='$phone' 
         ORDER BY order_date DESC");

        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
    ?>

    <div class="order-card">
        <h2>Order #<?php echo $row['id']; ?></h2>
        <p><b>Customer:</b> <?php echo $row['customer_name']; ?></p>
        <p><b>Item:</b> <?php echo $row['item_name']; ?></p>
        <p><b>Quantity:</b> <?php echo $row['quantity']; ?></p>
        <p><b>Total Amount:</b> Ksh <?php echo number_format($row['total_amount'], 2); ?></p>
        <p><b>Order Date:</b> <?php echo $row['order_date']; ?></p>
        <p>
            <b>Status:</b>
            <span class="status <?php echo $row['status']; ?>">
                <?php echo $row['status']; ?>
            </span>
        </p>
    </div>

    <?php
            }
        } else {
            echo "<div class='order-card'><h3>No order found for this phone number.</h3></div>";
        }
    }
    ?>

</div>

<div class="footer">
    <p>&copy; 2026 Moonlight POS. All Rights Reserved.</p>
</div>

</body>
</html>