<?php
session_start();
include 'db/koneksi.php';

// Jika sudah login, redirect langsung ke dashboard masing-masing
if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/beranda_admin.php");
    exit;
} elseif (isset($_SESSION['username']) && $_SESSION['role'] === 'user') {
    header("Location: users/beranda_user.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $query = "SELECT * FROM users WHERE username=? AND role=?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $data   = $result->fetch_assoc();

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['id']       = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $data['role'];

        if ($role === 'admin') {
            header("Location: admin/beranda_admin.php");
        } else {
            header("Location: users/beranda_user.php");
        }
        exit;
    } else {
        $error = "Login gagal. Username, password, atau role tidak sesuai.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - PLANify</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-br from-blue-100 to-white">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">LOGIN</h2>
    <h3 class="text-lg font-semibold text-center mb-4">Selamat Datang di <span class="text-blue-600">PLANify</span>.</h3>

    <?php if (!empty($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="index.php" method="POST" class="space-y-4">
      <div>
        <label class="block font-medium mb-1">Login Sebagai</label>
        <select name="role" class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
          <option value="user">Pengguna</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div>
        <label class="block font-medium mb-1">Username</label>
        <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-500" required />
      </div>
      <div>
        <label class="block font-medium mb-1">Password</label>
        <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-500" required />
      </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
        LOGIN
      </button>
      <p class="text-center text-sm mt-2">
        Belum punya akun? <a href=" auth/daftar.php" class="text-blue-600 hover:underline">Daftar di sini</a>
      </p>
    </form>
  </div>
</body>
</html>
