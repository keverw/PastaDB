#update - updates a table! #

```
update($tableName, $set, $where, [$escapeArgs, ...]])

```
#####Note:

the set values are automatically escaped! The $where and $escapeArgs works just like the ones in select();