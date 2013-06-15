<?php

class RandomController extends Site_Navigation_Default_Controller
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

    	$countries = $this->destManager->getCountriesWithPlaces();
    	
    	$activeCountry = rand(0, count($countries)-1);
    	$places = $countries[$activeCountry]["places"];
    	$activePlace = rand(0, count($places)-1);
    	
    	$this->view->countries = $countries;
    	$this->view->places = $places;
    	$this->view->activeCountry = $countries[$activeCountry];
    	$this->view->activePlace = $places[$activePlace];
    	
    	$this->view->photos = $this->photoManager->getPhotosByLocation($this->view->activePlace["id"]);
    	$this->view->did = $this->view->activePlace["id"];
    	
		// Set the site title
		$this->view->headTitle("Photos from ".$places[$activePlace]["name"].", ".$countries[$activeCountry]["name"]." chosen random from within our destinations");
		
		$this->view->headMeta()->appendName('description', 'Here is '.$places[$activePlace]["name"].", ".$countries[$activeCountry]["name"].'. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', $places[$activePlace]["name"].' '.$countries[$activeCountry]["name"].' photos photography travel opinions places destinations guide world countries');
		
		
    }
}