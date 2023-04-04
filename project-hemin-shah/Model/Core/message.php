<?php
require_once 'session.php';

class Model_Core_Message
{
	protected $session = null;
	const SUCCESS = 'success';
	const FAILURE = 'failure';
	const NOTICE = 'notice';

	public function __construct()
	{
		$this->getSession();
	}

	public function setSession(Model_Core_Session $session)
	{
		$this->session = $session;
		return $this;
	}

	public function getSession()
	{
		if ($this->session) 
		{
			return $this->session;
		}
		$session = new Model_Core_Session();
		$this->setSession($session);
		return $session;
	}

	public function addMessage($message , $type = null)
	{
		if (!$type) 
		{
			$type = self::SUCCESS;
		}
		$messages = $this->getMessage();
		$messages[$type] = $message;
		$this->getSession()->set('message',$messages);
		return $this;
	}

	public function getMessage()
	{
		return $this->getSession()->get('message');
	}

	public function clearMessage()
	{
		$this->getSession()->set('message',[]);
		return $this;
	}


	
}

?>