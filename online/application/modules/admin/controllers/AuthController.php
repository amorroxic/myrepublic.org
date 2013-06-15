<?php

class Admin_AuthController extends Zend_Controller_Action
{
	
	public function init() 
	{ 
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();				
    	$this->_helper->layout->setLayout('layout');
	}
	
    public function indexAction()
    {
        $this->_redirect('/admin/admin/index');		
    }

    public function loginAction()
    {

       	$request = $this->getRequest();
	
        $this->view->message = '';
        if ($request->isPost()) {
        	
			Authentication_Manager::logout();
        	$options = array();
        	$options["user"] 	= $request->getPost('username');
        	$options["pass"] 	= $request->getPost('password');
        	$options["table"]	= "site_users";        
        	Authentication_Manager::perform($options);
			
			if (Authentication_Manager::isAdminAllowed()) {
		            $this->_redirect('/admin/admin/index');		
			} else {
            		$this->view->message = Authentication_Manager::getErrorMessage();
			}
        
        }
        
        $this->view->title = "LOGIN";
        $this->render();		
    }

    public function logoutAction()
    {
        Auth::logout();
        $this->_redirect('/admin/auth/login');
    }

}