<?php

class Controller_Category extends Controller_Core_Action
{


	public function gridAction()
	{
		$categoryRow = Ccc::getModel('category_Row');
		$query = "SELECT * FROM `category` WHERE `category_id`>1";
		$categories = $categoryRow->fetchAll($query);
		$this->getView()->setTemplate('category/category-grid.phtml')->setData($categories);
		$this->render();
	}

	public function addAction()
	{
		$categoryRow = Ccc::getModel('category_Row');
		$query = 'SELECT * FROM `category`';
		$categories = $categoryRow->fetchAll($query);
			$this->getView()->setTemplate('category/category-edit.phtml')->setData(['categories'=>$categories,'category'=>$categoryRow]);
		$this->render();
	}
	
	public function editAction()
	{
		try {
			
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$id = (int)$request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
			}
			$category = Ccc::getModel('category_Row')->load($id);
			$query = "SELECT * FROM `category` WHERE `path` NOT LIKE '{$category->path}=%' AND `path` NOT LIKE '{$category->path}';";
			$parentCategories = Ccc::getModel('category_Row')->fetchAll($query);
			if (!$parentCategories) 
			{
				throw new Exception("data not found", 1);
			}

		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		$this->getView()->setTemplate('category/category-edit.phtml')->setData(['categories'=>$parentCategories,'category'=>$category]);
		$this->render();
		
		

	}

	public function saveAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			if (!$request->isPost()) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			$postData = $request->getPost('category');
			if (!$postData) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			$categoryRow = Ccc::getModel('category_Row');
			if ($id = (int)$request->getParams('id')) 
			{
				$data = $categoryRow->load($id);
				if (!$data) 
				{
					throw new Exception("Error Processing Request", 1);
				}
				$postData['category_id'] = $data->category_id;
				$postData['path'] = $data->path;
			}
			$category = $categoryRow->setData($postData);
			$result = $categoryRow->save();
			if (!$result) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			if (!$id) 
			{
				$category->category_id = $result->category_id;
			}
			$result->updatePath();
			$message->addMessage('saved successfuly');
			$url = $this->getUrlObject();
			$this->redirect($url->getUrl('category','grid',null,true));
		}
		 catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
	}

	
	public function deleteAction()
	{
		try 
		{
			$request = $this->getRequest();
			$message = $this->getMessageObject();
			$categoryRow = Ccc::getModel('category_Row');
			$url = $this->getUrlObject();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$categoryRow->load($id);
			$result = $categoryRow->delete();
			if (!$result) 
			{
				$request->errorAction();
			}
			$message->addMessage('data deleted successfully');
		} 
		catch (Exception $e) 
		{
			$message->addMessage('data not deleted',$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('category','grid',null,true));
	}
}

?>
