<?php

class Flash_IndexController extends Zend_Controller_Action
{


	public function init() 
	{ 
	}


    public function indexAction()
    {
    	$request = $this->getRequest();
    	$this->view->did = $request->getParam("did",0);
    	$this->_helper->layout->setLayout('empty');
    }
    
}