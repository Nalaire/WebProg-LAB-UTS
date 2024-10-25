<?php
define('DSN', 'mysql:host=localhost;dbname=web_programming_lab_uts');
define('DBUSER', 'userlab');
define('DBPASS', 'endc');
$db = new PDO(DSN, DBUSER, DBPASS);
?>