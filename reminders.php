<?php
require "config.php";
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

$isAdmin = ($_SESSION["role"] === "admin");
$userId = $_SESSION["user_id"];

// Handle reminder creation (residents only)
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$isAdmin) {
  $title = $_POST["title"];
  $date = $_POST["date"];
  $time = $_POST["time"];
  $notes = $_POST["notes"];

  $stmt = $conn->prepare("INSERT INTO reminders (title, date, time, notes, user_id) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$title, $date, $time, $notes, $userId]);
  echo "<p style='color:green'>Reminder created!</p>";
}

// Query reminders
if ($isAdmin) {
  $stmt = $conn->query("SELECT reminders.*, users.email FROM reminders JOIN users ON reminders.user_id = users.id ORDER BY date ASC");
} else {
  $stmt = $conn->prepare("SELECT * FROM reminders WHERE user_id = ? ORDER BY date ASC");
  $stmt->execute([$userId]);
}
$reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>⏰ Reminders</h2>

<?php if (!$isAdmin): ?>
<form method="POST">
  <h3>Set New Reminder</h3>
  Title: <input type="text" name="title" required><br>
  Date: <input type="date" name="date" required><br>
  Time: <input type="time" name="time" required><br>
  Notes: <textarea name="notes"></textarea><br>
  <button type="submit">Save</button>
</form>
<?php endif; ?>

<h3><?php echo $isAdmin ? "All Reminders" : "My Reminders"; ?></h3>
<table border="1" cellpadding="5">
  <tr>
    <th>Title</th><th>Date</th><th>Time</th><th>Notes</th><?php if ($isAdmin) echo "<th>User</th>"; ?>
  </tr>
  <?php foreach ($reminders as $rem): ?>
  <tr>
    <td><?= htmlspecialchars($rem["title"]) ?></td>
    <td><?= $rem["date"] ?></td>
    <td><?= $rem["time"] ?></td>
    <td><?= htmlspecialchars($rem["notes"]) ?></td>
    <?php if ($isAdmin): ?><td><?= $rem["email"] ?></td><?php endif; ?>
  </tr>
  <?php endforeach; ?>
</table>

<a href="<?= $isAdmin ? 'admin.php' : 'user.php' ?>">⬅ Back</a>
