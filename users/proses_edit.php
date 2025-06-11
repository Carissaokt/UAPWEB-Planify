<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

require '../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $prioritas = $_POST['prioritas'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];
    $username = $_SESSION['username'];

    $query = "UPDATE tugas SET judul = ?, kategori = ?, prioritas = ?, status = ?, deadline = ? 
              WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssis", $judul, $kategori, $prioritas, $status, $deadline, $id, $username);

    if ($stmt->execute()) {
        header("Location: beranda_user.php");
        exit;
    } else {
        echo "Gagal memperbarui tugas.";
    }
} else {
    header("Location: beranda_user.php");
    exit;
}
?>
