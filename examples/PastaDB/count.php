<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

//$result = $db->count('users', 'age', array('age' => 25));
$result = $db->count('users', 'age');
if (is_numeric($result))
{
	echo $result;
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
?>