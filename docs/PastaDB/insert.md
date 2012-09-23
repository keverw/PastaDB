#insert - inserts data in to database #

```
insert($table, $row, [$additionalRow, $additionalRow2, ...])

```
#####Note:

the rows are automatically escaped!

returns true when inserted, false when failed.

##Example
```
if ($result = $db->insert('users', array('name' => 'Mark', 'age' => 26)))
{
	?>
	user ID:<?=$db->insertedID?> created.
	<?
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```