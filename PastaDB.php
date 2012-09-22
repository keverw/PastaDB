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
	
	public function replace()
	{
		$args = func_get_args();
		array_unshift($args, 'REPLACE');
		return call_user_func_array(array($this, 'Internalinsert'), $args);
	}
	
	public function insert()
	{
		$args = func_get_args();
		array_unshift($args, 'INSERT INTO');
		return call_user_func_array(array($this, 'Internalinsert'), $args);
	}
	
	public function Internalinsert()
	{
		$args = func_get_args();
		
		$keyword = array_shift($args);
		
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
				
				$sql = $keyword . " `$tableName` ";
				
				$cols = '';
				$row1 = '';
				
				foreach ($tableRows as $key => $value)
				{
					if ($cols == '')
					{
						$cols .= '`' . $key . '`';
					}
					else
					{
						$cols .= ', `' . $key . '`';;
					}
					
					if ($row1 == '')
					{
						$row1 .= "'" . $this->PastaDB->clean($value) . "'";
					}
					else
					{
						$row1 .= ", '" . $this->PastaDB->clean($value) . "'";
					}
				}
				
				$sql .= '(' . $cols . ') VALUES (' . $row1 . ')';
				
				unset($cols, $row1, $tableRows);
				
				if (count($additionalRow) > 0)
				{
					$lastRows = array();
					
					foreach ($additionalRow as $key => $value)
					{
						$genRow = '';
						foreach ($value as $key => $value)
						{
							if ($genRow == '')
							{
								$genRow .= "'" . $this->PastaDB->clean($value) . "'";
							}
							else
							{
								$genRow .= ", '" . $this->PastaDB->clean($value) . "'";
							}
						}
						$lastRows[] = '(' . $genRow . ')';
					}
					
					$sql .= ', ' . implode(', ', $lastRows);
				}
				
				return $sql . ';';
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
	}
	
	public function select() //$where, $escapes
	{
		$args = func_get_args();
		
		$where = array_shift($args);
		
		if (count($args) > 0)
		{
			$newArrgs = array($where);
		
			foreach ($args as $key => $value)
			{
				$newArrgs[] = $this->PastaDB->clean($value);
			}
			
			return call_user_func_array('sprintf', $newArrgs);
		}
		else
		{
			return $where;
		}
		
	}
	
	/*
	public function update() //table, set, where
	{
		
	}
	*/
}
?>