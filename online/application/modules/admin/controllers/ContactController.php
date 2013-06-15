<?php

class Admin_ContactController extends Zend_Controller_Action
{

	public $contactManager;

    function preDispatch()
    {
        if (!Auth::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
        }
    }
		
	public function init() 
	{ 
    	// Set the layout
    	$this->_helper->layout->setLayout('page');	
    		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('perform', 'json');
		$ajaxContext->initContext();		
			
		$this->view->headerText = "Contact";
		$this->contactManager = new Contact_Manager();
	
	}
		
    public function indexAction()
    {
    
		$request 	= $this->getRequest();    	
		$page 		= $request->getParam("page","1");
		$sterge 	= $request->getParam("sterge","");

    	if ($sterge != "") {
	        $where = 'id = ' . $sterge;
			$this->contactManager->table->delete($where);
    	}    
    
    	$this->view->contacts = $this->contactManager->getContacts();

		$page = $request->getParam("page","1");
		$this->view->contacts = Zend_Paginator::factory($this->view->contacts);
		$this->view->contacts->setCurrentPageNumber($page);
		$this->view->contacts->setItemCountPerPage(10);
		$this->view->contacts->setPageRange(5);
    	
    	$this->view->paginator = $this->view->contacts;
    }
    
    
}