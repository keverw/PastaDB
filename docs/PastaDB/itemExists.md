#itemExists - check if a id exists!
```
itemExists($table, $col, $value)

```

##Example##
```
$result = $db->itemExists('users', 'id', 55);

if ($result['err'])
{
	echo 'SQL error';
}
else
{
	if ($result['exists'])
	{
		echo 'Item found.';
	}
	else
	{
		echo 'Item not found.';
	}
}
```