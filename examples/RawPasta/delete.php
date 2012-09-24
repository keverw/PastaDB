<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

//echo $db->RawPasta->delete('users', "id = '%s'", 25);
echo $db->RawPasta->delete('users', array('id' => 25, 'name' => 'Mark'));
?>