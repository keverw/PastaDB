<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

//echo $db->RawPasta->count('users');

echo $db->RawPasta->count('users', 'age', array('age' => 25));
?>