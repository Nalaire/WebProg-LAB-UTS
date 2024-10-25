<?php
session_start();
require_once('db.php');

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users
        WHERE email = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
  $error_message = "<b>User not found</b>";
  header("Location: login.php?error=1"); exit;
} else {
  if (!password_verify($password, $row['password'])) {
    $error_message = "<b>Wrong password</b>";
    header("Location: login.php?error=2"); exit;
  } else {
    // Login success, set SESSION DATA
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['user_name'] = $row['user_name'];
    header('location: home.php'); exit;
  }
}
