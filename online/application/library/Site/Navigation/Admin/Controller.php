<?php

class Site_Navigation_Admin_Controller extends Zend_Controller_Action
{
   
   	protected $request;
   
	public function init()
	{
		$this->_helper->layout->setLayout('admin');
		$this->request = $this->getRequest();
	}
	
	protected function post($param = "") {
		return $this->request->getParam($param,"");
	}
	        	
}