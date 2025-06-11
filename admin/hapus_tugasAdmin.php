<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tugas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: beranda_admin.php");
exit;
?>
