<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

if ($result = $db->insert('users', array('name' => 'Mark', 'age' => 26)))
{
	?>
	user ID:<?=$db->insertedID?> created.
	<?
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>