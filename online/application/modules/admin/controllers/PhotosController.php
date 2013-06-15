<?php

class Admin_PhotosController extends Zend_Controller_Action
{

	private $photoManager;
	private $destManager;

    function preDispatch()
    {
        if (!Authentication_Manager::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
        }
        
        $this->photoManager = new Photos_Manager();
        $this->destManager = new Destinations_Manager();
        
    }	

	
	public function init() 
	{ 
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();				
    	$this->_helper->layout->setLayout('layout');
    	
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
		$this->view->action = $this->_request->getActionName();
    	
	}
		
    public function indexAction()
    {
            $this->_redirect('/admin/photos/newest');
    }

    public function newestAction()
    {
    	$request 		= $this->getRequest();
    	$isAjax 		= $request->isXmlHttpRequest();
		$currentPage 	= $request->getParam("page","1");
    
    	$photos = $this->photoManager->getNewestPhotosWithLocations($currentPage);
    	
    	$this->view->page 				= $currentPage;
    	$this->view->photos 			= $photos;
		$this->view->backlink 			= "/admin/photos/newest";
    	$this->view->destinations 		= $this->destManager->getDestinationsWithCountries();
    	
		if ($isAjax) {
			$jsonData = array();
			$jsonData['photos'] = $this->view->render('photos/photo-list.phtml');
			$this->_helper->json->sendJson($jsonData);
			return;
		}
    	
    	
    }
  

    public function locationsAction()
    {
    	$request 	= $this->getRequest();
		$destination = $request->getParam("destination","");
		
    	$isAjax = $request->isXmlHttpRequest();
		
		if ($destination != "") {
			// we have a destination
			$currentPage = $request->getParam("page","1");
			$this->photoManager->usePaging($currentPage,9,5);
		   	$this->view->page 				= $currentPage;
			$this->view->backlink 			= "/admin/photos/locations/destination/".$destination;
	    	$this->view->destinationInfo 	= $this->destManager->getDestinationInfo($destination);
    		$this->view->country 			= $this->destManager->getCountriesWithPlaces($this->view->destinationInfo["country_id"]);
	    	$this->view->destinations 		= $this->destManager->getDestinationsWithCountries();
			$this->view->photos 			= $this->photoManager->getPhotosByLocation($destination);

			if ($isAjax) {
				$jsonData = array();
				$jsonData['photos'] = $this->view->render('photos/photo-list.phtml');
				$this->_helper->json->sendJson($jsonData);
				return;
			} else {
				$this->_helper->viewRenderer->setRender("location");
			}

			return;
		} else {
			$country = $request->getParam("country","");
			if ($country != "") {
				// we have a country
				$currentPage = $request->getParam("page","1");
				$this->photoManager->usePaging($currentPage,9,5);
			   	$this->view->page 				= $currentPage;
				$this->view->backlink 			= "/admin/photos/locations/country/".$country;
	    		$this->view->country 			= $this->destManager->getCountriesWithPlaces($country);
		    	$this->view->destinations 		= $this->destManager->getDestinationsWithCountries();
				$this->view->photos 			= $this->photoManager->getPhotosByCountry($country);
				
				if ($isAjax) {
					$jsonData = array();
					$jsonData['photos'] = $this->view->render('photos/photo-list.phtml');
					$this->_helper->json->sendJson($jsonData);
					return;
				} else {
					$this->_helper->viewRenderer->setRender("country");
				}
				
				return;
				
			} else {
				$this->view->countries = $this->destManager->getCountriesWithPlaces();
			}
		}
    }
  
    
    public function deleteAction() {
    
    	$request 	= $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	$isPost = $request->isPost();
    	
    	if ($isPost && $isAjax) {

			$photoID 		= $request->getParam("id","");
    		
    		$this->photoManager->delete($photoID);
			$jsonData = array();
			$jsonData['status'] = "ok";
			$this->_helper->json->sendJson($jsonData);
			return;
			
		} else {
		
	        $this->_redirect("/admin/photos");
    		return;
    		
		}
		
    }

    public function assignAction() {
    
    	$request 	= $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	$isPost = $request->isPost();
    	
    	if ($isPost && $isAjax) {

			$locationID 	= $request->getParam("location_id","");
			$photoID 		= $request->getParam("id","");
    		
    		$this->photoManager->assign($photoID,$locationID);
			$jsonData = array();
			$jsonData['status'] = "ok";
			$this->_helper->json->sendJson($jsonData);
			return;
			
		} else {
		
	        $this->_redirect("/admin/photos");
    		return;
    		
		}
		
    }
    
	public function searchAction() {

    	$request 			= $this->getRequest();
    	$isPost 			= $request->isPost();
    	$isAjax 			= $request->isXmlHttpRequest();
    	$search 			= $request->getParam("search","");
    	$searchFor 			= $request->getParam("search_for","");
    	$page 				= $request->getParam("page","1");

    	if ($search == "") { 
    		$this->_redirect('/admin/destinations/countries');
    		return;
    	}
    		
		$this->view->search 			= $search;
		$this->view->searchFor 			= $searchFor;
		$this->view->page				= $page;
    	$this->view->destinations 		= $this->destManager->getDestinationsWithCountries();
		$this->view->backlink 			= "/admin/photos/search/search/".$search."/search_for/".$searchFor;

		if ($searchFor == "user") {
				$this->photoManager->usePaging($page,9,5);
				$this->view->photos 	= $this->photoManager->searchUserPhotosWithDestinations($search);
		} else {
				$this->destManager->usePaging($page,9,5);
				$this->view->photos 	= $this->destManager->searchWithPhotos($search);
		}
		
		if ($isAjax) {
			$jsonData = array();
			$jsonData['photos'] = $this->view->render('photos/photo-list.phtml');
			$this->_helper->json->sendJson($jsonData);
			return;
		}

	}    
    
    
}