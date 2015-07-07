##clean##

```
clean($mixedValue)
```
###Parameters###
***mixedValue*** - A value to be escaped to help prevent SQL injections.

###note##
some functions like `->insert()`, `->replace()`, `->update()` will automatically escape varables, this is great for when using `->query()`


##cleanLike##

Simuilar to `clean`, run this on strings before running the string into `clean`(or a function that does automatic escaping using `clean`). This is to use for varables that will be used with MySQL `like` statement

```
cleanLike($mixedValue)
```

###Parameters###
***mixedValue*** - A value to be escaped to help prevent SQL injections.

##cleanBoth##

```
cleanBoth($mixedValue)
```

###Parameters###
***mixedValue*** - A value to be escaped on both .

###note###

Runs `cleanLike`, then `clean` on a string in one swoop. Prefect for when you need to run both `cleanLike` and `clean` when doing manual SQL queries.

##cleanParms##

```
cleanParms($statement, [$escapeArgs, ...])
```

###Parameters###
***statement*** - A SQL statement
***escapeArgs*** - pass each arugment you wish to escape in as a function parmaeter matching up with each %s in the statement. This works just like how select statement's are called.