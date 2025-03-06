<?php
header('Content-Type: application/json');

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $category = isset($_POST['category']) ? trim($_POST['category']) : null;
    $score = isset($_POST['score']) ? (int)$_POST['score'] : null;

    if (!$user_id || !$category || $score === null) {
        echo json_encode([
            'success' => false,
            'message' => 'All fields (user_id, category, score) are required.'
        ]);
        exit;
    }

    // Checking if the user and category exists 
    $stmt = $conn->prepare("SELECT user_id FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid user ID.'
        ]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Update the score for the user and category
    $stmt = $conn->prepare("INSERT INTO Scores (user_id, category, score) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE score = GREATEST(score, VALUES(score))");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL statement.',
            'debug' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("isi", $user_id, $category, $score);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Score submitted successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit score.',
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
