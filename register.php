<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

  $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'resident')");
  try {
    $stmt->execute([$email, $password]);
    echo "Registration successful. <a href='login.php'>Login here</a>";
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>

<form method="POST">
  <h2>Register</h2>
  Email: <input type="email" name="email" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Register</button>
</form>
