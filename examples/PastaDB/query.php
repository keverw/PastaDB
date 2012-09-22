<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/exmaples/PastaDB/connect.php';
ob_end_clean();

if ($result = $db->query("select * from test"))
{
	print_r($result);
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>