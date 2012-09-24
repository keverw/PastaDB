<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

if ($result = $db->delete('users', "id = '%s'", 25))
{
	echo 'affectedRows: ' . $db->affectedRows;
	echo '<br>deleted';
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>