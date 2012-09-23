#update - updates a table! #

```
update($tableName, $set, $where, [$escapeArgs, ...]])

```
#####Note:

the set values are automatically escaped! The $where and $escapeArgs works just like the ones in select();

##Example
```
if ($result = $db->update('users', array('name' => 'Mark', 'age' => 32), "id = '%s'", 21))
{
	echo 'affectedRows: ' . $db->affectedRows;
	echo '<br>Updated';
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```