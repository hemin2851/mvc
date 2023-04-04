<?php
require_once 'Controller/Core/Front.php';
session_start();
define('DS', DIRECTORY_SEPARATOR);
spl_autoload_register(function ($classname)
{
	$classpath = str_replace('_', '/', $classname);
	require_once "{$classpath}.php";
});
class Ccc
{

	public static function init()
	{
		$data = new Controller_Core_Front();
		$data->init();
	}

	public static function getModel($classname)
	{
		$classname = "Model_".$classname;
		return new $classname();
	}

	public static function getSingleton($classname)
	{
		$classname = 'Model_'.$classname;
		if (array_key_exists($classname, $_GLOBALS)) {
			return $_GLOBALS[$classname];
		}
		$_GLOBALS[$classname] = new $classname();
		return$_GLOBALS[$classname];
	}
}

Ccc::init();
?>