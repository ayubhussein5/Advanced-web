<?php
session_start();
require 'db.php';

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Update the logout_time for the most recent login of this user
    $sql = "UPDATE logins SET logout_time = CURRENT_TIMESTAMP WHERE user_id = ? ORDER BY login_time DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>