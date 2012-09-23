#connect - Open a new connection to the MySQL server#

```
$db = connect([ string $host = ini_get("mysqli.default_host") [, string $username = ini_get("mysqli.default_user") [, string $passwd = ini_get("mysqli.default_pw") [, string $dbname = "" [, int $port = ini_get("mysqli.default_port") [, string $socket = ini_get("mysqli.default_socket") ]]]]]] )

```
##Parameters##
***host*** - Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol.

Prepending host by p: opens a persistent connection. [mysqli_change_user()](http://php.net/manual/en/mysqli.change-user.php) is automatically called on connections opened from the connection pool.

***username*** - The MySQL user name.

***passwd*** - If not provided or NULL, the MySQL server will attempt to authenticate the user against those user records which have no password only. This allows one username to be used with different permissions (depending on if a password as provided or not).

***dbname*** - If provided will specify the default database to be used when performing queries.

***port*** - Specifies the port number to attempt to connect to the MySQL server.

***socket*** - Specifies the socket or named pipe that should be used.

#####Note:

Specifying the socket parameter will not explicitly determine the type of connection to be used when connecting to the MySQL server. How the connection is made to the MySQL database is determined by the host parameter.


##Using with MySQLi
Since this is based on MySQLi, you can use `$db->DBH->MySQLiFunctionHere`. Great for transitioning other projects to using this.