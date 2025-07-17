<?php
// Start session
session_start();

// Database credentials
$host = 'localhost';
$db   = 'ecommerce';
$user = 'root'; // Change if your MySQL user is different
$pass = '';

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get POST data
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username && $password) {
    // Prepare statement
    $stmt = $conn->prepare('SELECT id, password FROM admin WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        // Compare plain text password (not secure)
        if ($password === $hashed_password) {
            // Success: set session and redirect
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $username;
            header('Location: admin-dashboard/index.php'); // Redirect to admin dashboard
            exit();
        }
    }
    // Invalid credentials
    header('Location: login.html?error=1');
    exit();
} else {
    // Missing fields
    header('Location: login.html?error=1');
    exit();
} 