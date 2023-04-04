<?php
require_once 'Model/Core/Table.php';
class Model_Product_Media extends Model_Core_Table
{
    protected $tableName = "media";
    protected $primaryKey = "media_id";

    public function update($data,$condition = null)
    {
        $set = "";
        foreach ($data as $column => $value) {
          $set .= '`'.$column.'` = "'.$value.'",';
        }
        
        $set = rtrim($set, ", ");
        $where = "";
        if (is_array($condition)) {
            if (!array_key_exists('product_id',$condition)) {
                $ids = join(',',$condition);
                $where = '`'.$this->getPrimaryKey().'` IN ('.$ids.')';   
            }else{
                foreach ($condition as $column => $value) {
                    $where .= '`'.$column.'` = "'.$value.'" AND ' ;
                }
            }
        }else{
            $where = '`'.$this->getPrimaryKey().'` = "'.$condition.'"'; 
        }

        $where = rtrim($where, " AND ");
        $query = 'UPDATE `'.$this->getTableName().'` SET '.$set.' WHERE '.$where;
        $adapter = $this->getAdapter();
        $result = $adapter->update($query);
        return $result;
    }
}
?>