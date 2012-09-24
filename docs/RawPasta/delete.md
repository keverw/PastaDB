#delete - generates the DELETE SQL! #

```
delete($tableName, $where, [$escapeArgs, ...]])

```
#####Note:

the set values are automatically escaped! The $where and $escapeArgs works just like the ones in select();