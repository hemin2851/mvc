<?php
class Controller_paymentMethod extends Controller_Core_Action
{
	public function gridAction()
	{
		try 
		{
			$message = $this->getMessageObject();
			$RowPayment = Ccc::getModel('payment_Row');
			$query = "SELECT * FROM `payment_method`";
			$payments = $RowPayment->fetchAll($query);
			if (!$payments) 
			{
				throw new Exception("data not fetched", 1);
			}
		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage,$message::FAILURE);		
		}
		$this->getView()->setTemplate('paymentMethod/payment-grid.phtml')->setData($payments);
		$this->render();
		
	}

	public function addAction()
	{
		$RowPayment = Ccc::getModel('payment_Row');
		$this->getView()->setTemplate('paymentMethod/payment-edit.phtml')->setData($RowPayment);
		$this->render();
	}

	
	public function editAction()
	{
		try {
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$RowPayment = Ccc::getModel('payment_Row');
			$id = $request->getParams('id');
			if (!$id) 
			{
				throw new Exception("id not found", 1);
			}
			$payment = $RowPayment->load($id);
			
		} catch (Exception $e) {
			$message->addMessage($e->getMessage(),$message::FAILURE);
			
		}
		
		$this->getView()->setTemplate('paymentMethod/payment-edit.phtml')->setData($payment);
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
			$payment = $request->getPost('payment');
			if (!$payment) 
			{
				throw new Exception("no data posted", 1);
			}
			$RowPayment = Ccc::getModel('payment_Row');
			if (($id = (int) $request->getParams('id'))) 
			{
				if (!$RowPayment->load($id)) 
				{
					throw new Exception("Error Processing Request", 1);
				}
				$payment['payment_method_id'] = $RowPayment->payment_method_id;
			}
			$RowPayment->setData($payment);
			if ( $RowPayment->payment_method_id) 
			{
				$RowPayment->updated_at = date('Y-m-d H:i:s');
			}
			else
			{
				$RowPayment->created_at = date('Y-m-d H:i:s');
			}
			if (!$RowPayment->save()) 
			{
				throw new Exception("Error Processing Request", 1);
			}
			$message->addMessage('data saved successfully');

		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		$this->redirect($url->getUrl('paymentMethod','grid'));

	}

	
	public function deleteAction()
	{
		try 
		{
			$paymentModel = new Model_paymentMethod();
			$message = $this->getMessageObject();
			$request = $this->getRequest();
			$url = $this->getUrlObject();
			$id = $request->getParams('id');
			if (!$id) 
			{
				$request->errorAction();
			}
			$result = $paymentModel->delete($id);
			if (!$result) 
			{
				$request->errorAction();
			}
			$message->addMessage('data deleted successfully');
			
		} 
		catch (Exception $e) 
		{
			$message->addMessage($e->getMessage(),$message::FAILURE);
		}
		
		$this->redirect($url->getUrl('paymentMethod','grid'));
	}
}

?>