<?php
session_start();
require 'koneksi.php';

$userId = $_SESSION['user_id'];

function countTasks($conn, $userId, $status = null) {
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE user_id = ?";
    if ($status !== null) {
        $sql .= " AND status = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $status);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

$response = [
    'total' => countTasks($conn, $userId),
    'completed' => countTasks($conn, $userId, 'completed'),
    'in progress' => countTasks($conn, $userId, 'in progress'),
    'pending' => countTasks($conn, $userId, 'pending'),
    'canceled' => countTasks($conn, $userId, 'canceled'),
];

echo json_encode($response);
