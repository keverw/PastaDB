<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

if ($result = $db->select("SELECT * FROM users LIMIT 5"))
{
	if ($db->numRows > 0)
	{
		foreach ($result as $row)
		{
			echo '<pre>';
			print_r($row);
			echo '</pre>';
		}
	}
	else
	{
		echo 'users not found';
	}
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>