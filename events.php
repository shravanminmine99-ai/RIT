<?php
require "config.php";
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

$isAdmin = ($_SESSION["role"] === "admin");
$userId = $_SESSION["user_id"];

// Handle event creation (admin only)
if ($_SERVER["REQUEST_METHOD"] === "POST" && $isAdmin) {
  $title = $_POST["title"];
  $day = $_POST["day"];
  $start = $_POST["start"];
  $end = $_POST["end"];
  $location = $_POST["location"];
  $notes = $_POST["notes"];

  $stmt = $conn->prepare("INSERT INTO events (title, day, start, end, location, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([$title, $day, $start, $end, $location, $notes, $userId]);
  echo "<p style='color:green'>Event added!</p>";
}

// Query all events (everyone can see)
$stmt = $conn->query("SELECT events.*, users.email FROM events JOIN users ON events.created_by = users.id ORDER BY day, start ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📅 Society Timetable</h2>

<?php if ($isAdmin): ?>
<form method="POST">
  <h3>Add New Event</h3>
  Title: <input type="text" name="title" required><br>
  Day: <input type="text" name="day" required><br>
  Start: <input type="time" name="start" required><br>
  End: <input type="time" name="end" required><br>
  Location: <input type="text" name="location"><br>
  Notes: <textarea name="notes"></textarea><br>
  <button type="submit">Add Event</button>
</form>
<?php endif; ?>

<h3>Upcoming Events</h3>
<table border="1" cellpadding="5">
  <tr>
    <th>Title</th><th>Day</th><th>Start</th><th>End</th><th>Location</th><th>Notes</th><?php if ($isAdmin) echo "<th>Created By</th>"; ?>
  </tr>
  <?php foreach ($events as $event): ?>
  <tr>
    <td><?= htmlspecialchars($event["title"]) ?></td>
    <td><?= htmlspecialchars($event["day"]) ?></td>
    <td><?= $event["start"] ?></td>
    <td><?= $event["end"] ?></td>
    <td><?= htmlspecialchars($event["location"]) ?></td>
    <td><?= htmlspecialchars($event["notes"]) ?></td>
    <?php if ($isAdmin): ?><td><?= $event["email"] ?></td><?php endif; ?>
  </tr>
  <?php endforeach; ?>
</table>

<a href="<?= $isAdmin ? 'admin.php' : 'user.php' ?>">⬅ Back</a>
