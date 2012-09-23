<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

$userID = 21;

if ($result = $db->select("SELECT * FROM users WHERE id = '%s' LIMIT 1", $userID))
{
	if ($db->numRows > 0)
	{
		echo '<pre>';
		print_r($result[0]);
		echo '</pre>';
	}
	else
	{
		echo 'User ID: ' . $userID . ' not found';
	}
	
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>