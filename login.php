<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password']) || $password === $user['password']) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

       if ($user['role'] == 'Inventory Manager') {
             header("Location: manager_dashboard.php");
            }
           elseif ($user['role'] == 'customer') {
          header("Location: shop_items.php");
            }
           else {  
          header("Location: dashboard.php");
            } 

            exit();

        } else {
            echo "<script>alert('Invalid password! Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that username or email!');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Moonlight Enterprises | Login</title>

<style>
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #0a192f, #112d4e, #3a7bd5);
    background-size: 300% 300%;
    animation: gradientShift 8s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.container {
    width: 380px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    color: #fff;
    animation: fadeIn 1.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.logo {
    width: 100px;
    height: 100px;
    background: #fff;
    color: #0a192f;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    font-weight: bold;
    margin: 0 auto 20px;
    box-shadow: 0 0 20px rgba(255,255,255,0.6);
    animation: logoPulse 4s infinite ease-in-out;
}

@keyframes logoPulse {
    0% { transform: scale(1); box-shadow: 0 0 10px rgba(255,255,255,0.4); }
    50% { transform: scale(1.05); box-shadow: 0 0 25px rgba(255,255,255,0.8); }
    100% { transform: scale(1); box-shadow: 0 0 10px rgba(255,255,255,0.4); }
}

input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    margin: 10px 0;
    font-size: 14px;
    outline: none;
}

.btn {
    width: 100%;
    padding: 10px;
    background: linear-gradient(90deg, #1e90ff, #00bfff);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
}

.btn:hover {
    background: linear-gradient(90deg, #00bfff, #1e90ff);
    transform: translateY(-2px);
}

.link {
    color: #b3e5fc;
    text-decoration: none;
    font-size: 13px;
}

.link:hover {
    color: white;
    text-decoration: underline;
}
</style>
</head>

<body>
<div class="container">
    <div class="logo">ME</div>
    <h2>Moonlight Enterprises</h2>
    <p>Welcome back! Please login to continue.</p>

    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" class="btn">Login</button>
    </form>

    <p style="margin-top: 15px;">
        Don't have an account? <a href="register.php" class="link">Register</a>
    </p>
</div>
</body>
</html>