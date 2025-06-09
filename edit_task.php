<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$taskId = $_GET['id'];
$userId = $_SESSION['user_id'];

// Ambil data task berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $taskId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    echo "Tugas tidak ditemukan atau bukan milik Anda.";
    exit();
}

// Ambil semua kategori
$categories = $conn->query("SELECT * FROM categories");

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    $update = $conn->prepare("UPDATE tasks SET title=?, category_id=?, priority=?, status=?, due_date=? WHERE id=? AND user_id=?");
    $update->bind_param("sisssii", $title, $category_id, $priority, $status, $due_date, $taskId, $userId);

    if ($update->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Gagal mengupdate tugas.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas - Planify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">

        <!-- Tombol Back di atas -->
        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-secondary">← Back</a>
        </div>

        <h2 class="mb-4 text-center">📝 Edit Tugas</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $task['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Prioritas</label>
                <select name="priority" class="form-select">
                    <option value="Low" <?= $task['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                    <option value="Medium" <?= $task['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="High" <?= $task['priority'] == 'High' ? 'selected' : '' ?>>High</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jatuh Tempo</label>
                <input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
</body>

</html>
