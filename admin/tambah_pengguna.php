<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    $stmt->execute();
    header("Location: pengguna.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Pengguna - PLANify</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex">

<!-- Sidebar -->
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
      <a href="pengguna.php" class="flex items-center gap-3 py-2 px-3 rounded bg-blue-700 transition">
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

<!-- Main Content -->
<main class="flex-1 p-8 overflow-y-auto">
  <div class="max-w-4xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pengguna Baru</h2>
    <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold mb-1 text-gray-700">Username</label>
        <input name="username" required placeholder="Username" class="w-full px-4 py-2 border rounded">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1 text-gray-700">Email</label>
        <input name="email" type="email" required placeholder="Email" class="w-full px-4 py-2 border rounded">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1 text-gray-700">Password</label>
        <input name="password" type="password" required placeholder="Password" class="w-full px-4 py-2 border rounded">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1 text-gray-700">Role</label>
        <select name="role" class="w-full px-4 py-2 border rounded">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="md:col-span-2 flex justify-end">
        <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
          Simpan
        </button>
      </div>
    </form>
  </div>
</main>

</body>
</html>
