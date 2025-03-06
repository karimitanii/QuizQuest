<?php
header('Content-Type: application/json');

require_once 'connection.php';

ob_clean();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //giving the user an option to select a category
    $category = isset($_GET['category']) ? trim($_GET['category']) : null;

    if ($category) {
        // Fetch leaderboard for a specific category
        $query = "
            SELECT 
                s.category, 
                u.username, 
                s.score 
            FROM 
                Scores s 
            JOIN 
                Users u 
            ON 
                s.user_id = u.user_id 
            WHERE 
                s.category = ? 
            ORDER BY 
                s.score DESC 
            LIMIT 50;
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare SQL statement.',
                'debug' => $conn->error
            ]);
            exit;
        }

        $stmt->bind_param("s", $category);
    } else {
        // getting the leader board without the category
        $query = "
            SELECT 
                s.category, 
                u.username, 
                s.score 
            FROM 
                Scores s 
            JOIN 
                Users u 
            ON 
                s.user_id = u.user_id 
            ORDER BY 
                s.score DESC 
            LIMIT 50;
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare SQL statement.',
                'debug' => $conn->error
            ]);
            exit;
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $leaderboard = [];

        while ($row = $result->fetch_assoc()) {
            $leaderboard[] = $row;
        }

        echo json_encode([
            'success' => true,
            'leaderboard' => $leaderboard
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch leaderboard.',
            'debug' => $conn->error
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
