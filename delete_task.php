<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$taskId = $_GET['id'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $taskId, $userId);
$stmt->execute();

header("Location: dashboard.php");
exit();
?>
