<?php
session_start();

require_once './connection.php';

// Establish database connection
$db = new DatabaseConnection();
$conn = $db->connect();

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Prepare and execute query to check credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // Authentication successful
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $result['fname'] . ' ' . $result['lname'];
            header('Location: ../pages/layouts/app.php?page=dashboard');
            exit();
        } else {
            // Authentication failed
            $_SESSION['error'] = 'Invalid username or password';
            header('Location: ../pages/auth/login.php');
            exit();
        }
    } catch (PDOException $e) {
        // Handle query errors
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header('Location: ../pages/auth/login.php');
        echo ($e);
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header('Location: ../pages/auth/login.php');
    exit();
}
