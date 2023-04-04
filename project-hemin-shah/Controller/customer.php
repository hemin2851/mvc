<?php

class Controller_Customer extends Controller_Core_Action
{
	
	public function gridAction()
	{
		$RowCustomer = Ccc::getModel('Customer_Row');
		$query = "SELECT * FROM `customer`";
		$customers = $RowCustomer->fetchAll($query);
		$this->setCustomers($customers);
		$this->getTemplate('customer/customer-grid.phtml');
	}

	public function addAction()
	{
		$this->getTemplate('customer/customer-add.phtml');
	}

	public function insertAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$RowCustomer = new Model_Customer_Row();
			$RowCustomerAddress = new Model_CustomerAddress_Row();
			$url = new Model_Core_Url();
			$request = $this->getRequest();
			if (!$request->isPost()) 
			{
				$request->errorAction();
			}
			$customers = $request->getPost('customer');
			$RowCustomer->setData($customers);
			$result = $RowCustomer->save();
			if (!$result) 
			{
				$request->errorAction();
			}

			$address = $request->getPost('customer_address');
			$RowCustomerAddress->setData($address);
			$RowCustomerAddress->customer_id = $RowCustomer->customer_id;
			$resultAddress = $RowCustomerAddress->save();
			if (!$resultAddress) 
			{
				$request->errorAction();
			}
			$message->addMessage('data inserted successfully');
			
		} 
		catch (Exception $e) 
		{
			$message->addMessage('data not inserted',$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('customer','grid'));
	}

	public function editAction()
	{
		try {
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowCustomer = new Model_Customer_Row();
			$RowCustomerAddress = new Model_CustomerAddress_Row();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$result->errorAction();
			}
			$customer = $RowCustomer->load($id);
			$this->setCustomers($customer);
			if (!$customer) {
				throw new Exception("data could not load", 1);
			}
			$RowCustomerAddress->customer_id = $RowCustomer->customer_id;
			$customer_address = $RowCustomerAddress->load($id);
			$this->setCustomerAddress($customer_address);
			if (!$customer_address) {
				throw new Exception("data of address could not load", 1);
				
			}
			
		} catch (Exception $e) 
		{
			$message->addMessage('data not edited',$message::FAILURE);
		}
		
		$this->getTemplate('customer/customer-edit.phtml');
	}

	public function updateAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowCustomer = new Model_Customer_Row();
			$RowCustomerAddress = new Model_CustomerAddress_Row();
			$url = new Model_Core_Url();
			$id = $request->getPost('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$customer = $request->getPost('customer');
			$RowCustomer->setData($customer);
			$RowCustomer->updated_at = date('Y-m-d H-i-s');
			$RowCustomer->customer_id = $id;
			$result = $RowCustomer->save();
			if (!$result) 
			{
				$request->errorAction();
			}
			$customer_address = $request->getPost('customer_address');
			$RowCustomerAddress->setData($customer_address);
			$result = $RowCustomerAddress->save();
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
		
		$this->redirect($url->getUrl('customer','grid'));

	}

	public function deleteAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowCustomer = new Model_Customer_Row();
			$url = new Model_Core_Url();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$RowCustomer->load($id);
			$result = $RowCustomer->delete();
			if (!$result) 
			{
				$request->errorAction();
			}
			$message->addMessage('deleted successfully');
		} 
		catch (Exception $e) 
		{
			$message->addMessage('data not deleted',$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('customer','grid'));
	}
}


?>