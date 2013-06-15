<?php

class Admin_UsersController extends Zend_Controller_Action
{

	private $userManager;
	private $photoManager;
	private $destManager;

    function preDispatch()
    {
        if (!Authentication_Manager::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
        }
    }	

	
	public function init() 
	{ 
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();				
    	$this->_helper->layout->setLayout('layout');
    	
    	$this->photoManager = new Photos_Manager();
    	$this->userManager = new User_Manager();
    	$this->destManager = new Destinations_Manager();
		$this->userManager->setupRecordsPerPage(20);    	
		
		$params = $this->_request->getParams();
		$queryParams = array();
		
		foreach ($params as $key => $value) {
			switch (strtolower($key)) {
				case "module":
								break;
				case "controller":
								break;
				case "action":
								break;
				default:
								$queryParams[$key] = $value;
								break;
			}
		}
		
		$this->view->queryParams = $queryParams;		
		
	}
		
    public function indexAction()
    {
    	$request = $this->getRequest();
    	$isAjax 			= $request->isXmlHttpRequest();
		$page 				= $request->getParam("page","1");
		$id 				= $request->getParam("id","");
		$this->view->page 	= $page;
		
		if ($id != "" && is_numeric($id)) {
		
			$this->photoManager->usePaging($page,9,5);
			$this->view->users 				= $this->userManager->getUsers($id);
			$this->view->photos 			= $this->photoManager->getUserPhotosWithDestinations($id);
			$this->view->id 				= $id;
			$this->view->backlink 			= "/admin/users/index/id/".$id;
	    	$this->view->destinations 		= $this->destManager->getDestinationsWithCountries();
			
			if ($isAjax) {
				$jsonData = array();
				$jsonData['photos'] = $this->view->render('photos/photo-list.phtml');
				$this->_helper->json->sendJson($jsonData);
				return;
			} else {
				$this->_helper->viewRenderer->setRender("photos");
			}
			return;
			
		} 
		
		$this->view->backlink 			= "/admin/users/";
		$this->userManager->getPage($page);
    	$this->view->users = $this->userManager->getUsers();
		
    }
    
	public function searchAction() {

    	$request = $this->getRequest();
    	$isAjax 				= $request->isXmlHttpRequest();
		$page 					= $request->getParam("page","1");
		$search 				= $request->getParam("search","");
		$this->view->page 		= $page;
		$this->view->search 	= $search;
		$this->view->backlink 	= "/admin/users/search/search/".$search;

		if ($search != "") {
			$this->userManager->getPage($page);
			$this->userManager->setSearchString($search);
	    	$this->view->users = $this->userManager->getUsers();
			
		} else {
            $this->_redirect('/admin/users/');
		} 


	}

    
}