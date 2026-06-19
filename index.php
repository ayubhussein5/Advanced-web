<?php session_start(); 
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Moonlight POS | Home</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f8fb;
    color: #1f2937;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 70px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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
    margin-left: 25px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

.navbar a:hover {
    color: #0f766e;
}

.signin {
    background: #0f766e;
    color: white !important;
    padding: 10px 18px;
    border-radius: 6px;
}

.register {
    background: #f59e0b;
    color: white !important;
    padding: 10px 18px;
    border-radius: 6px;
}

.hero {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 40px;
    padding: 80px 70px;
    background: linear-gradient(135deg, #e6fffa, #ffffff);
    align-items: center;
}

.hero h1 {
    font-size: 48px;
    color: #064e3b;
    margin-bottom: 20px;
}

.hero p {
    font-size: 18px;
    line-height: 1.7;
    color: #4b5563;
}

.hero-box {
    background: #064e3b;
    color: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.hero-box h2 {
    margin-top: 0;
}

.hero-box .stat {
    background: rgba(255,255,255,0.12);
    padding: 15px;
    margin: 12px 0;
    border-radius: 10px;
}

.btn-group {
    margin-top: 25px;
}

.btn {
    display: inline-block;
    padding: 13px 22px;
    margin-right: 10px;
    background: #0f766e;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}

.btn.secondary {
    background: #f59e0b;
}

.section {
    padding: 60px 70px;
}

.section h2 {
    text-align: center;
    color: #064e3b;
    font-size: 34px;
    margin-bottom: 35px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.card {
    background: white;
    padding: 28px;
    border-radius: 14px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    text-align: center;
}

.icon {
    font-size: 45px;
    margin-bottom: 15px;
}

.card h3 {
    color: #0f766e;
}

.steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.step {
    background: #ffffff;
    padding: 25px;
    border-radius: 14px;
    text-align: center;
    border-top: 5px solid #0f766e;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
}

.resources {
    background: #064e3b;
    color: white;
    text-align: center;
}

.resources h2 {
    color: white;
}

.footer {
    background: #111827;
    color: white;
    text-align: center;
    padding: 25px;
}

@media(max-width: 900px) {
    .navbar {
        flex-direction: column;
        padding: 15px;
    }

    .navbar a {
        display: inline-block;
        margin: 8px;
    }

    .hero {
        grid-template-columns: 1fr;
        padding: 40px 25px;
    }

    .cards, .steps {
        grid-template-columns: 1fr;
    }

    .section {
        padding: 40px 25px;
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
        <a href="#features">Features</a>
        <a href="#resources">Resources</a>
        <a href="login.php" class="signin">Sign In</a>
        <a href="register.php" class="register">Register</a>
    </div>
</div>

<div class="hero">
    <div>
        <h1>Smart Inventory and Customer Ordering System</h1>
        <p>
            Moonlight POS helps customers place orders easily while admins manage stock,
            sales, customer orders, and business transactions from one simple platform.
        </p>

        <div class="btn-group">
            <?php if(isset($_SESSION['username'])) { ?>
           <a href="shop_items.php" class="btn">Shop Now</a>
           <a href="order_status.php" class="btn secondary">Check Order Status</a>
           <?php } else { ?>
           <a href="login.php" class="btn">Sign In</a>
           <a href="register.php" class="btn secondary">Register</a>
           <?php } ?>
            <a href="order_status.php" class="btn secondary">Check Order Status</a>
        </div>
    </div>

    <div class="hero-box">
        <h2>System Overview</h2>
        <div class="stat">📦 Inventory Tracking</div>
        <div class="stat">🛒 Customer Orders</div>
        <div class="stat">📊 Sales Records</div>
        <div class="stat">👨‍💼 Admin & Manager Access</div>
    </div>
</div>

<div class="section" id="features">
    <h2>System Features</h2>

    <div class="cards">
        <div class="card">
            <div class="icon">🛍️</div>
            <h3>Customer Shopping</h3>
            <p>Customers can view available products, place orders, and track order status.</p>
        </div>

        <div class="card">
            <div class="icon">📦</div>
            <h3>Inventory Control</h3>
            <p>Admin can manage stock, add items, update inventory, and monitor quantity levels.</p>
        </div>

        <div class="card">
            <div class="icon">📈</div>
            <h3>Sales Tracking</h3>
            <p>Sales records are saved automatically when customer orders are completed.</p>
        </div>

        <div class="card">
            <div class="icon">🧾</div>
            <h3>Order Management</h3>
            <p>Admin can process customer orders by marking them as Processing, Completed, or Cancelled.</p>
        </div>

        <div class="card">
            <div class="icon">👨‍💼</div>
            <h3>Manager Reports</h3>
            <p>Inventory Manager can view stock, sales, customer orders, and reports without editing records.</p>
        </div>

        <div class="card">
            <div class="icon">🔐</div>
            <h3>Role-Based Access</h3>
            <p>Admin, manager, and customer areas are separated for better control and security.</p>
        </div>
    </div>
</div>

<div class="section">
    <h2>How Customers Use the System</h2>

    <div class="steps">
        <div class="step">
            <div class="icon">1️⃣</div>
            <h3>Browse Items</h3>
            <p>Customer opens the shop page and views available products.</p>
        </div>

        <div class="step">
            <div class="icon">2️⃣</div>
            <h3>Place Order</h3>
            <p>Customer enters name, phone number, quantity, and submits order.</p>
        </div>

        <div class="step">
            <div class="icon">3️⃣</div>
            <h3>Admin Processes</h3>
            <p>Admin receives the order and updates the order status.</p>
        </div>

        <div class="step">
            <div class="icon">4️⃣</div>
            <h3>Track Status</h3>
            <p>Customer checks whether the order is Pending, Processing, Completed, or Cancelled.</p>
        </div>
    </div>
</div>

<div class="section resources" id="resources">
    <h2>Resources & Support</h2>
    <p>
        Use Moonlight POS to simplify stock control, customer order handling,
        sales records, and business reporting.
    </p>

    <div class="btn-group">
        <a href="shop_items.php" class="btn">Start Shopping</a>
        <a href="login.php" class="btn secondary">Staff Sign In</a>
    </div>
</div>

<div class="footer">
    <p>&copy; 2026 Moonlight Enterprises POS System. All Rights Reserved.</p>
</div>

</body>
</html>