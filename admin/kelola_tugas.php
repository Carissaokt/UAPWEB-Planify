<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

$searchQuery = $_GET['search'] ?? '';
$searchSQL = '%' . $searchQuery . '%';

$query = "SELECT t.id, t.judul, t.deadline, t.status, u.username
          FROM tugas t
          JOIN users u ON t.username = u.username
          WHERE t.judul LIKE ?
          ORDER BY t.deadline ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $searchSQL);
$stmt->execute();
$result = $stmt->get_result();
?>

