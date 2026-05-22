<style>
    /* BASE LAYOUT for SIDEBAR */
    body {
        align-items: flex-start; /* Required for sidebar layout */
    }
    .main-wrapper {
        display: flex;
        width: 100%;
        max-width: 1200px; /* Wider container for the dashboard and content */
        margin-top: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background-color: #fff;
        min-height: 80vh;
    }
    
    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        background-color: #4a69bd; 
        padding: 20px 0;
        color: white;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
        flex-shrink: 0;
        position: relative; 
    }
    .sidebar h2 {
        text-align: center;
        color: #fff;
        margin-bottom: 30px;
        font-size: 1.5em;
    }
    .sidebar-menu a {
        display: block;
        padding: 15px 20px;
        text-decoration: none;
        color: white;
        border-left: 5px solid transparent;
        transition: background-color 0.3s, border-left-color 0.3s;
    }
    .sidebar-menu a:hover, .sidebar-menu a.active {
        background-color: #3b53a4;
        border-left-color: #f39c12; 
    }
    
    /* Main Content Area Container */
    .content {
        flex-grow: 1;
        padding: 30px;
        background-color: #fff;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
        /* Ensure content doesn't push the sidebar */
        overflow-x: auto; 
    }
    
    /* Logout Button in Sidebar */
    .sidebar .logout-btn {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        background-color: #e74c3c; 
        padding: 10px 20px; 
        border-radius: 4px; 
        color: white;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .sidebar .logout-btn:hover {
        background-color: #c0392b;
    }
</style>

<div class="sidebar">
    <h2>Moonlight POS</h2>
    
    <div class="sidebar-menu">
        <a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">🏠 Dashboard</a>
        
        <a href="stock_placement.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'stock_placement.php') ? 'active' : ''; ?>">📦 Stock Inventory</a>
        <a href="new_stock_record.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'new_stock_record.php') ? 'active' : ''; ?>">➕ Add New Item</a>
        
        <a href="view_sales_records.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_sales_records.php') ? 'active' : ''; ?>">📈 Sales Records</a>
        <a href="items_sold_record.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'items_sold_record.php') ? 'active' : ''; ?>">💰 Record New Sale</a>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>