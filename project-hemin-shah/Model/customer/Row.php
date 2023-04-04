<?php
require_once 'Model/Core/Table/Row.php';
class Model_Customer_Row extends Model_Core_Table_Row
{
	protected $tableName = "customer";
	protected $primaryKey = "customer_id";
	protected $tableClass = "Model_Customer";
}
?>