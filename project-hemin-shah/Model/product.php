<?php
require_once 'Core/Table.php';


class Model_Product extends Model_core_Table
{
	protected $tableName = "product";
	protected $primaryKey = "product_id";
}

?>