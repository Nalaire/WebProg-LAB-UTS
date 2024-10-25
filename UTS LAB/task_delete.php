<?php
//page init.
require_once('db.php');
session_start();

//check session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); exit;
}


//post variable verification, previously get, but was changed for more interactivity
$validPage = true;
$integer_pattern = '/[0123456789]/';

$task_id_str = strval($_POST['task_id']);
if (preg_match($integer_pattern, $task_id_str)) {
    $task_id = intval($task_id_str);
}
else {
    $validPage = false;
    header('location: home.php'); exit;
}

//verify access
if ($validPage) {
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT tasks.task_id, tasks.list_id AS list_id, to_do_lists.user_id FROM tasks, to_do_lists WHERE tasks.list_id = to_do_lists.list_id AND user_id = ? AND tasks.task_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id, $task_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        header('location: home.php'); exit;
    }
}

//execute
$sql = 'DELETE FROM tasks WHERE task_id = ?';
$stmt = $db->prepare($sql);
$stmt->execute([$task_id]);

$list_id = $result['list_id'];
header("location: individual.php?list_id=$list_id"); exit;