<?php
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'triviagame';

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {

    die(json_encode([
        'success' => false,

        'message' => 'Database connection failed: ' . $conn->connect_error

    ]));
}


$conn->set_charset('utf8mb4');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    echo json_encode([
        'success' => true,

        'message' => 'Database connection established successfully.'
    ]);
}
