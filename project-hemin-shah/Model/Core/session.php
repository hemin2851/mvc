<?php
class Model_Core_Session
{
	
	public function start()
	{
		session_start();
		return $this;
	}
	public function getId()
	{
		return session_id();
	}

	public function destroy()
	{
		session_destroy();
		return $this;
	}

	public function set($key , $value)
	{
		$_SESSION[$key] = $value;
		return $this;
	}

	public function get($key)
	{
		if (!array_key_exists($key,$_SESSION))
		{
			return null;
		}
		return $_SESSION[$key];
	}

	public function unset($key)
	{
		if (!array_key_exists($key, $_SESSION)) 
		{
			unset($_SESSION[$key]);
			return $this;
		}
	}
}
?>