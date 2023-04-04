<?php
require_once 'Adapter.php';
class Model_Core_Table
{
	protected $tableName = null;
	protected $primaryKey = null;
	protected $adapter = null;
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 1;

	public function getStatusOptions()
	{
		return[
			self::STATUS_ACTIVE => self::STATUS_ACTIVE_LBL,
			self::STATUS_INACTIVE => self::STATUS_INACTIVE_LBL,
		];
	}

	function __construct()
	{
		$this->getAdapter();
	}

	public function getAdapter()
	{
		if ($this->adapter) 
		{
			return $this->adapter;
		}
		$adapter = new Model_Core_Adapter();
		$this->setAdapter($adapter);
		return $adapter;
	}

	protected function setAdapter(Model_Core_Adapter$adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function getTableName()
	{
		if ($this->tableName) 
		{
			return $this->tableName;
		}
		$this->setTableName($tableName);
		return $tableName;
	}

	protected function setTableName($tableName)
	{
		$this->tableName = $tableName;
		return $this;
	}

	public function getPrimaryKey()
	{
		if ($this->primaryKey) 
		{
			return $this->primaryKey;
		}
		$this->setPrimaryKey($primaryKey);
		return $primaryKey;
	}

	protected function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
		return $this;
	}


	public function fetchAll($query = NULL)
	{
		if ($query == null) 
		{
			throw new Exception('query not found', 1);
		}
		$result = $this->getAdapter()->fetchAll($query);
		return $result;
	}

	public function fetchRow($query = null)
	{
		if ($query == null) {

			throw new Exception("Invaild request.", 1);
		}
		$result = $this->getAdapter()->fetchRow($query);
		return $result;
	}

	
	public function insert($data = [])
	{
		if (!$data) 
		{
			throw new Exception("unable to find data", 1);
		}
		$keys = "`".implode("`, `", array_keys($data))."`";
		$values = "'".implode("','", array_values($data))."'";
		$query = "INSERT INTO `{$this->getTableName()}`({$keys}) VALUES ({$values})";
		$result = $this->getAdapter()->insert($query);
		if (!$result) 
		{
			throw new Exception('result not found', 1);
			
		}
		return $result;
	}

	public function update($data  , $condition = null)
	{	
		$datavalue = [];
		foreach ($data as $key => $value) {
			$datavalue[] = "`$key` = '$value'";
		}
		if (is_array($condition)) 
		{
			$where = [];
			foreach ($condition as $key => $value) 
			{
				$where[] = "`{$key}` = '{$value}'"; 

			}
			$whereString = implode(" AND ", $where);
		}
		else 
		{
			$whereString = "`{$this->getPrimaryKey()}` = {$condition}";
		}
		$query = "UPDATE `{$this->getTableName()}` SET ".implode(', ',$datavalue)." WHERE {$whereString}";
		$result = $this->getAdapter()->update($query);
		
		if (!$result) 
		{
			throw new Exception('result not found', 1);
			
		}
		return $result;

	}

	public function delete($condition = [])
	{
		if (!$condition) 
		{
			throw new Exception("Error Processing Request", 1);
			
		}
		if (is_array($condition)) 
		{
			$where = [];
			foreach ($condition as $key => $value) 
			{
				$where[] = "'{$key}' = '{$value}'"; 
			}
			$whereString = implode(" AND ", $where);
		}
		else 
		{
			$whereString = "{$this->getPrimaryKey()} = {$condition}";
		}
		
		$query = "DELETE FROM `{$this->getTableName()}` WHERE  {$whereString}";
		$result = $this->getAdapter()->delete($query);
		if (!$result) 
		{
			throw new Exception('result not found', 1);
			
		}
		return $result;
	}

	public function load($value , $column = null)
	{
		$column = (!$column) ? $this->getPrimaryKey() : $column;
		$query = "SELECT * FROM {$this->getTableName()} WHERE `{$column}` = {$value}";
		$row = $this->getAdapter()->fetchRow($query);
		return $row;
	}

}
?>