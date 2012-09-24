#select - generates the SELECT SQL!
```
select($where, [$escapeArgs, ...])

```
#####Note:

The where is automatically escaped! When a string use sprintf `%s` for strings and $escapeArgs for things to be escaped like sprintf. You can also give a key/value array for $where like `array('id' => 12)`
