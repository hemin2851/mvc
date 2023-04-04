<?php
require_once 'Model/Core/Table.php';

class Model_Salesman_Price extends Model_Core_Table
{

    protected $tableName = "salesmen_price";
    protected $primaryKey = "entity_id";

    public function insert($data = [])
    {
        if (!$data) 
        {
            throw new Exception("unable to find data", 1);
        }
        $keys = "`".implode("`, `", array_keys($data))."`";
        $values = "'".implode("','", array_values($data))."'";
        $date = date("Y-m-d h:i:s");
        $query = "INSERT INTO `{$this->getTableName()}`({$keys}) VALUES ({$values})";
        $result = $this->getAdapter()->insert($query);
        if (!$result) 
        {
            throw new Exception('result not found', 1);
            
        }
        return $result;
    }
}
?>