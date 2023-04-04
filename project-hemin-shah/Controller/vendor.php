<?php

class Controller_Vendor extends Controller_Core_Action
{

	public function gridAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$RowVendor = Ccc::getModel('vendor_Row');
			$request = $this->getRequest();
			$query = "SELECT * FROM `vendor`";
			$vendors = $RowVendor->fetchAll($query);
			if (!$vendors) 
			{
				throw new Exception("Invalid Request", 1);
			}
		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		$this->getView()->setTemplate('vendor/vendor-grid.phtml')->setData($vendors);
		$this->render();
	}

	public function addAction()
	{
		$RowVendor = Ccc::getModel('vendor_Row');
		$RowVendorAddress = Ccc::getModel('vendorAddress_Row');
		$this->getView()->setTemplate('vendor/vendor-edit.phtml')->setData(["vendor"=>$RowVendor,
			"vendor_address"=>$RowVendorAddress]);
		$this->render();
	}

	public function editAction()
	{
		try 
		{
			$request = $this->getRequest();
			$RowVendor = Ccc::getModel('vendor_Row');
			$message = $this->getMessageObject();
			$RowVendorAddress = Ccc::getModel('vendorAddress_Row');
			$id = $request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
			}
			$vendor = $RowVendor->load($id);
			if (!$vendor) 
			{
				throw new Exception("Invalid Request", 1);
			}
			$query = "SELECT * FROM `vendor_address` WHERE `vendor_id` = {$id}";
			$vendor_address = $RowVendorAddress->fetchRow($query);
			if (!$vendor_address) 
			{
				throw new Exception("Invalid Request in address", 1);
			}
		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		
		$this->getView()->setTemplate('vendor/vendor-edit.phtml')->setData(["vendor"=>$vendor,
			"vendor_address"=>$vendor_address]);
		$this->render();
	}

	public function saveAction()
	{
		try 
		{
			$url = $this->getUrlObject();
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			if (!$request->isPost())
			{
				throw new Exception("invalid Request.", 1);
			}
			$vendor = $request->getPost('vendor');
			$vendorAddress = $request->getPost('vendor_address');

			$RowVendor = Ccc::getModel('vendor_Row');
			$RowVendorAddress = Ccc::getModel('vendorAddress_Row');

			if($id=(int)$request->getParams('id'))
			{
				if(!$RowVendor->load($id))
				{
					throw new Exception("invalid id.", 1);
				}
				$vendor['vendor_id'] = $id;
				$query = "SELECT * FROM `vendor_address` WHERE `vendor_id` = {$id}";
				$vendorAddressRow = $RowVendorAddress->fetchRow($query);
				if(!$vendorAddressRow)
				{
					throw new Exception("invalid Customer address.", 1);
				}
				$vendorAddress['address_id'] = $vendorAddressRow->address_id;
				$vendorAddress['vendor_id'] = $vendorAddressRow->vendor_id;
			}

			
			if (!$RowVendor->setData($vendor)) 
			{
				throw new Exception("Customer not inserted.", 1);
			}
			if ( $RowVendor->vendor_id) 
			{
				$RowVendor->updated_at = date('Y-m-d H:i:s');
			}
			else
			{
				$RowVendor->created_at = date('Y-m-d H:i:s');
			}
			$RowVendor->save();

			if(!$id)
			{
			$vendorAddress['vendor_id'] = $RowVendor->vendor_id;
			}
			$insertVendorAddress = $RowVendorAddress->setData($vendorAddress);
			$insertVendorAddress->save();
			if (!$insertVendorAddress) {
				throw new Exception("Customer Address not inserted.", 1);
			}

			$message->addMessage('vendor saved successfully.');
		}
		catch (Exception $e)
		{
			$message->addMessage($e->getMessage(), $message::FAILURE);
		}
		$this->redirect($url->getUrl('vendor','grid'));
	}
	public function deleteAction()
	{
		$request = $this->getRequest();
		$RowVendor = Ccc::getModel('vendor_Row');
		$url = $this->getUrlObject();
		$id = $request->getParams('id');
		if (!$id) 
		{
			$request->errorAction();
		}
		$RowVendor->load($id);
		$result = $RowVendor->delete();
		if (!$result) 
		{
			$request->errorAction();
		}
		$this->redirect($url->getUrl('vendor','grid'));
	}
}


?>