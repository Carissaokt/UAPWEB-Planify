<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

require '../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $prioritas = $_POST['prioritas'] ?? '';
    $status = $_POST['status'] ?? '';
    $deadline = $_POST['deadline'] ?? '';

    if ($judul && $kategori && $prioritas && $status && $deadline) {
        $username = $_SESSION['username'];
        $query = "INSERT INTO tugas (username, judul, kategori, prioritas, status, deadline) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $username, $judul, $kategori, $prioritas, $status, $deadline);

        if ($stmt->execute()) {
            header("Location: beranda_user.php");
            exit;
        } else {
            echo "Gagal menyimpan tugas.";
        }
    } else {
        echo "Semua bidang harus diisi.";
    }
} else {
    header("Location: beranda_user.php");
    exit;
}
?>
