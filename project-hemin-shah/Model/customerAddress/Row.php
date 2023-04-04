<?php
require_once 'Model/Core/Table/Row.php';
class Model_CustomerAddress_Row extends Model_Core_Table_Row
{
	protected $tableName = "customer_address";
	protected $primaryKey = "address_id";
	protected $tableClass = "Model_CustomerAddress";
}
?>