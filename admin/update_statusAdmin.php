<?php
session_start();
require '../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Admin boleh update semua, user hanya tugas miliknya
    if ($_SESSION['role'] === 'admin') {
        $stmt = $conn->prepare("UPDATE tugas SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
    } else {
        $stmt = $conn->prepare("UPDATE tugas SET status = ? WHERE id = ? AND username = ?");
        $stmt->bind_param("sis", $status, $id, $_SESSION['username']);
    }

    $stmt->execute();
}

header("Location: " . ($_SESSION['role'] === 'admin' ? "beranda_admin.php" : "beranda_user.php"));
exit;
