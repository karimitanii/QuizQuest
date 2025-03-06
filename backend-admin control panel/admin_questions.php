<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access. Please log in.'
    ]);
    exit;
}

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : null;

    $validCategories = ['Science', 'History', 'Football', 'Geography', 'Technology', 'Entertainment'];

    if ($action === 'fetch') {
        $category = isset($_POST['category']) ? trim($_POST['category']) : null;
        if (!$category || !in_array($category, $validCategories)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid or missing category.'
            ]);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM Questions WHERE category = ?");
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare SQL statement: ' . $conn->error
            ]);
            exit;
        }

        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }

        echo json_encode([
            'success' => true,
            'questions' => $questions
        ]);

        $stmt->close();
    } elseif ($action === 'delete') {
        $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : null;

        if (!$question_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Question ID is required.'
            ]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM Questions WHERE question_id = ?");
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare SQL statement: ' . $conn->error
            ]);
            exit;
        }

        $stmt->bind_param("i", $question_id);
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => "Question ID $question_id has been deleted."
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete question.'
            ]);
        }

        $stmt->close();
    } elseif ($action === 'add') {
        $category = isset($_POST['category']) ? trim($_POST['category']) : null;
        $question_text = isset($_POST['question_text']) ? trim($_POST['question_text']) : null;
        $option_a = isset($_POST['option_a']) ? trim($_POST['option_a']) : null;
        $option_b = isset($_POST['option_b']) ? trim($_POST['option_b']) : null;
        $option_c = isset($_POST['option_c']) ? trim($_POST['option_c']) : null;
        $correct_option = isset($_POST['correct_option']) ? trim($_POST['correct_option']) : null;

        if (!$category || !in_array($category, $validCategories) || !$question_text || !$option_a || !$option_b || !$option_c || !$correct_option) {
            echo json_encode([
                'success' => false,
                'message' => 'All fields are required and category must be valid.'
            ]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO Questions (category, question_text, option_a, option_b, option_c, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare SQL statement: ' . $conn->error
            ]);
            exit;
        }

        $stmt->bind_param("ssssss", $category, $question_text, $option_a, $option_b, $option_c, $correct_option);
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Question added successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to add question: ' . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

$conn->close();
