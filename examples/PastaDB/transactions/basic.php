<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

if ($db->begin())
{
	if ($result = $db->insert('users', array('name' => 'Mark', 'age' => 26)))
	{
		?>
		user ID:<?=$db->insertedID?> created.
		<?
		if ($db->commit())
		{
			echo 'Commited!';
		}
	}
	else
	{
		if ($db->rollback())
		{
			echo 'Rollbacked!';
		}
		die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
	}
}
else
{
	echo 'Error starting transaction';
}
?>