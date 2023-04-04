<?php
class Model_Core_Table_Row
{
	protected $data = [];
	protected $tableObject = null;
	protected $tableClass = null;

	public function __construct()
	{
		
	}
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function getData($key)
	{
		if ($key == null) 
		{
			return $this->data;
		}
		if (array_key_exists($key, $this->data)) 
		{
			return $this->data[$key];
		}
		return null;
	}
	public function getId()
	{
		$primaryKey = $this->getTableObject()->getPrimaryKey();
		return (int)$this->$primaryKey;
	}
	public function __get($key)
	{
		if (array_key_exists($key, $this->data)) 
		{
			return $this->data[$key];
		}
		return null;
	}

	public function __set($key,$value)
	{
		$this->data[$key] = $value;
	}

	public function __unset($key)
	{
		if (array_key_exists($key, $this->data)) 
		{
			unset($this->data[$key]);
		}
		return $this;
	}

	public function getTableName()
	{
		if ($this->tableName) 
		{
			return $this->tableName;
		}
		$this->getTableObject()->getTableName();
		return $tableName;
	}


	public function getPrimaryKey()
	{
		if ($this->primaryKey) 
		{
			return $this->primaryKey;
		}
		$this->getTableObject()->getPrimaryKey();
		return $primaryKey;
	}

	
	public function getTableObject()
	{
		if ($this->tableObject) 
		{
			return $this->tableObject;
		}
		$tableObject = new ($this->tableClass)();
		$this->setTableObject($tableObject);
		return $tableObject;
	}

	protected function setTableObject($tableObject)
	{
		$this->tableObject = $tableObject;
		return $this;
	}

	public function getStatusText()
	{
		$statusOptions = $this->getTableObject()->getStatusOptions();
		if (array_key_exists($this->status, $statusOptions)) {
			return $statusOptions[$this->status];
		}
		return $statusOptions[Model_Core_Table::STATUS_DEFAULT];
	}

	public function fetchAll($query)
	{
		$result = $this->getTableObject()->fetchAll($query);
		if (!$result) 
		{
			return false;
		}
		$rows = [];
		foreach ($result as $row) 
		{
			$rows[] = (new $this)->setData($row);
		}
		return $rows;
	}

	public function fetchRow($query)
	{
		$result = $this->getTableObject()->fetchRow($query);
		if ($result) 
		{
			$this->data = $result;
			return $this;
		}
		return $this;
	}

	public function load($id , $column = null)
	{
		$column = (!$column) ? $this->getPrimaryKey() : $column;
		$query = "SELECT * FROM {$this->getTableName()} WHERE `{$column}` = {$id}";
		$table = new Model_Core_Table();
		$row = $table->fetchRow($query);
		if ($row) 
		{
			$this->data = $row;
		}
		return $this;
	}

	public function save()
	{
		if (!array_key_exists($this->getPrimaryKey(), $this->data)) 
		{
			$insert = $this->getTableObject()->insert($this->data);
			if ($insert) 
			{
				$this->load($insert);
				return $this;
			}
			return null;
		}
		else
		{
			$id = $this->data[$this->getPrimaryKey()];
			if (!$id) 
			{
				throw new Exception("id not found", 1);
				
			}
			$update = $this->getTableObject()->update($this->data,$id);
			if ($update) 
			{
				$this->load($id);
				return$this;
			}
			return null;

		}

	}

	public function delete()
	{
		$id = $this->data[$this->getPrimaryKey()];
		if (!$id) 
		{
			throw new Exception("id not found", 1);
		}
		$delete = $this->getTableObject()->delete($id);
		return $delete;
	}

}
?>