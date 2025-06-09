<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

function countTasks($conn, $userId, $status = null) {
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE user_id = ?";
    if ($status !== null) {
        $sql .= " AND status = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $status);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

function getStatusColor($status) {
    $status = strtolower(trim($status));
    return match($status) {
        'completed' => 'success',
        'in progress' => 'info',
        'pending' => 'secondary',
        default => 'dark',
    };
}

$totalTasks = countTasks($conn, $userId);
$completed = countTasks($conn, $userId, 'completed');
$inProgress = countTasks($conn, $userId, 'in progress');
$pending = countTasks($conn, $userId, 'pending');

// Ambil semua tugas user
$sql = "SELECT tasks.id, tasks.title, tasks.priority, tasks.status, tasks.due_date, categories.name AS category_name
        FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        WHERE tasks.user_id = ?
        ORDER BY tasks.due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Planify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .badge-status { cursor: pointer; }
  </style>
</head>
<body>
<div class="container mt-4">
  <h2>Welcome, <?= htmlspecialchars($username); ?>!</h2>

  <!-- Ringkasan -->
  <div class="row my-4" id="summary-cards">
    <?php
    $summary = [
        ['label' => 'Total Tasks', 'count' => $totalTasks, 'class' => 'primary', 'id' => 'total-tasks'],
        ['label' => 'Completed', 'count' => $completed, 'class' => 'success', 'id' => 'completed-tasks'],
        ['label' => 'In Progress', 'count' => $inProgress, 'class' => 'info', 'id' => 'inprogress-tasks'],
        ['label' => 'Pending', 'count' => $pending, 'class' => 'secondary', 'id' => 'pending-tasks']
    ];
    foreach ($summary as $item): ?>
      <div class="col-md-3">
        <div class="card text-white bg-<?= $item['class']; ?> mb-3">
          <div class="card-body">
            <h5 class="card-title"><?= $item['label']; ?></h5>
            <p class="card-text" id="<?= $item['id']; ?>"><?= $item['count']; ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="d-flex justify-content-between mb-3">
    <h4>Your Tasks</h4>
    <a href="add_task.php" class="btn btn-success">+ New Activity</a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>Title</th>
          <th>Deadline</th>
          <th>Category</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($tasks->num_rows > 0): ?>
        <?php while ($task = $tasks->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($task['title']); ?></td>
            <td><?= htmlspecialchars($task['due_date']); ?></td>
            <td><?= htmlspecialchars($task['category_name']); ?></td>
            <td><?= ucfirst($task['priority']); ?></td>
            <td>
              <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle text-white badge-status bg-<?= getStatusColor($task['status']); ?>" 
                        type="button" id="dropdownMenu<?= $task['id']; ?>" 
                        data-bs-toggle="dropdown" aria-expanded="false"
                        data-task-id="<?= $task['id']; ?>">
                  <?= ucfirst($task['status']); ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?= $task['id']; ?>">
                  <li><a class="dropdown-item status-option" href="#" data-status="pending" data-task-id="<?= $task['id']; ?>">Pending</a></li>
                  <li><a class="dropdown-item status-option" href="#" data-status="in progress" data-task-id="<?= $task['id']; ?>">In Progress</a></li>
                  <li><a class="dropdown-item status-option" href="#" data-status="completed" data-task-id="<?= $task['id']; ?>">Completed</a></li>
                </ul>
              </div>
            </td>
            <td>
              <a href="edit_task.php?id=<?= $task['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete_task.php?id=<?= $task['id']; ?>" class="btn btn-sm btn-danger"
                 onclick="return confirm('Yakin ingin menghapus tugas ini?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">Tidak ada tugas ditemukan.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function(){
  $('.status-option').click(function(e){
    e.preventDefault();
    const taskId = $(this).data('task-id');
    const newStatus = $(this).data('status');
    const button = $('#dropdownMenu' + taskId);

    $.ajax({
      url: 'update_status.php',
      method: 'POST',
      data: { id: taskId, status: newStatus },
      success: function(response) {
        if(response === 'success'){
          button.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
          button.removeClass (function (index, className) {
              return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
          });
          let newColor = 'bg-light';
          switch(newStatus){
            case 'completed': newColor = 'bg-success'; break;
            case 'in progress': newColor = 'bg-info'; break;
            case 'pending': newColor = 'bg-secondary'; break;
          }
          button.addClass(newColor + ' text-white');

          // Update jumlah tugas atas
          $.get('get_task_counts.php', function(data) {
            const counts = JSON.parse(data);
            $('#total-tasks').text(counts.total);
            $('#completed-tasks').text(counts.completed);
            $('#inprogress-tasks').text(counts['in progress']);
            $('#pending-tasks').text(counts.pending);
          });

        } else {
          alert('Gagal update status');
        }
      },
      error: function() {
        alert('Error saat mengupdate status');
      }
    });
  });
});
</script>
</body>
</html>
