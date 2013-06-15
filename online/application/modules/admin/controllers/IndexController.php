<?php

class Admin_IndexController extends Zend_Controller_Action
{

    function preDispatch()
    {
        if (!Authentication_Manager::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
        }
    }	

	public function init() 
	{ 
    	// Set the layout
    	$this->_helper->layout->setLayout('layout');		
	}

    public function indexAction()
    {
    	//$this->_forward('index', 'page', null, array('1' => 'index'));
    	$this->_redirect('/admin/admin/');
    }
}