<?php
require_once 'Model/Core/message.php';
require_once 'Model/Core/Table/Row.php';
class Controller_Core_Action 
{
	protected $request = null;
	protected $adapter = null;
	protected $message = null;
	protected $Row = null;
	protected $view = null;
	protected $url = null;

	public function getView()
	{
		if ($this->view) 
		{
			return $this->view;
		}
		$view = Ccc::getModel('core_view');
		$this->setview($view);
		return $view;
	}

	protected function setView(Model_Core_View$view)
	{
		$this->view = $view;
		return $this;
	}

	public function getMessageObject()
	{
		if ($this->message) 
		{
			return $this->message;
		}
		$message = new Model_Core_Message();
		$this->setMessageObject($message);
		return $message;
	}

	public function setMessageObject(Model_Core_Message$message)
	{
		$this->message = $message;
		return $this;
	}

	public function getUrlObject()
	{
		if ($this->url) 
		{
			return $this->url;
		}
		$url = new Model_Core_Url();
		$this->setUrlObject($url);
		return $url;
	}

	public function setUrlObject(Model_Core_Url$url)
	{
		$this->url = $url;
		return $this;
	}


	public function getRequest()
	{
		if ($this->request) 
		{
			return $this->request;
		}
		$request = new Model_Core_Request();
		$this->setRequest($request);
		return $request;
	}

	protected function setRequest(Model_Core_Request$request)
	{
		$this->request = $request;
		return $this;
	}

	public function getTemplate($templatePath)
	{
		require_once 'View'.DS.$templatePath;
	}

	public function redirect($url)
	{
		header("location:$url");
		exit();
	}

	public function render()
	{
		return $this->getView()->render();
	}
}
?>