#select - generates the COUNT SELECT SQL!
```
count($table, [$what = '*', $where = null])

```
#####Note:

The where is automatically escaped! When a string use sprintf `%s` for strings and $escapeArgs for things to be escaped like sprintf. You can also give a key/value array for $where like `array('id' => 12)`
