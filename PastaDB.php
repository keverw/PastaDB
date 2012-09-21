<?php
class PastaDB //class interacts with database
{
	/* Properties */
	public $DBH = null; //MySQLi database handler
	public $error = null;
	public $errorNum = null;
	
	/* Methods */
	function connect()
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
			return true;
		}
	}

}

class RawPasta //class output SQL strings
{
	
}
?>