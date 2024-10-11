<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';  // Your database connection
?>

<?php
// signup.php
include 'db_connect.php'; // Include the database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id']; // Get the user_id from the form input
    $username = $_POST['username'];
    $password = $_POST['password'];
    

    // Validate input
    if (empty($user_id) ||empty($username) || empty($password)) {
        echo "ID, Username and Password are required!";
        exit();
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if (!$stmt) {
        echo "Preparation failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already taken!";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Check if userID already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    if (!$stmt) {
        echo "Preparation failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This UserID already taken!";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO users (user_id, username, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Preparation failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    // Use 'iss' for the types (i = integer for user_id, s = string for username and password)
    $stmt->bind_param("iss", $user_id, $username, $hashed_password);  // 3 values = 3 placeholders

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Account created successfully!";
    } else {
        echo "Error: " . $stmt->error;  // Display error if execution fails
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}