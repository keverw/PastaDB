<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/exmaples/PastaDB/connect.php';
ob_end_clean();

echo $db->RawPasta->replace('test', array('id' => '5', 'value2' => 'Hey5'), array('6', 'Hey6'));
?>