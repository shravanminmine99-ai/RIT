<?php
require "config.php";
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

$isAdmin = ($_SESSION["role"] === "admin");
$userId = $_SESSION["user_id"];

// Handle new issue submission (only residents)
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$isAdmin) {
  $title = $_POST["title"];
  $unit = $_POST["unit"];
  $category = $_POST["category"];
  $description = $_POST["description"];
  $priority = $_POST["priority"];

  $stmt = $conn->prepare("INSERT INTO issues (title, unit, category, description, priority, created_by) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$title, $unit, $category, $description, $priority, $userId]);
  echo "<p style='color:green'>Issue reported successfully!</p>";
}

// Query issues
if ($isAdmin) {
  $stmt = $conn->query("SELECT issues.*, users.email FROM issues JOIN users ON issues.created_by = users.id ORDER BY created_at DESC");
} else {
  $stmt = $conn->prepare("SELECT * FROM issues WHERE created_by = ? ORDER BY created_at DESC");
  $stmt->execute([$userId]);
}
$issues = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📋 Issues</h2>



<h3><?php echo $isAdmin ? "All Reported Issues" : "My Issues"; ?></h3>
<table border="1" cellpadding="5">
  <tr>
    <th>Title</th><th>Unit</th><th>Category</th><th>Priority</th><th>Status</th><th>Created At</th><?php if ($isAdmin) echo "<th>User</th>"; ?>
  </tr>
  <?php foreach ($issues as $issue): ?>
  <tr>
    <td><?= htmlspecialchars($issue["title"]) ?></td>
    <td><?= htmlspecialchars($issue["unit"]) ?></td>
    <td><?= htmlspecialchars($issue["category"]) ?></td>
    <td><?= htmlspecialchars($issue["priority"]) ?></td>
    <td><?= htmlspecialchars($issue["status"]) ?></td>
    <td><?= $issue["created_at"] ?></td>
    <?php if ($isAdmin): ?><td><?= $issue["email"] ?></td><?php endif; ?>
  </tr>
  <?php endforeach; ?>
</table>

<a href="<?= $isAdmin ? 'admin.php' : 'user.php' ?>">⬅ Back</a>
