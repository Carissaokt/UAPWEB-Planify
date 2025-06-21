<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

$filterUser = $_GET['username'] ?? '';
$filterKategori = $_GET['kategori'] ?? '';

$query = "SELECT username, kategori FROM tugas WHERE kategori IS NOT NULL AND kategori != ''";
$params = [];
$types = "";

if (!empty($filterUser)) {
    $query .= " AND username LIKE ?";
    $params[] = "%$filterUser%";
    $types .= "s";
}

if (!empty($filterKategori)) {
    $query .= " AND kategori = ?";
    $params[] = $filterKategori;
    $types .= "s";
}

$query .= " GROUP BY username, kategori ORDER BY username ASC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$kategoriPengguna = [];
while ($row = $result->fetch_assoc()) {
    $kategoriPengguna[$row['username']][] = $row['kategori'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelola Kategori - PLANify Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 h-screen flex">

<aside class="w-64 bg-blue-600 text-white flex flex-col justify-between py-8 px-6 shadow-lg">
  <div>
    <div class="flex flex-col items-center mb-10">
      <img src="../img/logoPlanify.png" alt="PLANify Logo" class="w-12 h-12 mb-2">
      <h1 class="text-2xl font-bold">PLANify Admin</h1>
    </div>
    <nav class="space-y-4">
      <a href="beranda_admin.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'beranda_admin.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="kelola_tugas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'kelola_tugas.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-tasks"></i> Tugas
      </a>
      <a href="kategori.php" class="flex items-center gap-3 py-2 px-3 rounded bg-blue-700 transition">
        <i class="fas fa-folder-open"></i> Kategori
      </a>
      <a href="prioritas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'prioritas.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-flag"></i> Prioritas
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
  <header class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kategori Pengguna</h2>
  </header>

  <form method="get" class="mb-6 flex flex-col md:flex-row gap-3">
    <input type="text" name="username" placeholder="Cari nama pengguna" value="<?= htmlspecialchars($filterUser) ?>"
      class="px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300 w-full md:w-1/3" />

    <select name="kategori" class="px-4 py-2 border rounded shadow-sm w-full md:w-1/3">
      <option value="">-- Semua Kategori --</option>
      <option value="Kerja" <?= $filterKategori === 'Kerja' ? 'selected' : '' ?>>Kerja</option>
      <option value="Kuliah" <?= $filterKategori === 'Kuliah' ? 'selected' : '' ?>>Kuliah</option>
      <option value="Pribadi" <?= $filterKategori === 'Pribadi' ? 'selected' : '' ?>>Pribadi</option>
    </select>

    <div class="flex gap-2">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
      <a href="kategori.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
    </div>
  </form>

  <div class="bg-white rounded shadow p-6">
    <table class="min-w-full text-sm text-left text-gray-700">
      <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
        <tr>
          <th class="px-4 py-2">Nama Pengguna</th>
          <th class="px-4 py-2">Kategori yang Dipilih</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($kategoriPengguna)): ?>
          <tr><td colspan="2" class="text-center py-4 text-gray-500 italic">Tidak ada hasil ditemukan.</td></tr>
        <?php else: ?>
          <?php foreach ($kategoriPengguna as $user => $kategoriList): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-3 font-medium"><?= htmlspecialchars($user) ?></td>
              <td class="px-4 py-3"><?= implode(', ', array_map('htmlspecialchars', $kategoriList)) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

</body>
</html>
