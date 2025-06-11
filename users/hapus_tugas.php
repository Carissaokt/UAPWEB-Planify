<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

require '../db/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $username = $_SESSION['username'];

    $query = "DELETE FROM tugas WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $id, $username);

    if ($stmt->execute()) {
        header("Location: beranda_user.php");
        exit;
    } else {
        echo "Gagal menghapus tugas.";
    }
} else {
    header("Location: beranda_user.php");
    exit;
}
?>
