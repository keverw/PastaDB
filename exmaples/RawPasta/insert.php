<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/exmaples/PastaDB/connect.php';
ob_end_clean();

echo $db->RawPasta->insert('test', array('value' => 'Pie', 'value2' => 'Hey'), array('pie2', 'Hey2'));
//die('Connect Error (' . $db->errorNum . ') ' . $db->error); 

//echo $db->RawPasta->insert('test', array('value' => 'Pie', 'value2' => 'Hey'), array('pie2', 'Hey2'));
?>