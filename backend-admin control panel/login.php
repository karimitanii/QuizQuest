<?php
header('Content-Type: application/json');

require_once 'connection.php';

if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;

if (!$email || !$password) {
    echo json_encode([
        'success' => false,
        'message' => 'Email or password not provided.',
        'debug' => [
            'email' => $email,
            'password' => $password
        ]
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT user_id, username, password FROM Users WHERE email = ?");
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
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        echo json_encode([
            'success' => true,
            'user_id' => $user['user_id'],
            'username' => $user['username']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Incorrect password.',
            'debug' => [
                'input_password' => $password,
                'stored_password' => $user['password']
            ]
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User not found.',
        'debug' => [
            'email' => $email
        ]
    ]);
}

$stmt->close();
$conn->close();
