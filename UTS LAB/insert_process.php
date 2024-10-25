<?php
//page init
require_once('db.php');
session_start();
//check session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); exit;
}

//preparing to insert the value to the table
$list_name = $_POST['list_name'];
$user_id = $_SESSION['user_id'];
$sql = 'INSERT INTO to_do_lists (list_name, user_id) VALUES (?, ?)';

//inserting value to the table
$stmt = $db->prepare($sql);
$stmt->execute([$list_name, $user_id]);


header('location: home.php?alert_status=1'); exit;
?>