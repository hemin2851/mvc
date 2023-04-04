<?php
require_once 'Model/Core/Request.php';
class Controller_Core_Front
{
	public function init()
	{
		$request = new Model_Core_Request();
		$controllername = $request->getControllerName();
		$ControllerClassName = 'Controller_'.ucfirst($controllername);
		$ControllerClassPath = str_replace('_', '/', $ControllerClassName);
		require_once "{$ControllerClassPath}.php";
		$controller = new $ControllerClassName();
		$action = $request->getActionName().'Action';
		if (!method_exists($controller, $action)) 
		{
			throw new Exception("Error Processing Request", 1);
			
		}
		else
		{
			$controller->$action();
		}
	}

	
}
?>