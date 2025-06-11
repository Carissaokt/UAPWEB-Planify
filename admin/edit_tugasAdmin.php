<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: beranda_admin.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tugas = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $prioritas = $_POST['prioritas'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];

    $update = $conn->prepare("UPDATE tugas SET judul = ?, kategori = ?, prioritas = ?, status = ?, deadline = ? WHERE id = ?");
    $update->bind_param("sssssi", $judul, $kategori, $prioritas, $status, $deadline, $id);
    $update->execute();

    header("Location: beranda_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Tugas (Admin) - PLANify</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex">

<aside class="w-64 bg-blue-600 text-white flex flex-col justify-between py-8 px-6 shadow-lg">
  <div>
    <h1 class="text-2xl font-bold mb-10">PLANify Admin</h1>
    <nav class="space-y-4">
      <a href="beranda_admin.php" class="flex items-center gap-3 py-2 px-3 rounded bg-blue-700 hover:bg-blue-500 transition">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="kelola_tugas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-tasks"></i> Tugas
      </a>
      <a href="kategori.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-folder-open"></i> Kategori
      </a>
      <a href="prioritas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-flag"></i> Prioritas
      </a>
      <a href="status.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-sliders-h"></i> Status
      </a>
      <a href="pengguna.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-users"></i> Pengguna
      </a>
    </nav>
  </div>
  <div>
    <a href="../auth/keluar.php" onclick="return confirm('Yakin untuk keluar?');"
       class="flex items-center gap-3 py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded transition w-full">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</aside>

<main class="flex-1 p-8 overflow-y-auto">
  <div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Tugas</h2>
    <form method="post" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($tugas['judul']) ?>" required class="w-full px-4 py-2 border rounded">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="kategori" class="w-full px-4 py-2 border rounded">
          <option <?= $tugas['kategori'] === 'Kerja' ? 'selected' : '' ?>>Kerja</option>
          <option <?= $tugas['kategori'] === 'Kuliah' ? 'selected' : '' ?>>Kuliah</option>
          <option <?= $tugas['kategori'] === 'Pribadi' ? 'selected' : '' ?>>Pribadi</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
        <select name="prioritas" class="w-full px-4 py-2 border rounded">
          <option <?= $tugas['prioritas'] === 'Rendah' ? 'selected' : '' ?>>Rendah</option>
          <option <?= $tugas['prioritas'] === 'Sedang' ? 'selected' : '' ?>>Sedang</option>
          <option <?= $tugas['prioritas'] === 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select name="status" class="w-full px-4 py-2 border rounded">
          <option <?= $tugas['status'] === 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
          <option <?= $tugas['status'] === 'Proses' ? 'selected' : '' ?>>Proses</option>
          <option <?= $tugas['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
        <input type="date" name="deadline" value="<?= $tugas['deadline'] ?>" class="w-full px-4 py-2 border rounded">
      </div>
      <div class="flex items-center justify-between mt-4">
        <a href="beranda_admin.php" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
        <button class="bg-yellow-500 text-white px-5 py-2 rounded hover:bg-yellow-600">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</main>

</body>
</html>
