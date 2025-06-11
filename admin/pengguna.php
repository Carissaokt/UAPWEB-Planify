<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
require '../db/koneksi.php';

$query = "SELECT id, username, email, role FROM users ORDER BY username ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelola Pengguna - PLANify Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gray-100 h-screen flex">

<aside class="w-64 bg-blue-600 text-white flex flex-col justify-between py-8 px-6 shadow-lg">
  <div>
    <h1 class="text-2xl font-bold mb-10">PLANify Admin</h1>
    <nav class="space-y-4">
      <a href="beranda_admin.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'beranda_admin.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="kelola_tugas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'kelola_tugas.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-tasks"></i> Tugas
      </a>
      <a href="kategori.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'kategori.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-folder-open"></i> Kategori
      </a>
      <a href="prioritas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'prioritas.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-flag"></i> Prioritas
      </a>
      <a href="status.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'status.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-sliders-h"></i> Status
      </a>
      <a href="pengguna.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 bg-blue-700 transition">
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
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna</h2>
    <a href="tambah_pengguna.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
      + Tambah Pengguna
    </a>
  </div>

  <div class="overflow-x-auto bg-white shadow rounded p-4">
    <table class="min-w-full text-sm text-left text-gray-700">
      <thead class="bg-blue-100 text-blue-800">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Username</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Role</th>
          <th class="px-4 py-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while($user = $result->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?= $user['id'] ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($user['role']) ?></td>
            <td class="px-4 py-2 flex gap-2 justify-center">
              <a href="edit_pengguna.php?id=<?= $user['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
              <a href="hapus_pengguna.php?id=<?= $user['id'] ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?');" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
