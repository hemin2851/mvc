<?php

class Controller_ShippingMethod extends Controller_Core_Action
{

	public function gridAction()
	{
		try 
		{
			$RowShipping = Ccc::getModel('shipping_Row');
			$message = Ccc::getModel('Core_Message');
			$query = "SELECT * FROM `shipping_method`";
			$shippings = $RowShipping->fetchAll($query);
			if (!$shippings) 
			{
				throw new Exception("could not fetch grid", 1);
				
			}
		}
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);	
		}
		$this->getView()->setTemplate('shippingMethod/shipping-grid.phtml')->setData($shippings);
		$this->render();
	}

	public function addAction()
	{
		$RowShipping = Ccc::getModel('shipping_Row');
		$this->getView()->setTemplate('shippingMethod/shipping-edit.phtml')->setData($RowShipping);
		$this->render();
	}
	public function editAction()
	{
		try {
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowShipping = Ccc::getModel('shipping_Row');
			$id = $request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
				
			}
			$shipping = $RowShipping->load($id);
			
		} catch (Exception $e) {
			$message->addMessage($e->getMessage(),$message::FAILURE);
			
		}
		
		$this->getView()->setTemplate('shippingMethod/shipping-edit.phtml')->setData($shipping);
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
			$shipping = $request->getPost('shipping');
			if (!$shipping) 
			{
				throw new Exception("no data posted", 1);
			}
			$RowShipping = Ccc::getModel('shipping_Row');
			if (($id = (int) $request->getParams('id'))) 
			{
				if (!$RowShipping->load($id)) 
				{
					throw new Exception("Error Processing Request", 1);
				}
				$shipping['shipping_method_id'] = $RowShipping->shipping_method_id;
			}
			$RowShipping->setData($shipping);
			if ( $RowShipping->shipping_method_id) 
			{
				$RowShipping->updated_at = date('Y-m-d H:i:s');
			}
			else
			{
				$RowShipping->created_at = date('Y-m-d H:i:s');
			}
			if (!$RowShipping->save()) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			$message->addMessage('data saved successfully');

		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		$this->redirect($url->getUrl('shippingMethod','grid'));

	}

	public function deleteAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowShipping = Ccc::getModel('shipping_Row');
			$url = $this->getUrlObject();
			$id = $request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
				
			}
			$RowShipping->load($id);
			$result = $RowShipping->delete();
			if (!$result) 
			{
				throw new Exception("Error Processing Request", 1);
			}	
			$message->addMessage('data deleted successfully');
		} catch (Exception $e) {
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('shippingMethod','grid'));
	}
}

?>