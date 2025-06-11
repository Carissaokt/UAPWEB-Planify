<?php
include '../db/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah semua input tersedia dan tidak kosong
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role     = 'user';

        // mengecek username tidak boleh sama
        $cekQuery = "SELECT * FROM users WHERE username = '$username'";
        $cekResult = mysqli_query($conn, $cekQuery);
        if (mysqli_num_rows($cekResult) > 0) {
            echo "<script>alert('Username sudah terdaftar. Silakan gunakan username lain.'); window.location='daftar.php';</script>";
            exit;
        }

        // Simpan ke database (tambahkan kolom email)
        $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('Registrasi berhasil. Silakan login.'); window.location='../index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Semua harus diisi.'); window.location='daftar.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar plan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <body class="flex items-center justify-center h-screen bg-gray-100">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Daftar Akun</h2>
    <form action="daftar.php" method="post">

      <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded mb-4" required />
      <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded mb-4" required />
      <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded mb-4" required />
      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-500">Daftar</button>
      <p class="mt-4 text-sm">Sudah punya akun? <a href="../index.php" class="text-blue-500">Login</a></p>

    </form>
  </div>
</body>
</body>
</html>
