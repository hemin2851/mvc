<?php

class Controller_Admin extends Controller_Core_Action
{

	public function gridAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$RowAdmin = Ccc::getModel('Admin_Row');
			$query = "SELECT * FROM `admin`";
			$admins = $RowAdmin->fetchAll($query);
			if (!$admins) 
			{
				throw new Exception("Error Processing Request", 1);
			}
		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		
		$this->getView()->setTemplate('admin/admin-grid.phtml')->setData($admins);
		$this->render();
	}

	public function addAction()
	{
		$RowAdmin = Ccc::getModel('Admin_Row');
		$this->getView()->setTemplate('admin/admin-edit.phtml')->setData($RowAdmin);
		$this->render();
	}
	
	public function editAction()
	{
		try {
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowAdmin = Ccc::getModel('Admin_Row');
			$id = $request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
			}
			$admin = $RowAdmin->load($id);
			
		} catch (Exception $e) {
			$message->addMessage($e->getMessage(),$message::FAILURE);
			
		}
		
		$this->getView()->setTemplate('admin/admin-edit.phtml')->setData($admin);
		$this->render();

	}

	public function saveAction()
	{
		try 
		{
			$request = $this->getRequest();
			$url = $this->getUrlObject();
			$message = $this->getMessageObject();
			if (!$request->isPost()) 
			{
				throw new Exception("Invalid Request", 1);
			}
			$admin = $request->getPost('admin');
			if (!$admin) 
			{
				throw new Exception("no data posted", 1);
			}
			$RowAdmin = Ccc::getModel('Admin_Row');
			if (($id = (int) $request->getParams('id'))) 
			{
				if (!$RowAdmin->load($id)) 
				{
					throw new Exception("Error Processing Request", 1);
				}
				$admin['admin_id'] = $RowAdmin->admin_id;
			}
			$RowAdmin->setData($admin);
			if ( $RowAdmin->admin_id) 
			{
				$RowAdmin->updated_at = date('Y-m-d H:i:s');
			}
			else
			{
				$RowAdmin->created_at = date('Y-m-d H:i:s');
			}
			if (!$RowAdmin->save()) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			$message->addMessage('data saved successfully');

		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		$this->redirect($url->getUrl('admin','grid'));

	}



	public function deleteAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowAdmin = Ccc::getModel('Admin_Row');
			$url = new Model_Core_Url();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$RowAdmin->load($id);
			$result = $RowAdmin->delete();
			if (!$result) 
			{
				$request->errorAction();
			}	
			$message->addMessage('data deleted successfully');
		} catch (Exception $e) {
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('admin','grid',null,true));
	}
}

?>