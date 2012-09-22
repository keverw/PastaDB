<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

//echo $db->RawPasta->update('users', array('name' => 'Kyle', 'age' => 20));

echo $db->RawPasta->update('users', array('name' => 'Kyle', 'age' => 20), "id = '%s'", 1);
?>
<br>
<?
die('Connect Error (' . $db->errorNum . ') ' . $db->error);
?>