<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

require '../db/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: beranda_user.php");
    exit;
}

$id = $_GET['id'];
$username = $_SESSION['username'];

$query = "SELECT * FROM tugas WHERE id = ? AND username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Tugas tidak ditemukan atau bukan milik Anda.";
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Aktivitas - PLANify</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

<main class="p-6">
  <div class="max-w-xl mx-auto bg-white rounded shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Aktivitas</h2>

    <form action="proses_edit.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" value="<?= $data['id'] ?>">

      <div>
        <label class="block text-sm font-medium text-gray-700">Judul Aktivitas</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="kategori" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
          <option value="Kuliah" <?= $data['kategori'] === 'Kuliah' ? 'selected' : '' ?>>Kuliah</option>
          <option value="Kerja" <?= $data['kategori'] === 'Kerja' ? 'selected' : '' ?>>Kerja</option>
          <option value="Pribadi" <?= $data['kategori'] === 'Pribadi' ? 'selected' : '' ?>>Pribadi</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Skala Prioritas</label>
        <select name="prioritas" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
          <option value="Tinggi" <?= $data['prioritas'] === 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
          <option value="Sedang" <?= $data['prioritas'] === 'Sedang' ? 'selected' : '' ?>>Sedang</option>
          <option value="Rendah" <?= $data['prioritas'] === 'Rendah' ? 'selected' : '' ?>>Rendah</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
          <option value="Belum Selesai" <?= $data['status'] === 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
          <option value="Selesai" <?= $data['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
          <option value="Proses" <?= $data['status'] === 'Proses' ? 'selected' : '' ?>>Proses</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Deadline</label>
        <input type="date" name="deadline" value="<?= $data['deadline'] ?>" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <div class="flex justify-between mt-6">
        <a href="beranda_user.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">← Batal</a>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Update</button>
      </div>
    </form>
  </div>
</main>
</body>
</html>
