<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

if ($result = $db->replace('users', array('id' => 21, 'name' => 'Mark', 'age' => 30)))
{
	?>
	user ID:<?=$db->insertedID?> replaced.
	<?
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>