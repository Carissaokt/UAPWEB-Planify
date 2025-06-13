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
      <a href="kategori.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 bg-blue-700 transition<?= basename($_SERVER['PHP_SELF']) === 'kategori.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-folder-open"></i> Kategori
      </a>
      <a href="prioritas.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'prioritas.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-flag"></i> Prioritas
      </a>
      <a href="status.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 <?= basename($_SERVER['PHP_SELF']) === 'status.php' ? 'bg-blue-700' : '' ?> transition">
        <i class="fas fa-sliders-h"></i> Status
      </a>
      <a href="pengguna.php" class="flex items-center gap-3 py-2 px-3 rounded hover:bg-blue-500 ">
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
    <h2 class="text-2xl font-bold text-gray-800">Kategori</h2>
  </header>
