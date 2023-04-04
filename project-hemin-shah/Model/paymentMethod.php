<?php
require_once 'Core/Table.php';

class Model_PaymentMethod extends Model_core_Table
{
	protected $tableName = "payment_method";
	protected $primaryKey = "payment_method_id";
}
?>