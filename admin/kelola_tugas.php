<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

$searchQuery = $_GET['search'] ?? '';
$searchSQL = '%' . $searchQuery . '%';

$query = "SELECT t.id, t.judul, t.deadline, t.status, u.username
          FROM tugas t
          JOIN users u ON t.username = u.username
          WHERE t.judul LIKE ?
          ORDER BY t.deadline ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $searchSQL);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelola Tugas - PLANify Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex">

<aside class="w-64 bg-blue-600 text-white flex flex-col justify-between py-8 px-6 shadow-lg">
  <div>
    <div class="flex flex-col items-center mb-10">
      <img src="../img/logoPlanify.png" alt="PLANify Logo" class="w-12 h-12 mb-2">
      <h1 class="text-2xl font-bold">PLANify Admin</h1>
    </div>
    <nav class="space-y-4">
      <a href="beranda_admin.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="kelola_tugas.php" class="flex items-center gap-3 py-2 px-3 rounded bg-blue-700 transition">
        <i class="fas fa-tasks"></i> Tugas
      </a>
      <a href="kategori.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
        <i class="fas fa-folder-open"></i> Kategori
      </a>
      <a href="prioritas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 transition">
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
    <h2 class="text-2xl font-bold text-gray-800">Tugas Pengguna</h2>
  </header>

  <form method="get" class="mb-6 flex flex-col sm:flex-row gap-3">
    <input type="text" name="search" placeholder="Cari berdasarkan judul tugas"
           value="<?= htmlspecialchars($searchQuery) ?>"
           class="px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300 w-full sm:w-1/2"/>
    <div class="flex gap-2">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cari</button>
      <?php if (!empty($searchQuery)): ?>
        <a href="kelola_tugas.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
      <?php endif; ?>
    </div>
  </form>

  <div class="overflow-x-auto bg-white rounded shadow p-6">
    <table class="min-w-full text-sm text-left text-gray-700">
      <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
        <tr>
          <th class="px-4 py-2">no</th>
          <th class="px-4 py-2">Pengguna</th>
          <th class="px-4 py-2">Judul Tugas</th>
          <th class="px-4 py-2">Deadline</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="6" class="text-center py-4 text-gray-500 italic">Tugas tidak ditemukan.</td></tr>
        <?php else: $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-3"><?= $no++ ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['username']) ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['judul']) ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['deadline']) ?></td>
            <td class="px-4 py-3">
              <span class="<?=
                $row['status'] === 'Selesai' ? 'text-green-600' :
                ($row['status'] === 'Proses' ? 'text-cyan-600' : 'text-red-600')
              ?> font-medium"><?= htmlspecialchars($row['status']) ?></span>
            </td>
            <td class="px-4 py-3 flex gap-2 justify-center">
              <a href="edit_tugasAdmin.php?id=<?= $row['id'] ?>"
                 class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
              <a href="hapus_tugasAdmin.php?id=<?= $row['id'] ?>"
                 onclick="return confirm('Yakin ingin menghapus tugas ini?');"
                 class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
            </td>
          </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
