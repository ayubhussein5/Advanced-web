<?php
session_start();
include("db.php"); // connect to your database

if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } 
    // Check password length (must be between 5 and 12)
    elseif (strlen($password) < 5 || strlen($password) > 12) {
        echo "<script>alert('Password must be between 5 and 12 characters long!');</script>";
    }
    else {
        // Check if username, email, or phone exists
        $check_user = "SELECT * FROM users WHERE username='$username' OR email='$email' OR phone='$phone'";
        $result = mysqli_query($conn, $check_user);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Username, Email, or Phone already exists!');</script>";
        } else {
            // ✅ Secure password hashing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user securely
            $insert = "INSERT INTO users (username, email, phone, password, created_at)
                       VALUES ('$username', '$email', '$phone', '$hashed_password', NOW())";

            if (mysqli_query($conn, $insert)) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Error registering user.');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Moonlight Enterprises | Register</title>

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
    width: 400px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    color: #fff;
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
#strengthMessage {
    font-size: 13px;
    margin-top: -5px;
    margin-bottom: 10px;
}
</style>
</head>

<body>
<div class="container">
    <div class="logo">ME</div>
    <h2>Create Your Account</h2>
    <p>Join Moonlight Enterprises today!</p>

    <form action="" method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="text" name="phone" placeholder="Enter Phone Number" required>
        <input type="password" id="password" name="password" placeholder="Enter Password" required>
        <div id="strengthMessage"></div>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" class="btn">Register</button>
    </form>

    <p style="margin-top: 15px;">Already have an account? <a href="login.php" class="link">Login</a></p>
</div>

<!-- Password strength and length feedback -->
<script>
const passwordInput = document.getElementById("password");
const strengthMessage = document.getElementById("strengthMessage");

passwordInput.addEventListener("input", () => {
    const password = passwordInput.value;

    if (password.length === 0) {
        strengthMessage.textContent = "";
        return;
    }

    if (password.length < 5) {
        strengthMessage.textContent = "Too short ❌ (min 5 characters)";
        strengthMessage.style.color = "red";
    } else if (password.length > 12) {
        strengthMessage.textContent = "Too long ⚠️ (max 12 characters)";
        strengthMessage.style.color = "orange";
    } else {
        strengthMessage.textContent = "Good password ✅";
        strengthMessage.style.color = "lightgreen";
    }
});
</script>

</body>
</html>
