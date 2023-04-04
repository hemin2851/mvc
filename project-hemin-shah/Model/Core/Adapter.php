<?php
class Model_Core_Adapter
{
	public $serverName = 'localhost:3307';
	public $userName = 'root';
	public $passWord = '';
	public $dbName = 'project-hemin-shah';

	public function connect()
	{
		$connect = mysqli_connect($this->serverName,$this->userName,$this->passWord,$this->dbName);
		return $connect;
	}

	public function fetchAll($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if(!$result)
		{
			return false;
		}
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	public function fetchRow($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if (!$result) 
		{
			return false;
		}
		return $result->fetch_assoc();
	}

	public function fetchPairs($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if(!$result)
		{
			return false;
		}
		$data = $result->fetch_all();
		$column1 = array_column($data, '0');
		$column2 = array_column($data, '1');
		if (!$column2) 
		{
			$column2 = array_fill(0, count($column1), null);
		}
		return array_combine($column1,$column2);

	}

	public function fetchOne($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if(!$result)
		{
			return false;
		}
		$row =  $result->fetch_array();
		return(array_key_exists(0, $row)) ? $row[0] : null;
	}



	public function insert($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if (!$result) 
		{
			return false;
		}
		return $connect->insert_id;
	}

	public function update($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if (!$result)
		 {
			return false;
		}
		return true;
	}

	public function delete($query)
	{
		$connect = $this->connect();
		$result = $connect->query($query);
		if (!$result)
		{
			return false;
		}
		return true;
	}

}

?>