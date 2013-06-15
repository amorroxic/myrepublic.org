<?php

class DestinationsController extends Site_Navigation_Default_Controller
{

	private $destManager;
	private $photoManager;

	public function init() {
		parent::init();
		$this->destManager = new Destinations_Manager();
		$this->photoManager = new Photos_Manager();
		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('perform', 'json');
		$ajaxContext->initContext();		
		
	}

    public function indexAction()
    {
    	
    	$activeCountry = rand(0, count($countries)-1);
    	$places = $countries[$activeCountry]["places"];
    	$activePlace = rand(0, count($places)-1);
    	
    	$this->view->countries = $countries;
    	$this->view->places = $places;
    	$this->view->activeCountry = $countries[$activeCountry];
    	$this->view->activePlace = $places[$activePlace];
    	
    	
		// Set the site title
		$this->view->headTitle($this->view->t("This is the index"));
    }
    
    public function contentDetailAction() {

    	$request = $this->getRequest();
    	$photoID = $request->getParam("p",0);
    	$userID = $request->getParam("u",0);
    	$favoritesByUserID = $request->getParam("f",0);
    	$activePlace = $this->view->seoGet();

    	$countries 	= $this->destManager->getCountriesWithPlaces();
    	$country 	= $this->destManager->getCountryByDestinationID($activePlace);
    	$place 		= $this->destManager->getDestination($activePlace);

    	$this->view->countries = $countries;
    	$this->view->places = $country["places"];
    	$this->view->activeCountry = $country;
    	$this->view->activePlace = $place;
    	$this->view->did = $activePlace;
    	$this->view->pid = $photoID;
    	$this->view->uid = $userID;
    	$this->view->fid = $favoritesByUserID;
    	$this->view->activePhoto = $this->photoManager->getPhoto($photoID);

		$this->view->headTitle("Photos from ".$place["name"].", ".$country["name"]);
    	
		$this->view->headMeta()->appendName('description', $place["name"].", ".$country["name"].'. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', $place["name"]." ".$country["name"].' photos photography travel opinions places destinations guide world countries');
    
    	
    	
    }
    
    public function opinionsAction() {
    	$request = $this->getRequest();
    	$activePlace = $this->view->seoGet();

    	$countries 	= $this->destManager->getCountriesWithPlaces();
    	$country 	= $this->destManager->getCountryByDestinationID($activePlace);
    	$place 		= $this->destManager->getDestination($activePlace);

    	$this->view->countries = $countries;
    	$this->view->places = $country["places"];
    	$this->view->activeCountry = $country;
    	$this->view->activePlace = $place;

		$this->view->headTitle("Travel opinions on ".$place["name"].", ".$country["name"]);

		$this->view->headMeta()->appendName('description', 'Opinions on '.$place["name"].", ".$country["name"].'. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', $place["name"]." ".$country["name"].' photos photography travel opinions places destinations guide world countries');
    

    }
    
    public function thumbnailsAction() {
    
    	$request = $this->getRequest();
    	$activePlace = $this->view->seoGet();
    	$countries 	= $this->destManager->getCountriesWithPlaces();
    	$country 	= $this->destManager->getCountryByDestinationID($activePlace);
    	$place 		= $this->destManager->getDestination($activePlace);

    	$this->view->countries = $countries;
    	$this->view->places = $country["places"];
    	$this->view->activeCountry = $country;
    	$this->view->activePlace = $place;
    	$this->view->photos = $this->photoManager->getPhotosByLocation($activePlace);

		$this->view->headTitle($place["name"].", ".$country["name"].". These are the photos we have.");

		$this->view->headMeta()->appendName('description', 'Photos from '.$place["name"].", ".$country["name"].'. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', $place["name"]." ".$country["name"].' photos photography travel opinions places destinations guide world countries');
    

    	
    }
    
    public function likeAction() {
    
    	$request = $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	
    	$photoID = $request->getParam("p","");
    	$favManager = new Photos_Favorites_Manager();
    	
    	if ($isAjax) {
    	
    		$status = $favManager->favorite($photoID);
			$jsonData = array();
			$jsonData['content'] = $status;
			$this->_helper->json->sendJson($jsonData);
    		return;
    		
    	}

        	
    }
    
}