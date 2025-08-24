<?php
session_start();

$host = "localhost";
$db_name = "residence_tracker";
$username = "root"; // your MySQL username
$password = "";     // your MySQL password

try {
  $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
?>
