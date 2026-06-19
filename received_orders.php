<?php
include("db.php");

$result = mysqli_query($conn, "SELECT * FROM customer_orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Received Orders</title>
    <style>
body{
    margin:0;
    padding:30px;
    font-family:'Segoe UI',sans-serif;
    background:#f4f7fc;
}

.page-header{
    background:linear-gradient(135deg,#0f766e,#10b981);
    color:white;
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 5px 20px rgba(0,0,0,0.15);
}

.page-header h1{
    margin:0;
    font-size:34px;
}

.page-header p{
    margin-top:8px;
    opacity:0.9;
}

.orders-container{
    background:white;
    border-radius:15px;
    padding:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

th{
    background:#0f172a;
    color:white;
    padding:16px;
    text-align:left;
    font-size:15px;
}

td{
    padding:15px;
    border-bottom:1px solid #e5e7eb;
}

tr:hover{
    background:#f9fafb;
}

.status-pending{
    background:#fef3c7;
    color:#92400e;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.status-completed{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.status-cancelled{
    background:#fee2e2;
    color:#991b1b;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.btn{
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    color:white;
    font-size:13px;
    font-weight:bold;
}

.btn-process{
    background:#2563eb;
}

.btn-complete{
    background:#16a34a;
}

.btn-delete{
    background:#dc2626;
}

.back-btn{
    display:inline-block;
    margin-top:20px;
    background:#0f766e;
    color:white;
    padding:12px 20px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}

.back-btn:hover{
    background:#0d5c56;
}
</style>
</head>
<body>

<div class="page-header">
    <h1>📦 Received Customer Orders</h1>
    <p>Manage incoming customer requests, process orders and track fulfilment status.</p>
</div>

<div class="orders-container">

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Phone</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['customer_name']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['item_name']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>KES <?php echo $row['price']; ?></td>
        <td>KES <?php echo $row['total_amount']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td><?php echo $row['order_date']; ?></td>
        <td>
            <a href="process_order.php?id=<?php echo $row['id']; ?>&status=Processing">Processing</a>
            |
            <a href="process_order.php?id=<?php echo $row['id']; ?>&status=Completed">Completed</a>
            |
            <a href="process_order.php?id=<?php echo $row['id']; ?>&status=Cancelled">Cancelled</a>
        </td>
    </tr>
    <?php } ?>

</table>

<br>
<a href="dashboard.php"><a href="dashboard.php" class="back-btn">
🏠 Back to Dashboard
</a>
</div>

</body>
</html>