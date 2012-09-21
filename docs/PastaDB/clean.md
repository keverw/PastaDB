##clean##

```
clean($mixedValue)
```
##Parameters##
***mixedValue*** - A value to be escaped to help prevent SQL injections.

##note##
some functions like `->insert()`, `->replace()`, `->update()` will automatically escape varables, this is great for when using `->query()`