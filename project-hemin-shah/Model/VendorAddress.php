<?php
require_once 'Core/Table.php';

class Model_VendorAddress extends Model_core_Table
{
	protected $tableName = "vendor_address";
	protected $primaryKey = "address_id";

	// public function insert($data = [])
	// {
	// 	if (!$data) 
	// 	{
	// 		throw new Exception("unable to find data", 1);
	// 	}
	// 	$keys = "`".implode("`, `", array_keys($data))."`";
	// 	$values = "'".implode("','", array_values($data))."'";
	// 	$date = date("Y-m-d h:i:s");
	// 	$query = "INSERT INTO `{$this->getTableName()}`({$keys}) VALUES ({$values})";
	// 	$result = $this->getAdapter()->insert($query);
	// 	if (!$result) 
	// 	{
	// 		throw new Exception('result not found', 1);
			
	// 	}
	// 	return $result;
	// }

	// public function update($data = [] , $condition = null)
	// {	
	// 	$datavalue = [];
	// 	foreach ($data as $key => $value) 
	// 	{
	// 		$datavalue[] = "`$key` = '$value'";
	// 	}
	// 	if (is_array($condition)) 
	// 	{
	// 		$where = [];
	// 		foreach ($condition as $key => $value) 
	// 		{
	// 			$where[] = "`{$key}` = '{$value}'"; 

	// 		}
	// 		$whereString = implode(" AND ", $where);
	// 	}
	// 	else 
	// 	{
	// 		$whereString = "`vendor_id` = {$condition}";
	// 	}
	// 	echo $query = "UPDATE `{$this->getTableName()}` SET ".implode(', ',$datavalue)." WHERE {$whereString}";
	// 	$result = $this->getAdapter()->update($query);
		
	// 	if (!$result) 
	// 	{
	// 		throw new Exception('result not found', 1);
			
	// 	}
	// 	return $result;
	// }
}
?>