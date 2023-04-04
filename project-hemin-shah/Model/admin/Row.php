<?php
require_once 'Model/Core/Table/Row.php';
class Model_Admin_Row extends Model_Core_Table_Row
{
	protected $tableName = "admin";
	protected $primaryKey = "admin_id";
	protected $tableClass = "Model_Admin";
}
?>