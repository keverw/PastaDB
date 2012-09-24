#update - deletes a row! #

```
delete($tableName, $where, [$escapeArgs, ...]])

```
#####Note:

the set values are automatically escaped! The $where and $escapeArgs works just like the ones in select();

##Example
```
if ($result = $db->delete('users', "id = '%s'", 25))
{
	echo 'affectedRows: ' . $db->affectedRows;
	echo '<br>deleted';
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```