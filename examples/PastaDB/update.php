<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

if ($result = $db->update('users', array('name' => 'Mark', 'age' => 32), "id = '%s'", 21))
{
	echo 'affectedRows: ' . $db->affectedRows;
	echo '<br>Updated';
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>