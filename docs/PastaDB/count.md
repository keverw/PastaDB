#count - get row count!
```
count($table, [$what = '*', $where = null])

```
#####Note:

* the where is automatically escaped! Use sprintf `%s` for strings.
* returns 'err' on failure. returns a number on sucuess.

##Example##
```
$result = $db->count('users', 'age');
if (is_numeric($result))
{
	echo $result;
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```