<?php

class Controller_Salesman extends Controller_Core_Action
{
	
	public function gridAction()
	{
		$message = $this->getMessageObject();
		$sal = new Model_Salesman();
		$request = $this->getRequest();
		$query = "SELECT * FROM `salesmen`";
		$salesmen = $salesmanModel->fetchAll($query);
		
	}

	public function addAction()
	{
		$this->getTemplate('salesman/salesman-add.phtml');
	}

	public function insertAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$salesmanModel = new Model_Salesman();
			$salesmanAddressModel = new Model_SalesmanAddress();
			$url = new Model_Core_Url();
			$request = $this->getRequest();
			if (!$request->isPost()) 
			{
				$request->errorAction();
			}
			$salesmen = $request->getPost('salesman');
			$result = $salesmanModel->insert($salesmen);
			if (!$result) 
			{
				$request->errorAction();
			}

			$address = $request->getPost('salesman_address');
			$address['salesmen_id'] = $result;
			$resultAddress = $salesmanAddressModel->insert($address);
			if (!$resultAddress) 
			{
				$request->errorAction();
			}
			$message->addMessage('data inserted successfully',$message::SUCCESS);
		} 
		catch (Exception $e) 
		{
			$message->addMessage('data not deleted',$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('salesman','grid'));
	}

	public function editAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$salesmanModel = new Model_Salesman();
			$salesmanAddressModel = new Model_SalesmanAddress();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$result->errorAction();
			}
			$query = "SELECT * FROM `salesmen` WHERE `salesmen_id` = {$id}";
			$salesmen = $salesmanModel->fetchRow($query);
			$this->setSalesmen($salesmen);
			$query = "SELECT * FROM `salesmen_address` WHERE `salesmen_id` = {$id}";
			$salesmen_address = $salesmanAddressModel->fetchRow($query);
			$this->setSalesmenAddress($salesmen_address);
		}
		catch (Exception $e) 
		{
			$message->addMessage('data not found for edit',$message::FAILURE);
		}
		
		$this->getTemplate('salesman/salesman-edit.phtml');
	}

	public function updateAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$salesmanModel = new Model_Salesman();
			$salesmanAddressModel = new Model_SalesmanAddress();
			$url = new Model_Core_Url();
			$id = $request->getPost('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$salesman = $request->getPost('salesman');
			$salesman['updated_at'] = date('Y-m-d H-i-s');
			$result = $salesmanModel->update($salesman,$id);
			if (!$result) 
			{
				$request->errorAction();
			}
			$salesman_address = $request->getPost('salesman_address');
			$result = $salesmanAddressModel->update($salesman_address,$id);
			if (!$result) 
			{
				$request->errorAction();
			}
			$message->addMessage('data updated successfully');
		}
		catch (Exception $e) 
		{
			$message->addMessage('data not updated',$message::FAILURE);	
		}
		
		$this->redirect($url->getUrl('salesman','grid'));

	}

	public function deleteAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$salesmanModel = new Model_Salesman();
			$url = new Model_Core_Url();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$result = $salesmanModel->delete($id);
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
		
		$this->redirect($url->getUrl('salesman','grid'));
	}
}


?>