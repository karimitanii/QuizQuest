<?php
header('Content-Type: application/json');

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $new_username = isset($_POST['new_username']) ? trim($_POST['new_username']) : null;

    if (empty($user_id) || empty($new_username)) {
        echo json_encode([
            'success' => false,
            'message' => 'User ID and new username are required.'
        ]);
        exit;
    }

    // Updating the username
    $stmt = $conn->prepare("UPDATE Users SET username = ? WHERE user_id = ?");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL statement.',
            'debug' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("si", $new_username, $user_id);
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Username updated successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update username.',
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
