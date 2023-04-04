<?php
require_once 'Model/Core/Request.php';
class Model_Core_Url
{
	public function getCurrentUrl()
	{
		$url = $_SERVER['REQUEST_SCHEME'].'//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		return $url;
	}

	public function getUrl ($c = null, $a = null, $params = [], $resetParam = false)
	{
		$request = new Model_Core_Request();
		$final = $request->getParams();

		$required = [];

		if ($resetParam) 
		{
			$final = [];
		}

		if ($c == null) 
		{
			$required['c'] = $request->getControllerName();
		}
		else
		{
			$required['c'] = $c;
		}

		if ($a == null) 
		{
			$required['a'] = $request->getActionName();
		}
		else
		{
			$required['a'] = $a;
		}

		$final = array_merge($final , $required);
		if ($params) 
		{
			$final = array_merge($final , $params);
		}
		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].trim($_SERVER['REQUEST_URI'],$_SERVER['QUERY_STRING']).http_build_query($final);
		return $url;
	}	
}


?>