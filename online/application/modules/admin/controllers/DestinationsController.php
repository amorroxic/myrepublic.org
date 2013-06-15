<?php

class Admin_DestinationsController extends Zend_Controller_Action
{

	private $destManager;
	private $photoManager;

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
    	
    	$this->destManager = new Destinations_Manager();
    	$this->photoManager = new Photos_Manager();
	}
		
    public function indexAction()
    {
            $this->_redirect('/admin/destinations/countries');
    }
    
    public function countriesAction()
    {
    	$request = $this->getRequest();
    	$isPost = $request->isPost();

    	$full = $request->getParam("full","0");
    	$id = $request->getParam("id","0");
    	
    	if ($isPost) {
    		$oper = $request->getParam("operation");
    		switch ($oper) {
    			case "delete":

								$p = new Photos_Table();
								$d = new Destinations_Table();
								
    							$country = $this->destManager->getCountriesWithOrWithoutPlaces($id);
    							if (isset($country["places"])) {
    								foreach ($country["places"] as $place) {
    									$photos = $this->photoManager->getPhotosByLocation($place["id"]);
    									$this->photoManager->deletePhotos($photos);
		    							$where = "id = ".$place["id"];
		    							$d->delete($where);
    								}
    							}
    							$where = "id = ".$country["id"];
    							$d->delete($where);
    							$this->_redirect('/admin/destinations/countries');
    							return;
    							break;
    			case "rename":
    							$newName = $request->getParam("country","");
    							if ($newName != "") {
	    							$ct = new Destinations_Table();
	    							$fields = array(
	    								"name" => $newName	
	    							);
	    							$where = "id = ".$id;
	    							$ct->update($fields,$where);
    							}
    							break;
    		}
    	}
    	
    	if ($id != "0") {
    		$this->view->country = $this->destManager->getCountriesWithOrWithoutPlaces($id);
    		$this->_helper->viewRenderer->setRender("country");
    	} else {
	    	$this->view->full = $full;
	    	if ($full == "1") {
		    	$this->view->countries = $this->destManager->getCountriesWithOrWithoutPlaces();
	    	} else {
		    	$this->view->countries = $this->destManager->getCountriesWithPlaces();
	    	}
    	}
    	
    }

    public function locationsAction()
    {
    	$request = $this->getRequest();
    	$isPost = $request->isPost();
    	$id = $request->getParam("id","0");

    	if ($isPost) {
    		$oper = $request->getParam("operation");
    		switch ($oper) {
    			case "delete":

								$d = new Destinations_Table();
    							$place = $this->destManager->getDestination($id);
								$photos = $this->photoManager->getPhotosByLocation($place["id"]);
    							$this->photoManager->deletePhotos($photos);
    							$where = "id = ".$place["id"];
    							$d->delete($where);
    							$this->_redirect('/admin/destinations/locations');
    							return;
    							break;
    			case "rename":
    							$newName = $request->getParam("place","");
    							if ($newName != "") {
	    							$ct = new Destinations_Table();
	    							$fields = array(
	    								"name" => $newName	
	    							);
	    							$where = "id = ".$id;
	    							$ct->update($fields,$where);
    							}
    							break;
    			case "move":
    							$countryID = $request->getParam("country","");
    							if ($countryID != "") {
	    							$ct = new Destinations_Table();
	    							$fields = array(
	    								"parent_id" => $countryID	
	    							);
	    							$where = "id = ".$id;
	    							$ct->update($fields,$where);
    							}
    							break;
    		}
    	}

    	if ($id != "0") {
    		$this->view->country = $this->destManager->getCountryByDestinationID($id);
    		$this->view->place = $this->destManager->getDestination($id);
    		$this->view->allCountries = $this->destManager->getCountriesWithoutPlaces();
    		$this->_helper->viewRenderer->setRender("location");
    	} else {
			$this->view->countries = $this->destManager->getCountriesWithPlaces();
    	}    	
    
    }

	public function searchAction() {

    	$request = $this->getRequest();
    	$isPost = $request->isPost();
    	$search = $request->getParam("search","");

    	if ($isPost) {
    		$this->view->countries = $this->destManager->search($search);
    		$this->view->search = $search;
    	} else {
            $this->_redirect('/admin/destinations/countries');
    	}

	}

    public function opinionsAction()
    {
    }
    
}