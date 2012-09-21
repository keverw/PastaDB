<?php
require $_SERVER['DOCUMENT_ROOT'] . '/PastaDB.php';

$dbHost = 'localhost';
$dbUser = 'rxoot';
$dbPass = 'root';
$dbName = 'tests';

$db = new PastaDB();

if ($db->connect($dbHost, $dbUser, $dbPass, $dbName))
{
	echo 'YAY! We are connected!';
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>