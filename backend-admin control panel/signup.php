<?php
header('Content-Type: application/json');

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Validate input
    if (!$username || !$email || !$password) {
        echo json_encode([
            'success' => false,
            'message' => 'All fields (username, email, password) are required.'
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL statement.',
            'debug' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Email is already registered.'
        ]);
        $stmt->close();
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL statement for insertion.',
            'debug' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("sss", $username, $email, $password);
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'User registered successfully!',
            'user_id' => $stmt->insert_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to register user.',
            'debug' => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

$conn->close();
