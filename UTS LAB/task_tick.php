<?php
//page init.
require_once('db.php');
session_start();

//check session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); exit;
}


//post variable verification. was get, but got changed because loading said url might lead to changes, which may be a bad thing on second thought.
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
    $sql = 'SELECT tasks.task_id, tasks.task_completion AS task_completion, tasks.list_id AS list_id, to_do_lists.user_id FROM tasks, to_do_lists WHERE tasks.list_id = to_do_lists.list_id AND user_id = ? AND tasks.task_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id, $task_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        header('location: home.php'); exit;
    }
}

//execute
if ($result['task_completion']) {
    $sql = 'UPDATE tasks SET task_completion = 0 WHERE task_id = ?';
}
else {
    $sql = 'UPDATE tasks SET task_completion = 1 WHERE task_id = ?';
}
$stmt = $db->prepare($sql);
$stmt->execute([$task_id]);

$list_id = $result['list_id'];
header("location: individual.php?list_id=$list_id"); exit;