<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Aktivitas - PLANify</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

<header class="bg-white shadow-md py-4 px-6 flex justify-between items-center border-b">
  <div class="flex items-center space-x-3">
    <img src="../assets/logo.png" alt="PLANify Logo" class="w-8 h-8">
    <h1 class="text-2xl font-semibold text-blue-700">PLANify</h1>
  </div>
  <div class="flex items-center gap-4">
    <div class="text-right hidden sm:block">
      <p class="text-sm text-gray-500">Halo,</p>
      <p class="text-blue-600 font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></p>
    </div>
    <a href="../auth/keluar.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
      Logout
    </a>
  </div>
</header>


<main class="p-6">
  <div class="max-w-xl mx-auto bg-white rounded shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Aktivitas Baru</h2>

    <form action="proses_tambah.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Judul Aktivitas</label>
        <input type="text" name="judul" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="kategori" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
          <option value="">-- Pilih Kategori --</option>
          <option value="Kuliah">Kuliah</option>
          <option value="Kerja">Kerja</option>
          <option value="Pribadi">Pribadi</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Skala Prioritas</label>
        <select name="prioritas" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
          <option value="">-- Pilih Prioritas --</option>
          <option value="Tinggi">Tinggi</option>
          <option value="Sedang">Sedang</option>
          <option value="Rendah">Rendah</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Status Tugas</label>
        <select name="status" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
          <option value="">-- Pilih Status --</option>
          <option value="Belum Selesai">Belum Selesai</option>
          <option value="Selesai">Selesai</option>
          <option value="Proses">Proses</option>

        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Deadline</label>
        <input type="date" name="deadline" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <div class="flex justify-between mt-6">
        <a href="beranda_user.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">← Kembali</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Simpan</button>
      </div>
    </form>
  </div>
</main>
</body>
</html>
