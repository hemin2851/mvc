<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Salesman/Price.php';
class Controller_Salesman_Price extends Controller_Core_Action
{
	protected $prices = [];

	public function setPrices($prices)
	{
		$this->prices = $prices;
		return $this;
	}

	public function getPrices()
	{
		return $this->prices;
	}

	public function setSalesmen($salesmen)
	{
		$this->salesmen = $salesmen;
		return $this;
	}

	public function getSalesmen()
	{
		return $this->salesmen;
	}

	public function gridAction()
	{
		$request = $this->getRequest();
		$salesmanId = $request->getParams('salesmen_id');
		if (!$salesmanId) {
			$request->errorAction();
		}
		$query = 'SELECT * FROM `salesmen`';
		$priceModel = new Model_Salesman_Price();
		$salesmen = $priceModel->fetchAll($query);
		$this->setSalesmen($salesmen);
		$join_query = "SELECT SP.entity_id, SP.salesman_price, P.sku, P.cost, P.price, P.product_id FROM `product` P LEFT JOIN `salesmen_price` SP ON P.product_id = SP.product_id AND SP.salesmen_id =
			{$salesmanId}";
		$salesman_prices = $priceModel->fetchAll($join_query);
		$this->setPrices($salesman_prices);
		$this->getTemplate('salesman_price/price-grid.phtml');
	}
	
	public function updateAction()
	{
		$request = $this->getRequest();
		$changed_prices = $request->getPost('salesman_price');
		$salesmanId = $request->getParams('salesmen_id');
		foreach ($changed_prices as $key => $value) {
		$search_query = 'SELECT `entity_id` FROM `salesmen_price` WHERE `product_id` = '.$key.' AND `salesmen_id` = '.$salesmanId.'';
		$priceModel = new Model_Salesman_Price();
		$result = $priceModel->fetchAll($search_query);
			if ($result) 
				{
					$data = ['salesman_price'=>$value,'product_id'=>$key];
					$condition = ['salesmen_id'=>$salesmanId,'product_id'=>$key];
					$result = $priceModel->update($data,$condition);
				}
				else{
					if ($value != '') 
					{
						$data = ['salesman_price'=>$value,'product_id'=>$key,'salesmen_id'=>$salesmanId];
						$result = $priceModel->insert($data);
					}
				}
		}
		$this->redirect('index.php?c=salesman_price&a=grid&salesmen_id='.$salesmanId);
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$entityId = $request->getParams('entity_id');
		$salesmanId = $request->getParams('salesmen_id');
		if (!$salesmanId || !$entityId) {
			$request->errorAction();
		}
		$priceModel = new Model_Salesman_Price();
		$result = $priceModel->delete($entityId);
		if (!$result) {
			request->errorAction();
		}else{
			$this->redirect('index.php?c=salesman_price&a=grid&salesmen_id='.$salesmanId);
		}
	}
}
?>