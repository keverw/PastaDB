<?php
require $_SERVER['DOCUMENT_ROOT'] . '/PastaDB.php';

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'tests';

$db = new PastaDB('DUMBCHAR');

if ($db->connect($dbHost, $dbUser, $dbPass, $dbName))
{
	echo 'YAY! We are connected!';
	echo '<br>';
	printf("Current character set: %s\n", $db->DBH->character_set_name());
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>