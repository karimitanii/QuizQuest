<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if (!$email || !$password) {
        echo json_encode([
            'success' => false,
            'message' => 'Email and password are required.'
        ]);
        exit;
    }

    $adminEmail = "admin";
    $adminPassword = "123";

    if ($email === $adminEmail && $password === $adminPassword) {
        // Set session variables for the admin
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $adminEmail;

        echo json_encode([
            'success' => true,
            'message' => 'Login successful.',
            'redirect' => 'feadmin.php' // Redirect URL
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
