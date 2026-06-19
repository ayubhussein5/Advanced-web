<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

$result = mysqli_query($conn, "SELECT * FROM items WHERE quantity > 0 ORDER BY item_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Shop Items | Moonlight POS</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f7fb;
    color: #1f2937;
}

.navbar {
    background: white;
    padding: 18px 65px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    position: sticky;
    top: 0;
    z-index: 100;
}

.logo {
    font-size: 26px;
    font-weight: bold;
    color: #0f766e;
}

.navbar a {
    margin-left: 24px;
    text-decoration: none;
    color: #374151;
    font-weight: bold;
}

.navbar a:hover {
    color: #0f766e;
}

.hero {
    padding: 70px;
    background: linear-gradient(135deg, #064e3b, #0f766e, #10b981);
    color: white;
    text-align: center;
    animation: fadeDown 1s ease;
}

.hero h1 {
    font-size: 48px;
    margin-bottom: 10px;
}

.hero p {
    font-size: 19px;
}

.container {
    padding: 50px 70px;
}

.products {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

.card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: 0.35s ease;
    animation: fadeUp 0.9s ease;
    border: 1px solid #e5e7eb;
}

.card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 14px 35px rgba(15,118,110,0.18);
}

.product-icon{
    font-size:60px;
    width:100px;
    height:100px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#ecfdf5,#d1fae5);
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.card h3 {
    color: #064e3b;
    font-size: 25px;
    margin-bottom: 10px;
}

.badge {
    display: inline-block;
    background: #dcfce7;
    color: #166534;
    padding: 7px 14px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 13px;
}

.info {
    color: #4b5563;
    line-height: 1.7;
}

.price {
    font-size: 25px;
    color: #0f766e;
    font-weight: bold;
    margin: 15px 0;
}

.form-box {
    margin-top: 18px;
    padding-top: 15px;
    border-top: 1px solid #e5e7eb;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 12px;
    color: #374151;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    outline: none;
    transition: 0.3s;
}

input:focus {
    border-color: #0f766e;
    box-shadow: 0 0 0 3px rgba(15,118,110,0.15);
}

button {
    width: 100%;
    margin-top: 20px;
    padding: 14px;
    border: none;
    background: linear-gradient(135deg, #0f766e, #10b981);
    color: white;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #064e3b, #0f766e);
}

.footer {
    text-align: center;
    background: #111827;
    color: white;
    padding: 25px;
    margin-top: 50px;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(35px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeDown {
    from {
        opacity: 0;
        transform: translateY(-25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes floatIcon {
    0% { transform: translateY(0); }
    50% { transform: translateY(-7px); }
    100% { transform: translateY(0); }
}

@media(max-width: 1000px) {
    .products {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 700px) {
    .products {
        grid-template-columns: 1fr;
    }

    .navbar {
        flex-direction: column;
        padding: 15px;
    }

    .navbar a {
        display: inline-block;
        margin: 8px;
    }

    .hero, .container {
        padding: 35px 25px;
    }

    .hero h1 {
        font-size: 34px;
    }
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
    <h1>Shop Available Items</h1>
    <p>Browse products, place your order, and track your order status easily.</p>
</div>

<div class="container">
    <div class="products">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <div class="card">
            <?php
$icon = "📦";

if(stripos($row['item_name'], 'Laptop') !== false)
    $icon = "💻";

elseif(stripos($row['item_name'], 'MacBook') !== false)
    $icon = "💻";

elseif(stripos($row['item_name'], 'Mouse') !== false)
    $icon = "🖱️";

elseif(stripos($row['item_name'], 'Keyboard') !== false)
    $icon = "⌨️";

elseif(stripos($row['item_name'], 'Printer') !== false)
    $icon = "🖨️";

elseif(stripos($row['item_name'], 'Router') !== false)
    $icon = "📡";

elseif(stripos($row['item_name'], 'Headset') !== false)
    $icon = "🎧";

elseif(stripos($row['item_name'], 'Disk') !== false)
    $icon = "💾";

elseif(stripos($row['item_name'], 'SSD') !== false)
    $icon = "💾";

elseif(stripos($row['item_name'], 'Flash') !== false)
    $icon = "💽";

elseif(stripos($row['item_name'], 'USB') !== false)
    $icon = "🔌";

elseif(stripos($row['item_name'], 'Monitor') !== false)
    $icon = "🖥️";

elseif(stripos($row['item_name'], 'Phone') !== false)
    $icon = "📱";

elseif(stripos($row['item_name'], 'iPhone') !== false)
    $icon = "📱";

elseif(stripos($row['item_name'], 'Samsung Galaxy') !== false)
    $icon = "📱";

elseif(stripos($row['item_name'], 'Projector') !== false)
    $icon = "📽️";

elseif(stripos($row['item_name'], 'Switch') !== false)
    $icon = "🌐";

elseif(stripos($row['item_name'], 'Cable') !== false)
    $icon = "🔗";

elseif(stripos($row['item_name'], 'CCTV') !== false)
    $icon = "📹";

elseif(stripos($row['item_name'], 'Attendance') !== false)
    $icon = "🕒";

elseif(stripos($row['item_name'], 'Power Bank') !== false)
    $icon = "🔋";

elseif(stripos($row['item_name'], 'Webcam') !== false)
    $icon = "📷";

elseif(stripos($row['item_name'], 'Server') !== false)
    $icon = "🖧";
?>

<div class="product-icon" title="<?php echo $row['item_name']; ?>">
    <?php echo $icon; ?>
</div>

            <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>

            <p><span class="badge">Available in Stock</span></p>

            <div class="info">
                <p>Stock Code: <b><?php echo htmlspecialchars($row['stock_code']); ?></b></p>
                <p>Available Quantity: <b><?php echo htmlspecialchars($row['quantity']); ?></b></p>
            </div>

            <p class="price">Ksh <?php echo number_format($row['selling_price'], 2); ?></p>

            <div class="form-box">
                <form action="place_order.php" method="POST">
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($row['item_name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $row['selling_price']; ?>">

                    <label>Customer Name</label>
                    <input type="text" name="customer_name" placeholder="Enter your name" required>

                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter phone number" required>

                    <label>Quantity</label>
                    <input type="number" name="quantity" min="1" max="<?php echo $row['quantity']; ?>" placeholder="Enter quantity" required>

                    <button type="submit">🛒 Place Order</button>
                </form>
            </div>
        </div>

        <?php } ?>

    </div>
</div>

<div class="footer">
    <p>&copy; 2026 Moonlight POS. All Rights Reserved.</p>
</div>

</body>
</html>