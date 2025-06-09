<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['id'];
    $status = $_POST['status'];

    // Validasi status agar tidak sembarangan
    $allowedStatuses = ['pending', 'in progress', 'completed'];
    if (!in_array($status, $allowedStatuses)) {
        echo 'invalid status';
        exit;
    }

    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $taskId);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
