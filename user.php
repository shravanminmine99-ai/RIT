<?php
require "config.php";
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Residence Issue Tracker</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    h1 { text-align: center; }
    h2 { margin-top: 20px; }
    .section { background: white; border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    label { display: block; margin-top: 8px; }
    input, textarea, select { width: 100%; padding: 6px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table, th, td { border: 1px solid #ccc; }
    th, td { padding: 8px; text-align: left; }
    button { margin: 2px; padding: 6px 10px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-primary { background: #007bff; color: white; }
    .btn-primary:hover { background: #0056b3; }
    .btn-danger { background: #dc3545; color: white; }
    .btn-danger:hover { background: #a71d2a; }
    .btn-edit { background: #ffc107; color: black; }
    .btn-edit:hover { background: #d39e00; }
  </style>
</head>
<body>
  <h1>🏠 Residence Issue Tracker</h1>

  <!-- Issue Reporter -->
  <div class="section">
    <h2>Issue Reporter</h2>
      <form id="issueForm" method="POST" action="issues.php">
      <label>Title: <input type="text" name="title" required></label>
      <label>Unit/Flat No: <input type="text" name="unit"></label>
      <label>Category:
        <select name="category">
          <option>Plumbing</option>
          <option>Electricity</option>
          <option>Security</option>
          <option>Other</option>
        </select>
      </label>
      <label>Description:
        <textarea name="desc"></textarea>
      </label>
      <label>Priority:
        <select name="priority">
          <option>Low</option>
          <option selected>Medium</option>
          <option>High</option>
        </select>
      </label>
      <button type="submit" class="btn-primary">Report Issue</button>
    </form>

    <h3>Reported Issues</h3>
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Unit</th>
          <th>Category</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="issuesList"></tbody>
    </table>
  </div>

  <!-- Reminder Setter -->
  <div class="section">
    <h2>Reminder Setter</h2>
    <form id="reminderForm">
      <label>Reminder Title: <input type="text" name="title" required></label>
      <label>Date: <input type="date" name="date" required></label>
      <label>Time: <input type="time" name="time" required></label>
      <label>Notes:
        <textarea name="notes"></textarea>
      </label>
      <button type="submit" class="btn-primary">Add Reminder</button>
    </form>

    <h3>Reminders</h3>
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Date</th>
          <th>Time</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="remindersList"></tbody>
    </table>
  </div>

  <!-- Society Timetable -->
  <div class="section">
    <h2>Society Timetable</h2>
    <form id="eventForm">
      <label>Event Title: <input type="text" name="title" required></label>
      <label>Day:
        <select name="day">
          <option>Monday</option>
          <option>Tuesday</option>
          <option>Wednesday</option>
          <option>Thursday</option>
          <option>Friday</option>
          <option>Saturday</option>
          <option>Sunday</option>
        </select>
      </label>
      <label>Start Time: <input type="time" name="start"></label>
      <label>End Time: <input type="time" name="end"></label>
      <label>Location: <input type="text" name="location"></label>
      <button type="submit" class="btn-primary">Add Event</button>
    </form>

    <h3>Weekly Schedule</h3>
    <table>
      <thead>
        <tr>
          <th>Event</th>
          <th>Day</th>
          <th>Time</th>
          <th>Location</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="eventsList"></tbody>
    </table>
  </div>

  <script>
    // Load data
    const issues = JSON.parse(localStorage.getItem("issues") || "[]");
    const reminders = JSON.parse(localStorage.getItem("reminders") || "[]");
    const events = JSON.parse(localStorage.getItem("events") || "[]");

    // Render functions
    function renderIssues() {
      const list = document.getElementById("issuesList");
      list.innerHTML = issues.map((i, idx) =>
        `<tr>
          <td>${i.title}</td>
          <td>${i.unit}</td>
          <td>${i.category}</td>
          <td>${i.priority}</td>
          <td>${i.status}</td>
          <td>
            <button class="btn-edit" onclick="editIssue(${idx})">Edit</button>
            <button class="btn-danger" onclick="deleteIssue(${idx})">Delete</button>
          </td>
        </tr>`).join("");
    }
    function renderReminders() {
      const list = document.getElementById("remindersList");
      list.innerHTML = reminders.map((r, idx) =>
        `<tr>
          <td>${r.title}</td>
          <td>${r.date}</td>
          <td>${r.time}</td>
          <td>${r.notes}</td>
          <td>
            <button class="btn-edit" onclick="editReminder(${idx})">Edit</button>
            <button class="btn-danger" onclick="deleteReminder(${idx})">Delete</button>
          </td>
        </tr>`).join("");
    }
    function renderEvents() {
      const list = document.getElementById("eventsList");
      list.innerHTML = events.map((e, idx) =>
        `<tr>
          <td>${e.title}</td>
          <td>${e.day}</td>
          <td>${e.start} - ${e.end}</td>
          <td>${e.location}</td>
          <td>
            <button class="btn-edit" onclick="editEvent(${idx})">Edit</button>
            <button class="btn-danger" onclick="deleteEvent(${idx})">Delete</button>
          </td>
        </tr>`).join("");
    }

    // Save functions
    function saveIssues() { localStorage.setItem("issues", JSON.stringify(issues)); }
    function saveReminders() { localStorage.setItem("reminders", JSON.stringify(reminders)); }
    function saveEvents() { localStorage.setItem("events", JSON.stringify(events)); }

    // Delete functions
    function deleteIssue(i) { issues.splice(i,1); saveIssues(); renderIssues(); }
    function deleteReminder(i) { reminders.splice(i,1); saveReminders(); renderReminders(); }
    function deleteEvent(i) { events.splice(i,1); saveEvents(); renderEvents(); }

    // Edit functions (fill form with existing data)
    function editIssue(i) {
      const f = document.getElementById("issueForm");
      Object.keys(issues[i]).forEach(k => { if (f[k]) f[k].value = issues[i][k]; });
      deleteIssue(i); // remove old, will re-add on submit
    }
    function editReminder(i) {
      const f = document.getElementById("reminderForm");
      Object.keys(reminders[i]).forEach(k => { if (f[k]) f[k].value = reminders[i][k]; });
      deleteReminder(i);
    }
    function editEvent(i) {
      const f = document.getElementById("eventForm");
      Object.keys(events[i]).forEach(k => { if (f[k]) f[k].value = events[i][k]; });
      deleteEvent(i);
    }

    // Form submissions
    document.getElementById("issueForm").addEventListener("submit", e => {
      e.preventDefault();
      const f = e.target;
      issues.push({
        title: f.title.value,
        unit: f.unit.value,
        category: f.category.value,
        desc: f.desc.value,
        priority: f.priority.value,
        status: "Open"
      });
      saveIssues(); renderIssues(); f.reset();
    });

    document.getElementById("reminderForm").addEventListener("submit", e => {
      e.preventDefault();
      const f = e.target;
      reminders.push({
        title: f.title.value,
        date: f.date.value,
        time: f.time.value,
        notes: f.notes.value
      });
      saveReminders(); renderReminders(); f.reset();
    });

    document.getElementById("eventForm").addEventListener("submit", e => {
      e.preventDefault();
      const f = e.target;
      events.push({
        title: f.title.value,
        day: f.day.value,
        start: f.start.value,
        end: f.end.value,
        location: f.location.value
      });
      saveEvents(); renderEvents(); f.reset();
    });

    // Initial render
    renderIssues(); renderReminders(); renderEvents();
  </script>
</body>
</html>
