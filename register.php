<?php
include 'koneksi.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['username']);
$email = trim($data['email']);
$password = trim($data['password']);
$role = trim($data['role']);

$response = [];

if (empty($username) || empty($email) || empty($password)) {
    $response['success'] = false;
    $response['message'] = 'Username, email, dan password wajib diisi.';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['success'] = false;
    $response['message'] = 'Format email tidak valid.';
    echo json_encode($response);
    exit;
}

// Cek username
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Username sudah terdaftar.';
    echo json_encode($response);
    exit;
}

// Cek email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Email sudah terdaftar.';
    echo json_encode($response);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Registrasi berhasil.';
} else {
    $response['success'] = false;
    $response['message'] = 'Terjadi kesalahan saat menyimpan data.';
}

echo json_encode($response);
