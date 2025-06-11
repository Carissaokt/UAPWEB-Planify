<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE tugas SET status = ? WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sis", $status, $id, $_SESSION['username']);
    $stmt->execute();
}

header("Location: beranda_user.php");
exit;
