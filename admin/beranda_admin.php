<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

$tugas = [];
$searchQuery = $_GET['search'] ?? '';

$searchSQL = '%' . $searchQuery . '%';
$query = "SELECT * FROM tugas WHERE judul LIKE ? ORDER BY deadline ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $searchSQL);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $tugas[] = $row;
}

// Statistik Tugas
$totalTugas = count($tugas);
$selesai = $proses = $pending = 0;

foreach ($tugas as $item) {
    if ($item['status'] === 'Selesai') $selesai++;
    elseif ($item['status'] === 'Proses') $proses++;
    elseif ($item['status'] === 'Belum Selesai') $pending++;
}

// Statistik Pengguna
$queryUser = "SELECT COUNT(*) AS total_pengguna FROM users WHERE role = 'user'";
$resultUser = $conn->query($queryUser);
$rowUser = $resultUser->fetch_assoc();
$totalPengguna = $rowUser['total_pengguna'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin - PLANify</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gray-100 h-screen flex">

<!-- Sidebar -->
<aside class="w-64 bg-blue-600 text-white flex flex-col justify-between py-8 px-6 shadow-lg">
  <div>
    <h1 class="text-2xl font-bold mb-10">PLANify Admin</h1>
    <nav class="space-y-4">
      <a href="beranda_admin.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 bg-blue-700 transition">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="kelola_tugas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'kelola_tugas.php' ? 'bg-blue-700' : '' ?> transition">
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

<!-- Main -->
<main class="flex-1 p-8 overflow-y-auto">
  <header class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?> (Admin)</h2>
  </header>

  <!-- Statistik -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-blue-600 text-white rounded p-4 shadow">
      <p class="text-lg font-semibold">Total Tugas</p>
      <p class="text-2xl"><?= $totalTugas ?></p>
    </div>
    <div class="bg-green-600 text-white rounded p-4 shadow">
      <p class="text-lg font-semibold">Selesai</p>
      <p class="text-2xl"><?= $selesai ?></p>
    </div>
    <div class="bg-cyan-400 text-white rounded p-4 shadow">
      <p class="text-lg font-semibold">Proses</p>
      <p class="text-2xl"><?= $proses ?></p>
    </div>
    <div class="bg-red-600 text-white rounded p-4 shadow">
      <p class="text-lg font-semibold">Belum Selesai</p>
      <p class="text-2xl"><?= $pending ?></p>
    </div>
    <div class="bg-purple-600 text-white rounded p-4 shadow">
      <p class="text-lg font-semibold">Total Pengguna</p>
      <p class="text-2xl"><?= $totalPengguna ?></p>
    </div>
  </div>

  <!-- Search Form -->
  <form method="get" class="flex flex-col sm:flex-row gap-2 sm:items-center mb-6">
    <input type="text" name="search" placeholder="Cari berdasarkan judul..."
      value="<?= htmlspecialchars($searchQuery) ?>"
      class="w-full sm:w-1/2 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cari</button>
    <?php if (!empty($searchQuery)): ?>
      <a href="beranda_admin.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
    <?php endif; ?>
  </form>

  <!-- Tabel Tugas -->
  <div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm text-left text-gray-600">
      <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
        <tr>
          <th class="px-4 py-3">Judul</th>
          <th class="px-4 py-3">Pengguna</th>
          <th class="px-4 py-3">Deadline</th>
          <th class="px-4 py-3">Kategori</th>
          <th class="px-4 py-3">Prioritas</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($tugas) === 0): ?>
          <tr><td colspan="7" class="px-4 py-4 text-center italic text-gray-500">Tugas tidak ditemukan.</td></tr>
        <?php else: ?>
          <?php foreach ($tugas as $item): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-3 font-medium"><?= htmlspecialchars($item['judul']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($item['username']) ?></td>
              <td class="px-4 py-3"><?= $item['deadline'] ?></td>
              <td class="px-4 py-3"><?= $item['kategori'] ?></td>
              <td class="px-4 py-3"><?= $item['prioritas'] ?></td>
              <td class="px-4 py-3">
                <form method="post" action="update_statusAdmin.php" class="inline">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded border text-sm <?= 
                    $item['status'] === 'Selesai' ? 'text-green-600' : 
                    ($item['status'] === 'Proses' ? 'text-cyan-600' : 'text-red-600') ?>">
                    <option value="Selesai" <?= $item['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Proses" <?= $item['status'] === 'Proses' ? 'selected' : '' ?>>Proses</option>
                    <option value="Belum Selesai" <?= $item['status'] === 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
                  </select>
                </form>
              </td>
              <td class="px-4 py-3 flex gap-2 justify-center">
                <a href="edit_tugasAdmin.php?id=<?= $item['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                <a href="hapus_tugasAdmin.php?id=<?= $item['id'] ?>" onclick="return confirm('Yakin menghapus aktivitas ini?');" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
