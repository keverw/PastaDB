<?php
/**
* @project PastaDB
* @version 0.9
* @url https://github.com/keverw/PastaDB
* @about A powerful yet simple database abstraction layer library
**/
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
	public $insertedID = 0;
	
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
		
		return $this->DBH->real_escape_string($mixedValue); //escapes using real_escape_string
	}
	
	public function cleanLike($mixedValue)
	{
		if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
		{
			$mixedValue = stripcslashes($mixedValue);
		}
		
		return str_replace(array('%', '_'), array('\\%', '\_'), $mixedValue);
	}
	
	public function cleanBoth($mixedValue)
	{
		return $this->clean(
			$this->cleanLike($mixedValue)
		);
	}
	
	public function query($string)
	{
		$result = $this->DBH->query($string);
		if(!$result && strlen($this->DBH->error) > 0)
		{
			$this->_setSQLiError();
			return false;
		}
		else
		{
			$this->affectedRows = $this->DBH->affected_rows;
			$this->insertedID = $this->DBH->insert_id;
			
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
				
				if (count($records) > 0) //if more than 0 record, return records
				{
					return $records;
				}
				else //else return true, you should always use $db->numRows before trying to do a foreach loop or count on the result.
				{
					return true;
				}
				
			}
			else
			{
				return true;
			}
		}
	}
	
	public function insert()
	{
		return $this->query(call_user_func_array(array($this->RawPasta, 'insert'), func_get_args()));
	}
	
	public function replace()
	{
		return $this->query(call_user_func_array(array($this->RawPasta, 'replace'), func_get_args()));
	}
	
	public function select()
	{
		return $this->query(call_user_func_array(array($this->RawPasta, 'select'), func_get_args()));
	}
	
	public function count()
	{
		$result = $this->query(call_user_func_array(array($this->RawPasta, 'count'), func_get_args()));
		
		if ($result)
		{
			return array_pop($result[0]);
		}
		else
		{
			return 'err';
		}
	}

	public function itemExists($table, $col, $value)
	{
		$dbErr = false;
		$exists = false;

		$result = $this->count($table, $col, array(
			$col => $value
		));

		if (is_numeric($result))
		{
			if ($result > 0)
			{
				$exists = true;
			}
		}
		else
		{
			$dbErr = true;
		}

		return array(
			'err' => $dbErr,
			'exists' => $exists
		);
	}
	
	public function update()
	{
		return $this->query(call_user_func_array(array($this->RawPasta, 'update'), func_get_args()));
	}
	
	public function delete()
	{
		return $this->query(call_user_func_array(array($this->RawPasta, 'delete'), func_get_args()));
	}
	
	//transactions
	public function begin() //start transaction
	{
		return $this->DBH->autocommit(false);
	}
	
	public function rollback()
	{
		if ($this->DBH->rollback())
		{
			if ($this->DBH->autocommit(true))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function commit()
	{
		if ($this->DBH->commit())
		{
			if ($this->DBH->autocommit(true))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
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
	
	private function genreateWhere($lastArgs) //array or $where, $escapes
	{
		$args = func_get_args();
		
		$argCount = count($lastArgs);
		
		if ($argCount > 0)
		{
			$argCount--;
			$where = array_shift($lastArgs);
			
			if (is_string($where))
			{
				if ($argCount > 0) //has escapes
				{
					$newArrgs = array($where);
					
					$escapes = $lastArgs;
					
					foreach ($escapes as $key => $value)
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
			else if (is_array($where))
			{
				foreach ($where as $key => $value)
				{
					$rows[] = '`' . $key . "` = '" . $this->PastaDB->clean($value) . "'";
				}
				
				$sql = implode(' AND ', $rows);
				return $sql;
			}
			else
			{
				return '';
			}
			
		}
		else
		{
			return '';
		}
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
	
	private function Internalinsert()
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
				$additionalRow = $args;
				unset($args, $argCount);
				
				$sql = $keyword . " `$tableName` ";
				
				$cols = array();
				$rows = array();
				
				foreach ($tableRows as $key => $value)
				{
					$cols[] = '`' . $key . '`';
					$rows[] = "'" . $this->PastaDB->clean($value) . "'";
				}
				
				$sql .= '(' . implode(', ', $cols) . ') VALUES (' . implode(', ', $rows) . ')';
				
				unset($cols, $rows, $tableRows);
				
				if (count($additionalRow) > 0)
				{
					$lastRows = array();
					
					foreach ($additionalRow as $key => $value)
					{
						$rows = array();
						foreach ($value as $key => $value)
						{
							$rows[] =  "'" . $this->PastaDB->clean($value) . "'";
						}
						$lastRows[] = '(' . implode(', ', $rows) . ')';
					}
					
					$sql .= ', ' . implode(', ', $lastRows);
				}
				
				return $sql . ';';
			}
			else
			{
				$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing row in insert() or replace()');
				return false;
			}
			
		}
		else
		{
			$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing table name in insert() or replace()');
			return false;
		}
	}
	
	public function select()
	{
		return $this->genreateWhere(func_get_args());
	}
	
	public function update()
	{
		$args = func_get_args();
		
		$argCount = count($args);
		
		if ($argCount > 0)
		{
			$tableName = array_shift($args);
			$argCount--;
			
			if ($argCount > 0 && is_array($args[0]))
			{
				$set = array_shift($args);
				$argCount--;
				
				if ($argCount > 0)
				{
					$where = $this->genreateWhere($args);
				}
				else
				{
					$where = '';
				}
				unset($argCount, $args);
					
				//generate the sql
				$sql = 'UPDATE `' . $tableName . '` SET ';
				
				$rows = array();
				
				foreach ($set as $key => $value)
				{
					$rows[] = '`' . $key . "` = '" . $this->PastaDB->clean($value) . "'";
				}
				
				$sql .= implode(', ', $rows);
				
				if (strlen($where) > 0)
				{
					$sql .= ' WHERE ' . $where;
				}
				
				return $sql . ';';
			}
			else
			{
				$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing set array in update()');
				return false;
			}
		}
		else
		{
			$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing table name in update()');
			return false;
		}
	}
	
	public function delete() //table, where, escapes
	{
		$args = func_get_args();
		
		$argCount = count($args);
		
		if ($argCount > 0)
		{
			$tableName = array_shift($args);
			$argCount--;
			
			if ($argCount > 0)
			{
				$where = $this->genreateWhere($args);
			}
			else
			{
				$where = '';
			}
			unset($argCount, $args);
			
			//generate the sql
			$sql = 'DELETE FROM `' . $tableName . '`';
			
			if (strlen($where) > 0)
			{
				$sql .= ' WHERE ' . $where;
			}
			
			return $sql . ';';
		}
		else
		{
			$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing table name in delete()');
			return false;
		}
	}
	
	public function count() //$table, $what = '*', $where = null
	{
		$args = func_get_args();
		
		if (count($args) > 0)
		{
			$tableName = array_shift($args);
			
			if (count($args) > 0)
			{
				$what = array_shift($args);
			}
			else
			{
				$what = '*';
			}
			
			$where = $this->genreateWhere($args);
			
			$output = 'SELECT COUNT(' . $what . ') FROM ' . $tableName;
			
			if (strlen($where) > 0)
			{
				$output .= ' WHERE ' . $where;
			}
			
			return $output;
		}
		else
		{
			$this->PastaDB->_setSQLiError(1064, 'RawPasta: Missing table name in count()');
			return false;
		}
	}
}
?>