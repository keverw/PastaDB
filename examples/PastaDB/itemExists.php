<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

$result = $db->itemExists('users', 'id', 55);

if ($result['err'])
{
	echo 'SQL error';
}
else
{
	if ($result['exists'])
	{
		echo 'Item found.';
	}
	else
	{
		echo 'Item not found.';
	}
}
?>