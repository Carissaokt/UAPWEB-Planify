<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}
require '../db/koneksi.php';

$username = $_SESSION['username'];
$tugas = [];

$searchQuery = '';
$filterKategori = '';
$filterStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        $searchQuery = '';
        $filterKategori = '';
        $filterStatus = '';
    } else {
        $searchQuery = isset($_POST['search']) ? trim($_POST['search']) : '';
        $filterKategori = $_POST['kategori'] ?? '';
        $filterStatus = $_POST['status'] ?? '';
    }
}

$searchSQL = '%' . $searchQuery . '%';

$query = "SELECT * FROM tugas WHERE username = ? AND judul LIKE ?";

// filt berdasarkan ketegori dan status
if (!empty($filterKategori)) {
    $query .= " AND kategori = ?";
}
if (!empty($filterStatus)) {
    $query .= " AND status = ?";
}

$query .= " ORDER BY deadline ASC";
$stmt = $conn->prepare($query);

if (!empty($filterKategori) && !empty($filterStatus)) {
    $stmt->bind_param("ssss", $username, $searchSQL, $filterKategori, $filterStatus);
} elseif (!empty($filterKategori)) {
    $stmt->bind_param("sss", $username, $searchSQL, $filterKategori);
} elseif (!empty($filterStatus)) {
    $stmt->bind_param("sss", $username, $searchSQL, $filterStatus);
} else {
    $stmt->bind_param("ss", $username, $searchSQL);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $tugas[] = $row;
}

// hitung statistik didalam kotak
$totalTugas = count($tugas);
$selesai = $proses = $pending = 0;

foreach ($tugas as $item) {
    if ($item['status'] === 'Selesai') $selesai++;
    elseif ($item['status'] === 'Proses') $proses++;
    elseif ($item['status'] === 'Belum Selesai') $pending++;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Pengguna - PLANify</title>
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
    <a href=" ../auth/keluar.php" onclick="return confirm('Yakin untuk keluar?');" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
      Logout
    </a>
  </div>
</header>

<main class="p-6">
  <div class="max-w-6xl mx-auto">

    <!-- kotak keterangan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
    </div>

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-800">Daftar Tugas Saya</h2>
      <a href="add_tugas.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambahkan Tugas</a>
    </div>

    <!-- pencarian -->
    <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-6">
      <input
        type="text"
        name="search"
        placeholder="Cari berdasarkan judul..."
        value="<?= htmlspecialchars($searchQuery) ?>"
        class="px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300"
      />

      <select name="kategori" class="px-4 py-2 border rounded shadow-sm">
        <option value="">-- Semua Kategori --</option>
        <option value="Kerja" <?= $filterKategori === 'Kerja' ? 'selected' : '' ?>>Kerja</option>
        <option value="Kuliah" <?= $filterKategori === 'Kuliah' ? 'selected' : '' ?>>Kuliah</option>
        <option value="Pribadi" <?= $filterKategori === 'Pribadi' ? 'selected' : '' ?>>Pribadi</option>
      </select>

      <select name="status" class="px-4 py-2 border rounded shadow-sm">
        <option value="">-- Semua Status --</option>
        <option value="Selesai" <?= $filterStatus === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
        <option value="Proses" <?= $filterStatus === 'Proses' ? 'selected' : '' ?>>Proses</option>
        <option value="Belum Selesai" <?= $filterStatus === 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
      </select>

      <div class="flex gap-2">
        <button type="submit" name="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        <?php if (!empty($searchQuery) || !empty($filterKategori) || !empty($filterStatus)): ?>
          <button type="submit" name="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</button>
        <?php endif; ?>
      </div>
    </form>

    <!-- tabel tugas -->
    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="min-w-full text-sm text-left text-gray-600">
        <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
          <tr>
            <th class="px-4 py-3">Judul</th>
            <th class="px-4 py-3">Deadline</th>
            <th class="px-4 py-3">Kategori</th>
            <th class="px-4 py-3">Prioritas</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($tugas) === 0): ?>
            <tr><td colspan="6" class="px-4 py-4 text-center italic text-gray-500">Tugas tidak ditemukan.</td></tr>
          <?php else: ?>
            <?php foreach ($tugas as $item): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium"><?= htmlspecialchars($item['judul']) ?></td>
                <td class="px-4 py-3"><?= $item['deadline'] ?></td>
                <td class="px-4 py-3"><?= $item['kategori'] ?></td>
                <td class="px-4 py-3"><?= $item['prioritas'] ?></td>
                <td class="px-4 py-3">
                  <form method="post" action="update_status.php" class="inline">
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
                  <a href="edit_tugas.php?id=<?= $item['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                  <a href="hapus_tugas.php?id=<?= $item['id'] ?>" onclick="return confirm('Yakin ingin menghapus tugas ini?');" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>
