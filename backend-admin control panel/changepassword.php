<?php
header('Content-Type: application/json');

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : null;
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : null;

    // Validating input
    if (!$user_id || !$old_password || !$new_password) {
        echo json_encode([
            'success' => false,
            'message' => 'User ID, old password, and new password are required.'
        ]);
        exit;
    }

    // Verifying the old password
    $stmt = $conn->prepare("SELECT password FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found.'
        ]);
        $stmt->close();
        exit;
    }

    $user = $result->fetch_assoc();
    if ($old_password !== $user['password']) {
        echo json_encode([
            'success' => false,
            'message' => 'Old password is incorrect.'
        ]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Update the password
    $stmt = $conn->prepare("UPDATE Users SET password = ? WHERE user_id = ?");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL statement.',
            'debug' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("si", $new_password, $user_id);
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Password updated successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update password.',
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
