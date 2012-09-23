#replace - replaces data in to database #

```
replace($table, $row, [$additionalRow, $additionalRow2, ...])

```
#####Note:

the rows are automatically escaped!

returns true when replaced, false when failed.

##Example
```
if ($result = $db->replace('users', array('id' => 21, 'name' => 'Mark', 'age' => 30)))
{
	?>
	user ID:<?=$db->insertedID?> updated.
	<?
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```