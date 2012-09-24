#select - select from a table!
```
select($where, [$escapeArgs, ...])

```
#####Note:

* the where is automatically escaped! Use sprintf `%s` for strings.
* When zero records are returned, it will return `true` because an empty array will cause an `if ()` statement to think it's `false` when really there was nothing to find for the select. Use `$db->numRows` to get the number of records before running a foreach loop to list multple rows. same thing happens when you call `->query`.

##Example #1 - Single row
```
$userID = 21;

if ($result = $db->select("SELECT * FROM users WHERE id = '%s' LIMIT 1", $userID))
{
	if ($db->numRows > 0)
	{
		echo '<pre>';
		print_r($result[0]);
		echo '</pre>';
	}
	else
	{
		echo 'User ID: ' . $userID . ' not found';
	}
	
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```

##Example #2 - List multiple rows
```
if ($result = $db->select("SELECT * FROM users LIMIT 5"))
{
	if ($db->numRows > 0)
	{
		foreach ($result as $row)
		{
			echo '<pre>';
			print_r($row);
			echo '</pre>';
		}
	}
	else
	{
		echo 'users not found';
	}
}
else
{
	die('Connect Error (' . $db->errorNum . ') ' . $db->error); 
}
```