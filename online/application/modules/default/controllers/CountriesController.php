<?php

class CountriesController extends Site_Navigation_Default_Controller
{

	private $destManager;
	private $photoManager;

	public function init() {
		parent::init();
		$this->destManager = new Destinations_Manager();
		$this->photoManager = new Photos_Manager();
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
    	$activeCountry = $this->view->seoGet();

    	$countries 	= $this->destManager->getCountriesWithPlaces();
    	$country 	= $this->destManager->getCountriesWithOrWithoutPlaces($activeCountry);

    	$this->view->countries = $countries;
    	$this->view->places = $country["places"];
    	$this->view->activeCountry = $country;
    	$this->view->placePhotos = $this->photoManager->getRandomPhotosForDestinations($country["places"]);

		$this->view->headTitle($country["name"]." destinations.");


		$this->view->headMeta()->appendName('description', $country["name"].'. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', $country["name"].' photos photography travel opinions places destinations guide world countries');
		

    }
    
    public function opinionsAction() {
    
    	$request = $this->getRequest();
    	$activeCountry = $this->view->seoGet();

    	$countries 	= $this->destManager->getCountriesWithPlaces();
    	$country 	= $this->destManager->getCountriesWithOrWithoutPlaces($activeCountry);

    	$this->view->countries = $countries;
    	$this->view->places = $country["places"];
    	$this->view->activeCountry = $country;
    	$this->view->placePhotos = $this->photoManager->getRandomPhotosForDestinations($country["places"]);	    

		$this->view->headTitle("Travel opinions on ".$country["name"]);
    
		$this->view->headMeta()->appendName('description', 'Opinions on '.$country["name"].'. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', $country["name"].' photos photography travel opinions places destinations guide world countries');
    
    
    }
    
}