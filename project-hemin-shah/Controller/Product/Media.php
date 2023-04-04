<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Product/Media.php';
class Controller_Product_Media extends Controller_Core_Action
{
	public $images = [];

	public function setImages($images)
	{
		$this->images = $images;
	}

	public function getImages()
	{
		return $this->images;
	}

	public function gridAction()
	{
		$request = $this->getRequest();
		$productId = $request->getParams('product_id');
		if (!$productId) 
		{
			$this->errorAction();
		}
		$query = "SELECT * FROM `media` WHERE `product_id` = {$productId}";
		$mediaModel = new Model_Product_Media();
		$images = $mediaModel->fetchAll($query);
		$this->setImages($images);
		$this->getTemplate('product_media/media-grid.phtml');	
	}
	
	public function addAction()
	{
		$this->getTemplate('product_media/media-add.phtml');	
	}
	
	public function insertAction()
	{
		$request = $this->getRequest();
		$productId = $request->getParams('product_id');
		

		$name = $request->getPost('name'); 
		$data['name'] = $name;
		$data['product_id'] = $productId;
		// $data['created_at'] = date('Y-m-d h:i:sa');
		$mediaModel = new Model_Product_Media();
		$insertedId = $mediaModel->insert($data);

		$target_dir = "View/product_media/images/";
		$extension = explode('.',$_FILES["image"]["name"]);
		$fileName = $insertedId.'.'.$extension[1];
		$target_file = $target_dir . $fileName;
		$moveFile = move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
		$filedata = ['file_name'=>$fileName];
		$result = $mediaModel->update($filedata,$insertedId);
		if (!$result) {
			$this->errorAction('Failed to update file name !!!');
		}else{
			$this->redirect('index.php?c=product_media&a=grid&product_id='.$productId.'');
		}
	}
	
	public function saveAction()
	{
		$request = $this->getRequest();
		$productId = $request->getParams('product_id');
		$mediaModel = new Model_Product_Media();
		if ($productId && $request->isPost()) {
			$basicData = ['base'=>0,'thumbnail'=>0,'small'=>0,'gallery'=>0];
			$condition = ['product_id'=>$productId];
			$result = $mediaModel->update($basicData,$condition);
			$data = $request->getPost();
			if (array_key_exists('base',$data)) 
			{
			$basicData = ['base'=>1];
			$result = $mediaModel->update($data,$data["base"]);
			}

			if (array_key_exists('thumbnail',$data)) 
			{	
			$basicData = ['thumbnail'=>1];
			$result = $mediaModel->update($basicData,$data["thumbnail"]);
			}

			if (array_key_exists('small',$data)) 
			{
			$basicData = ['small'=>1];
			$result = $mediaModel->update($basicData,$data["small"]);
			}	

			if (array_key_exists('gallery',$data)) 
			{
			$condition = $data["gallery"];
			$basicData = ['gallery'=>1];
			$result = $mediaModel->update($basicData,$condition);
			}
			if (!$result) 
			{
				$request->errorAction();
			}
			else
			{
				$this->redirect('index.php?c=product_media&a=grid&product_id='.$productId.'');
			}
		}else
		{
			$this->errorAction('Invalid request !!!');
		}
		
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$imageId = $request->getParams('media_id');
		$productId = $request->getParams('product_id');
		$mediaModel = new Model_Product_Media();
		$result = $mediaModel->delete($imageId);
		if (!$result) {
			$this->errorAction('Failed to delete image!!!');
		}else{
			$this->redirect('index.php?c=product_media&a=grid&product_id='.$productId);
		}
	}
}

?>