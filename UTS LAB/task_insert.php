<?php
//page init.
require_once('db.php');
session_start();

//check session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); exit;
}

//page validation
$validPage = true;
$integer_pattern = '/[0123456789]/';

$list_id_str = strval($_POST['list_id']);
if (preg_match($integer_pattern, $list_id_str)) {
    $list_id = intval($list_id_str);
}
else {
    $validPage = false;
}

//access validation.
if ($validPage) {
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT * FROM to_do_lists WHERE list_id = ? AND user_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$list_id, $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        header('location: home.php'); //if user does not have access, stop this php and proceed back to home.
        exit;
    }
}

//execute
$task_name = $_POST['task_name'];
$sql = 'INSERT INTO tasks (task_name, list_id) VALUES (?, ?);';
$stmt = $db->prepare($sql);
$stmt->execute([$task_name, $list_id]);


header("location: individual.php?list_id=$list_id"); exit;