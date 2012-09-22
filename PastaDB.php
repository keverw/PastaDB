<?php
class PastaDB //class interacts with database
{
	/* Properties */
	public $DBH = null; //MySQLi database handler
	public $RawPasta = null;
	public $error = null;
	public $errorNum = null;
	
	/* results */
	public $affectedRows = 0;
	public $numRows = 0;
	
	/* charset */
	private $charsetDefault = null;
		
	/* Methods */
	function __construct($charset = 'utf8')
	{
		$this->charsetDefault = $charset;
	}
	
	public function _setSQLiError($errorNum = null, $error = '') //should be private but public so RawPasta can call it.
	{
		if ($errorNum)
		{
			$this->errorNum = $errorNum;
			$this->error = $error;
		}
		else
		{
			$this->errorNum = $this->DBH->errno;
			$this->error = $this->DBH->error;
		}
		
		return null;
	}
	
	public function connect()
	{
		$args = func_get_args();
		
		$reflector = new ReflectionClass('mysqli');
		$this->DBH = @$reflector->newInstanceArgs($args);

		if ($this->DBH->connect_error) //connection error
		{
			$this->error = $this->DBH->connect_error;
			$this->errorNum = $this->DBH->connect_errno;
			return false;
		}
		else //no error!
		{
			//sets the default character set
			if ($this->DBH->set_charset($this->charsetDefault))
			{
				$this->RawPasta = new RawPasta($this);
				return true;
			}
			else
			{
				$this->_setSQLiError();
				return false;
			}
			
		}
	}
	
	public function clean($mixedValue)
	{
		if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
		{
			$mixedValue = stripcslashes($mixedValue);
		}
		
		return addcslashes($this->DBH->real_escape_string($mixedValue), '%_'); //escapes using real_escape_string, then escapes _ (underscore) and % (percent) signs
	}
	
	public function query($string)
	{
		$result = $this->DBH->query($string);
		if(!$result && $this->DBH->error)
		{
			$this->_setSQLiError();
			return false;
		}
		else
		{
			$this->affectedRows = $this->DBH->affected_rows;
			
			if($result instanceof mysqli_result)
			{
				$this->numRows = $result->num_rows;
				
				$records = array();
				if($this->numRows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						$records[] = $row;
					}
				}
				$result->close();
				return $records;
			}
		}
	}
}

class RawPasta //class output SQL strings
{
	/* Properties */
	public $PastaDB = null; //parrent class
	
	/* Methods */
	function __construct($parrent)
	{
		$this->PastaDB = $parrent;
	}
	
	public function insert()
	{
		$args = func_get_args();
		
		$argCount = count($args);
		
		if ($argCount > 0 && is_string($args[0])) //set $tableName
		{
			$tableName = array_shift($args);
			$argCount--;
			if ($argCount > 0 && is_array($args[0]))
			{
				$tableRows = array_shift($args);
				$argCount--;
				$additionalRow = $args;
				unset($args, $argCount);
				
				var_dump($tableName);
				print_r($tableRows);
				print_r($additionalRow);
			}
			else
			{
				$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing row in insert()');
				return false;
			}
			
		}
		else
		{
			$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing table name in insert()');
			return false;
		}
		
		return 'later';
	}
}
?>