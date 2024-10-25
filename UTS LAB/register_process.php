<?php
require_once('db.php');

//calling form input.
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


//checking if username or email already exist.
$sql = 'SELECT * FROM users WHERE user_name = ? OR email = ?';
$stmt = $db->prepare($sql);
$stmt->execute([$username, $email]);
$check_result = $stmt->fetch(PDO::FETCH_ASSOC);


//if result is not null, that means that there is an existing row with that username or email.
if ($check_result) {
        header('location: register.php?error_status=1'); exit;
} //will change location directly to register.php with $_GET[error_status]=1.


//inserting value.
$encrypted_password = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO users (user_name, email, password)
        VALUES(?, ?, ?)";

$result = $db->prepare($sql);
$result->execute([$username, $email, $encrypted_password]);

header('location: login.php'); exit;
