<?php
// login.php
session_start();
include 'db_connect.php'; // Include the database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($user_id) || empty($username) || empty($password)) {
        echo "All fields are required!";
        exit();
    }

    // Prepare a SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND username = ?");
    if (!$stmt) {
        echo "Preparation failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    $stmt->bind_param("is", $user_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now check the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password matches, start session
            $_SESSION['user_id'] = $row['user_id'];
            echo "Login successful";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Invalid User ID or Username";
    }

    $stmt->close();
    $conn->close();
}