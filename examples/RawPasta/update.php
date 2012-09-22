<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/examples/PastaDB/connect.php';
ob_end_clean();

echo $db->RawPasta->update('users', array('name' => 'Kyle'), "WHERE id = '%s'", 1);
?>