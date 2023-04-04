<?php
require_once 'Core/Table.php';

class Model_Customer extends Model_core_Table
{
	protected $tableName = "customer";
	protected $primaryKey = "customer_id";
}
?>