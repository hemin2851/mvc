<?php

class Controller_Product extends Controller_Core_Action
{

	public function gridAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$RowProduct = Ccc::getModel('product_Row');
			$gridquery = "SELECT * FROM `product`";
			$products = $RowProduct->fetchAll($gridquery);	
			if (!$products) 
			{
				throw new Exception("data could not fetch", 1);
			}
		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		
		$this->getView()->setTemplate('Product/product-grid.phtml')->setData($products);
		$this->render();
	}

	public function addAction()
	{
		$RowProduct = Ccc::getModel('product_Row');
		$this->getView()->setTemplate('Product/product-edit.phtml')->setData($RowProduct);
		$this->render();
	}

	
	public function editAction()
	{
		try {

			$RowProduct = Ccc::getModel('product_Row');
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$product = $RowProduct->load($id);
			
		} catch (Exception $e) {

			$message->addMessage('id not found ','failure');
			
		}
		
		$this->getView()->setTemplate('Product/product-edit.phtml')->setData($product);
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
			$product = $request->getPost('product');
			if (!$product) 
			{
				throw new Exception("no data posted", 1);
			}
			$RowProduct = Ccc::getModel('product_Row');
			if (($id = (int) $request->getParams('id'))) 
			{
				if (!$RowProduct->load($id)) 
				{
					throw new Exception("Error Processing Request", 1);
				}
				$product['product_id'] = $RowProduct->product_id;
			}
			$RowProduct->setData($product);
			if ($RowProduct->product_id) 
			{
				$RowProduct->updated_at = date('Y-m-d H:i:s');
			}
			else
			{
				$RowProduct->created_at = date('Y-m-d H:i:s');
			}
			if (!$RowProduct->save()) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			$message->addMessage('data saved successfully');

		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		$this->redirect($url->getUrl('product','grid'));
	}

	public function deleteAction()
	{
		try {

			$message = $this->getMessageObject();
			$RowProduct = Ccc::getModel('product_row');
			$request = $this->getRequest();
			$url = $this->getUrlObject();
			$id = $request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
			}
			$RowProduct->load($id);
			$result  = $RowProduct->delete();
			if (!$result) 
			{
				throw new Exception("Invalid Request", 1);
			}
			$message->addMessage('data deleted successfully');
			
		} catch (Exception $e) {
			$message->addMessage('data not deleted','failure');
			
		}
		
		$this->redirect($url->getUrl('product','grid',null,true));
	}
}

?>