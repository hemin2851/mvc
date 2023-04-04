<?php
class Model_category_Row extends Model_Core_Table_Row
{
	protected $tableName = "category";
	protected $primaryKey = "category_id";
	protected $tableClass = "Model_category";

	public function updatePath()
	{
		if (!$this->getId()) 
		{
			return false;
		}
		$parent = Ccc::getModel('category_Row')->load($this->parent_id);
		$oldPath = $this->path;
		if (!$parent) 
		{
			$this->path = $this->getId();
		}
		else
		{
			$this->path = $parent->path.'='.$this->getId();
		}
		$this->save();
		$query ="UPDATE `category`
				SET `path` = REPLACE(`path`,'{$oldPath}=','{$this->path}=')
				WHERE `path` LIKE '{$oldPath}=%';";
		$this->getTableObject()->getAdapter()->update($query);
		return $this;
	}

	public function getPathName()
	{
		$pathArray = explode('=', $this->path);
		$query = "SELECT `category_id`,`name` FROM `category`;";
		$categoryNameArray = $this->getTableObject()->getAdapter()->fetchPairs($query);
		foreach ($pathArray as $id => &$categoryId) 
		{
			foreach ($categoryNameArray as $key => $categoryName) 
			{
				if ($categoryId == $key) 
				{
					$categoryId = $categoryName;
				}
			}
		}
		return implode('=>', $pathArray);
	}
}
?>