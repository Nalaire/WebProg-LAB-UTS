<?php
//page init.
require_once('db.php');
session_start();

//check session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); exit;
}

//hidden input verification
$validPage = true;
$integer_pattern = '/[0123456789]/';

$list_id_str = strval($_POST['list_id']);
if (preg_match($integer_pattern, $list_id_str)) {
    $list_id = intval($list_id_str);
}
else {
    $validPage = false;
    header('location: home.php'); exit;
}

//double check user access to ensure they did not tamper with hidden input
if ($validPage) {
    $validAccess = true;
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT * FROM to_do_lists WHERE list_id = ? AND user_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$list_id, $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        $validAccess = false; //this might not get used as there is no need to explain why the tampering failed
        header('location: home.php'); exit;
    }
}

//delete the list
$sql = "DELETE FROM to_do_lists WHERE list_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$list_id]);


header('location: home.php'); exit;
?>