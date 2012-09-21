<?php
class PastaDB //class interacts with database
{
	/* Properties */
	public $DBH = null; //MySQLi database handler
	public $RawPasta = null;
	public $error = null;
	public $errorNum = null;
	
	/* charset */
	private $charsetDefault = null;
		
	/* Methods */
	function __construct($charset = 'utf8')
	{
		$this->charsetDefault = $charset;
	}
	
	private function _setSQLiError()
	{
		$this->error = $this->DBH->error;
		$this->errorNum = $this->DBH->errno;
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
}
?>