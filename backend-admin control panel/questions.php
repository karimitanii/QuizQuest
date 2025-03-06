<?php
header('Content-Type: application/json');

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = isset($_POST['category']) ? trim($_POST['category']) : null;

    if (!$category) {
        echo json_encode([
            'success' => false,
            'message' => 'Category is required.'
        ]);
        exit;
    }

    //getting 10 random question from the database
    $stmt = $conn->prepare("SELECT question_id, question_text, option_a, option_b, option_c, correct_option FROM Questions WHERE category = ? ORDER BY RAND() LIMIT 10");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL statement.',
            'debug' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    // checking if we fetched the questions from the database
    if ($result->num_rows > 0) {
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row; //adding each question to the array
        }

        echo json_encode([
            'success' => true,
            'questions' => $questions
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No questions found for the selected category.'
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
